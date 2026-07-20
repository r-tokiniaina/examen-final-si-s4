<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'type',
        'num_source',
        'num_destination',
        'montant',
        'frais',
        'date_operation'
    ];

    public function getOperationsByNumero(string $numeroClient): array
    {
        return $this->groupStart()
                        ->where('num_source', $numeroClient)
                        ->orWhere('num_destination', $numeroClient)
                    ->groupEnd()
                    ->orderBy('date_operation', 'DESC')
                    ->findAll();
    }

    public function getSoldeByNumero(string $numeroClient): float
    {
        // 1. Entrées (Dépôts + Transferts reçus)
        $entrees = $this->selectSum('montant', 'total')
                        ->where('num_destination', $numeroClient)
                        ->first()['total'] ?? 0;

        // 2. Sorties (Retraits + Transferts envoyés + Frais)
        $sorties = $this->select('SUM(montant + frais) as total')
                        ->where('num_source', $numeroClient)
                        ->first()['total'] ?? 0;

        return (float) ($entrees - $sorties);
    }
}
