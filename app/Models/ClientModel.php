<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    // Configuration de la base de données
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    // Le controller s'attend à un tableau (ex: $client['numero'])
    protected $returnType       = 'array';

    // Pas de timestamps ni de suppression logique dans votre schéma SQLite
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    // Protection contre l'injection de masse (Mass Assignment)
    protected $allowedFields    = ['numero', 'pct_epargne'];

    // Règles de validation pour les futures insertions/mises à jour
    protected $validationRules      = [
        'id' => 'is_natural',
        'numero' => 'required|max_length[13]|is_unique[clients.numero,id,{id}]'
    ];

    protected $validationMessages   = [
        'numero' => [
            'required'   => 'Le numéro est obligatoire.',
            'max_length' => 'Le format du numéro est incorrect.',
            'is_unique'  => 'Ce numéro est déjà enregistré.'
        ]
    ];

    protected $skipValidation       = false;


    public function findOrInsert($numero)
    {
        $prefixe_model = model('PrefixeModel');
        $prefixes = array_column($prefixe_model->findAllByOperateur(null), 'valeur');

        $numero = $this->formatNumero($numero, $prefixes);

        if ($numero !== null) {
            $client = $this->where('numero', $numero)->first();
            if ($client == null) {
                $this->insert(['numero' => $numero, 'pct_epargne' => 0]);
                $client = $this->where('numero', $numero)->first();
            }
            return $client;
        } else {
            return null;
        }
    }

    public function findByNumero($numero)
    {
        return $this->where('numero', $numero)->first();
    }

    public function formatNumero($numero, $prefixes = null)
    {
        $numero = str_replace(' ', '', $numero ?? '');

        $prefixe_model = model('PrefixeModel');
        $prefixes = $prefixes ?? array_column($prefixe_model->findAll(), 'valeur');

        $pattern = '/^(' . implode('|', $prefixes) . ')(\d{2})(\d{3})(\d{2})$/';

        if (preg_match($pattern, $numero)) {
            return preg_replace($pattern, '$1 $2 $3 $4', $numero);
        } else {
            return null;
        }
    }
}
