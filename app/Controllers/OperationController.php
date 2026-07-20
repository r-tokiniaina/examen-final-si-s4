<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\OperationModel;
use App\Models\BaremeFraisModel;

class OperationController extends BaseController
{
    public function index()
    {
        $numeroClient = session()->get('client_numero');

        $operationModel   = new OperationModel();
        $baremeFraisModel = new BaremeFraisModel();

        $data = [
            'operations' => $operationModel->getOperationsByNumero((string) $numeroClient),
            'solde'      => $operationModel->getSoldeByNumero((string) $numeroClient),
            'baremes'    => $baremeFraisModel->findAll()
        ];

        return view('Client/operations', $data);
    }

    public function store()
    {
        $numeroClient = session()->get('client_numero');

        if (empty($numeroClient)) {
            return redirect()->back()->with('error', 'Session expirée ou numéro client introuvable.');
        }

        $type    = (int) $this->request->getPost('type');
        $montant = (float) $this->request->getPost('montant');
        $numDest = $this->request->getPost('num_destination');

        $operationModel   = new OperationModel();
        $baremeFraisModel = new BaremeFraisModel();

        $frais = $baremeFraisModel->calculerFrais($type, $montant);

        $data = [
            'type'           => $type,
            'montant'        => $montant,
            'frais'          => $frais,
            'date_operation' => date('Y-m-d H:i:s'),
        ];

        if ($type === 1) { // Dépôt
            $data['num_source']      = null;
            $data['num_destination'] = trim($numeroClient);
        } elseif ($type === 2) { // Retrait
            $data['num_source']      = trim($numeroClient);
            $data['num_destination'] = null;
        } elseif ($type === 3) { // Transfert
            $data['num_source']      = trim($numeroClient);
            $data['num_destination'] = trim($numDest);
        }

        $operationModel->insert($data);

        return redirect()->to(base_url('client/operations'))->with('success', 'Opération enregistrée avec succès.');
    }
}
