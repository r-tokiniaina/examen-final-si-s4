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
}
