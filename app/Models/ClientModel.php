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
    protected $allowedFields    = ['numero'];

    // Règles de validation pour les futures insertions/mises à jour
    protected $validationRules      = [
        'numero' => 'required|max_length[10]|is_unique[clients.numero,id,{id}]'
    ];

    protected $validationMessages   = [
        'numero' => [
            'required'   => 'Le numéro est obligatoire.',
            'max_length' => 'Le numéro ne doit pas dépasser 10 caractères.',
            'is_unique'  => 'Ce numéro de client est déjà enregistré.'
        ]
    ];

    protected $skipValidation       = false;

    // ==========================================
    // MÉTHODES PERSONNALISÉES (Optionnel mais recommandé)
    // ==========================================

    /**
     * Récupère un client directement via son numéro.
     * Cette méthode centralise la requête SQL pour garder le contrôleur propre.
     *
     * @param string $numero
     * @return array|null
     */
    public function getClientByNumero(string $numero)
    {
        return $this->where('numero', $numero)->first();
    }
}
