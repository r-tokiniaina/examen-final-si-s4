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
        if (empty($numeroClient)) {
            return [];
        }

        return $this->groupStart()
                        ->where('num_source', $numeroClient)
                        ->orWhere('num_destination', $numeroClient)
                    ->groupEnd()
                    ->orderBy('date_operation', 'DESC')
                    ->findAll();
    }

    public function getSoldeByNumero(string $numeroClient): float
    {
        if (empty($numeroClient)) {
            return 0.0;
        }

        // Calcul des entrées (Dépôts reçus ou Transferts reçus)
        $entreeData = $this->selectSum('montant', 'total')
                           ->where('num_destination', $numeroClient)
                           ->first();
        $entrees = (float) ($entreeData['total'] ?? 0);

        // Calcul des sorties (Retraits ou Transferts envoyés + Frais)
        $sortieData = $this->select('SUM(montant + frais) as total')
                           ->where('num_source', $numeroClient)
                           ->first();
        $sorties = (float) ($sortieData['total'] ?? 0);

        return $entrees - $sorties;
    }
}
