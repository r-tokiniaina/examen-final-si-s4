<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_operateur', 'valeur'];

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
        'valeur' => 'required|regex_match[/^0[0-9]{2}$/]',
    ];
    protected $validationMessages = [
        'valeur' => [
            'regex_match' => 'Le préfixe doit comporter exactement 3 chiffres et commencer par un 0',
        ],
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


    public function findAllByOperateur($id_operateur)
    {
        if ($id_operateur == null || $id_operateur == 0 || $id_operateur == '') {
            return $this->where('id_operateur', null)->findAll();
        }
        else {
            return $this->where('id_operateur', $id_operateur)->findAll();
        }
    }

    public function findByNumero($numero)
    {
        return $this->where('valeur', substr($numero, 0, 3))->first();
    }
}
