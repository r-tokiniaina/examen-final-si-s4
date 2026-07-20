<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'baremes_frais';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type_operation', 'montant_min', 'montant_max', 'frais'
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
    protected $validationRules = [
        'type_operation' => 'required|is_not_unique[types_operations.id]',
        'montant_min'    => 'required|is_natural',
        'montant_max'    => 'required|is_natural',
        'frais'          => 'required|is_natural',
    ];

    protected $validationMessages = [
        'type_operation' => [
            'required'       => 'Le type d’opération est obligatoire',
            'is_not_unique'  => 'Le type d’opération sélectionné n’existe pas'
        ],
        'montant_min' => [
            'required'   => 'Le montant minimum est obligatoire',
            'is_natural' => 'Le montant minimum doit être un entier positif'
        ],
        'montant_max' => [
            'required'                     => 'Le montant maximum est obligatoire',
            'is_natural'                   => 'Le montant maximum doit être un entier positif'
        ],
        'frais' => [
            'required'   => 'Le montant des frais est obligatoire',
            'is_natural' => 'Le montant des frais doit être un entier positif'
        ]
    ];
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


    public function findAllByTypeOperation($id_type_operation)
    {
        return $this->where('type_operation', $id_type_operation)->orderBy('montant_min', 'ASC')->findAll();
    }
}
