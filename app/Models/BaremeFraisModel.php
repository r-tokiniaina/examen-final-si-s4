<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table         = 'baremes_frais';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'type_operation',
        'montant_min',
        'montant_max',
        'frais'
    ];

    public function calculerFrais(int $type, float $montant): float
    {
        $row = $this->where('type_operation', $type)
                    ->where('montant_min <=', $montant)
                    ->where('montant_max >=', $montant)
                    ->first();

        return $row ? (float) $row['frais'] : 0.0;
    }
}
