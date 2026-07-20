<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class OperationController extends BaseController
{
    public function index()
    {
        $numeroClient = session()->get('client_numero');
        $model = new OperationModel();

        $data = [
            'operations' => $model->getOperationsByNumero($numeroClient),
            'solde'      => $model->getSoldeByNumero($numeroClient)
        ];

        return view('Client/operations', $data);
    }

    public function store()
    {
        $numeroClient = session()->get('client_numero');
        $type         = (int) $this->request->getPost('type');
        $montant      = (float) $this->request->getPost('montant');
        $numDest      = $this->request->getPost('num_destination');

        $frais = 0; // Ajuster selon les frais métier

        $data = [
            'type'           => $type,
            'montant'        => $montant,
            'frais'          => $frais,
            'date_operation' => date('Y-m-d H:i:s'),
        ];

        if ($type === 1) {
            // Dépôt : Le numéro connecté est le destinataire
            $data['num_source']      = null;
            $data['num_destination'] = $numeroClient;
        } elseif ($type === 2) {
            // Retrait : Le numéro connecté est la source
            $data['num_source']      = $numeroClient;
            $data['num_destination'] = null;
        } elseif ($type === 3) {
            // Transfert : Source = connecté, Destination = saisi
            $data['num_source']      = $numeroClient;
            $data['num_destination'] = trim($numDest);
        }

        $model = new OperationModel();
        $model->insert($data);

        return redirect()->to(base_url('client/operations'))->with('success', 'Opération effectuée avec succès.');
    }
}
