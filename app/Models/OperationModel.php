<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type', 'montant', 'frais', 'num_source', 'num_destination', 'date_operation'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function findAllPourNumero($numero)
    {
        return $this->where('num_source', $numero)
            ->orWhere('num_destination', $numero)
            ->orderBy('date_operation', 'DESC')
            ->findAll();
    }

    public function findAllPourTypeParJour($type, $dateInput): array
    {
        // 1. Calcul du lundi et du dimanche de la même semaine
        $date = new \DateTime($dateInput);

        // Si le jour est un dimanche, N renvoie 7, sinon 1 pour lundi à 6 pour samedi
        $jourSemaine = $date->format('N');

        $lundi = clone $date;
        $lundi->modify('-' . ($jourSemaine - 1) . ' days');

        $dimanche = clone $lundi;
        $dimanche->modify('+6 days');

        $dateDebut = $lundi->format('Y-m-d 00:00:00');
        $dateFin   = $dimanche->format('Y-m-d 23:59:59');

        // 2. Requête Query Builder pour récupérer les données groupées par jour
        $resultats = $this->select('strftime(\'%w\', date_operation) as jour_index, SUM(frais) as total_frais')
            ->where('type', $type)
            ->where('date_operation >=', $dateDebut)
            ->where('date_operation <=', $dateFin)
            ->groupBy("strftime('%w', date_operation)")
            ->findAll(); // Renvoie un tableau d'objets ou de tableaux associatifs [1, 2]

        // 3. Initialisation du tableau final avec 7 zéros (Lundi à Dimanche)
        $output = array_fill(0, 7, 0);

        // 4. Remplissage du tableau final avec les données de la base
        foreach ($resultats as $row) {
            $index = (int)$row['jour_index']; // 0 = Lundi, 1 = Mardi, ..., 6 = Dimanche
            $output[$index] = (float)$row['total_frais'];
        }

        return $output;
    }

    /**
     * Retourne un tableau de 7 éléments (Lundi à Dimanche) contenant
     * le nombre de clients distincts ayant effectué une opération par jour
     * pour la semaine contenant la date donnée.
     */
    public function findNbClientsParJour($dateInput): array
    {
        $date = new \DateTime($dateInput);
        $jourSemaine = $date->format('N');
        $lundi = clone $date;
        $lundi->modify('-' . ($jourSemaine - 1) . ' days');
        $dimanche = clone $lundi;
        $dimanche->modify('+6 days');
        $dateDebut = $lundi->format('Y-m-d 00:00:00');
        $dateFin   = $dimanche->format('Y-m-d 23:59:59');

        $sql = "
            SELECT strftime('%%w', o.date_operation) as jour_index, COUNT(DISTINCT c.id) as nb_clients
            FROM operations o
            JOIN clients c ON c.numero IN (o.num_source, o.num_destination)
            WHERE o.date_operation >= ? AND o.date_operation <= ?
            GROUP BY strftime('%%w', o.date_operation)
        ";
        $query = $this->db->query($sql, [$dateDebut, $dateFin]);
        $resultats = $query->getResultArray();

        $output = array_fill(0, 7, 0);
        foreach ($resultats as $row) {
            $index = (int)$row['jour_index'];
            $output[$index] = (int)$row['nb_clients'];
        }
        return $output;
    }

    /**
     * Calcule pour chaque opérateur tiers (ayant un id_operateur non nul)
     * le total des frais perçus sur les opérations dont le numéro source
     * correspond à l'un de leurs préfixes, ainsi que leur commission.
     *
     * Retourne un tableau de la forme :
     * [
     *   ['libelle' => 'Airtel', 'total_frais' => ..., 'pct_commission' => 10],
     *   ['libelle' => 'Yas',    'total_frais' => ..., 'pct_commission' => 20],
     * ]
     */
    public function findSituationMontantsEnvoyer(): array
    {
        $sql = "
            SELECT
                op.libelle,
                op.pct_commission,
                COALESCE(SUM(o.frais), 0) AS total_frais
            FROM operations o
            JOIN prefixes p ON SUBSTR(o.num_source, 1, 3) = p.valeur
            JOIN operateurs op ON p.id_operateur = op.id
            WHERE o.type IN (2, 3)
              AND p.id_operateur IS NOT NULL
            GROUP BY op.id, op.libelle, op.pct_commission
            ORDER BY op.libelle
        ";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    /**
     * Calcule les gains via les frais pour chaque type d'opération (retrait/transfert),
     * séparés entre l'opérateur (prefixes avec id_operateur IS NULL) et les autres opérateurs.
     *
     * Retourne un tableau de la forme :
     * [
     *   ['type' => 2, 'categorie' => 'operateur',     'total_frais' => ...],
     *   ['type' => 2, 'categorie' => 'autres',        'total_frais' => ...],
     *   ['type' => 3, 'categorie' => 'operateur',     'total_frais' => ...],
     *   ['type' => 3, 'categorie' => 'autres',        'total_frais' => ...],
     * ]
     */
    public function findSituationGainByOperateur(): array
    {
        $sql = "
            SELECT
                o.type,
                CASE WHEN p.id_operateur IS NULL THEN 'operateur' ELSE 'autres' END AS categorie,
                COALESCE(SUM(o.frais), 0) AS total_frais
            FROM operations o
            LEFT JOIN prefixes p ON SUBSTR(o.num_source, 1, 3) = p.valeur
            WHERE o.type IN (2, 3)
            GROUP BY o.type,
                CASE WHEN p.id_operateur IS NULL THEN 'operateur' ELSE 'autres' END
            ORDER BY o.type, categorie
        ";

        $query = $this->db->query($sql);
        return $query->getResultArray();
    }

    public function calculerFrais($id_type_operation, $montant, $nums_destination, $inclure_frais)
    {
        $prefixe_model = model('PrefixeModel');
        $bareme_frais_model = model('BaremeFraisModel');
        $operateur_model = model('OperateurModel');

        if ($id_type_operation == 3 && empty($nums_destination)) {
            return false;
        }
        $prefixe = $prefixe_model->findByNumero($nums_destination[0]);
        if ($id_type_operation != 3 || $prefixe['id_operateur'] === null) { // Même opérateur
            $frais = $bareme_frais_model->findFraisByTypeAndMontant($id_type_operation, $montant);
            if ($inclure_frais === true) {
                $montant -= $frais;
            }
        }
        else {
            $operateur = $operateur_model->find($prefixe['id_operateur']);
            $frais = (int) ($montant * $operateur['pct_commission'] / 100.0);
        }

        return [
            'montant' => $montant,
            'frais' => $frais,
        ];
    }
}
