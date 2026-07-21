<?php

namespace App\Controllers;

class ClientController extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    // ==========================================
    // AUTHENTIFICATION
    // ==========================================

    // GET /login
    public function login()
    {
        return view('Client/login');
    }

    // POST /login
    public function postLogin()
    {
        $numero = $this->request->getPost('numero');

        $client_model = model('ClientModel');

        $client = $client_model->findOrInsert($numero);

        if ($client === null) {
            return redirect()->back()->with('message_erreur', 'Numéro de téléphone invalide');
        } else {
            session()->set('client', $client);
            return redirect()->to('/client/operations')->with('message_succes', 'Connexion réussie');
        }
    }

    // POST /logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // ==========================================
    // ESPACE CLIENT : OPÉRATIONS
    // ==========================================

    // GET /client/operations
    public function operations()
    {
        $operation_model = model('OperationModel');
        $solde_model = model('SoldeModel');

        $client = session()->get('client');

        $operations = $operation_model->findAllPourNumero($client['numero']);
        $solde = $solde_model->find($client['numero'])['montant'] ?? 0;

        return view('Client/operations', [
            'operations' => $operations,
            'solde' => $solde,
        ]);
    }

    // POST /client/operations/new
    public function postOperationsNew()
    {
        $id_type_operation = (int) $this->request->getPost('type');
        $montant = (int) $this->request->getPost('montant');
        $num_destination = trim($this->request->getPost('num_destination') ?? '');
        $inclure_frais = (bool) $this->request->getPost('inclure_frais');

        $client_model = model('ClientModel');
        $operation_model = model('OperationModel');
        $solde_model = model('SoldeModel');
        $epargne_model = model('EpargneModel');

        $nums_destination_bruts = explode(',', $num_destination);
        $nums_destination = [];
        foreach ($nums_destination_bruts as $num_brut) {
            $num = $client_model->formatNumero($num_brut);
            if ($id_type_operation == 3 && $num === null) {
                return redirect()->back()->with('message_erreur', 'Un numéro de destination est invalide');
            }
            $nums_destination[] = $num;
        }
        $info_montant = $operation_model->calculerFrais($id_type_operation, $montant, $nums_destination, $inclure_frais);

        $client = session()->get('client');

        $solde = $solde_model->find($client['numero'])['montant'] ?? 0;
        if ($id_type_operation != 1 && $info_montant['montant'] + $info_montant['frais'] > $solde) {
            return redirect()->back()->with('message_erreur', 'Votre solde est insuffisant');
        }

        if ($id_type_operation == 3) { // Transfert
            $source = $client['numero'];
            $destinations = $nums_destination;

            if (empty($destinations)) {
                return redirect()->back()->with('message_erreur', 'Aucun numéro valide pour le transfert');
            }

            $date = date('Y-m-d H:i:s');
            foreach ($destinations as $d) {
                $m = (int) $info_montant['montant'] / count($destinations);
                $f = (int) $info_montant['frais'] / count($destinations);
                $c = $client_model->findByNumero($d);
                if ($c != null) {
                    $epargne = (int) ($m * $c['pct_epargne'] / 100.0);
                    $epargne_model->insert([
                        'numero' => $d,
                        'montant' => $epargne,
                        'date_operation' => $date,
                    ]);
                }
                $operation_model->insert([
                    'type'            => $id_type_operation,
                    'montant'         => $m,
                    'frais'           => $f,
                    'num_source'      => $source,
                    'num_destination' => $d,
                    'date_operation'  => $date
                ]);
            }
        } else { // Dépôt et retrait
            $source      = null;
            $destination = null;

            if ($id_type_operation == 1) { // Dépôt
                $destination = $client['numero'];
            }
            else if ($id_type_operation == 2) { // Retrait
                $source = $client['numero'];
            }

            $operation_model->insert([
                'type'            => $id_type_operation,
                'montant'         => (int) $info_montant['montant'],
                'frais'           => (int) $info_montant['frais'],
                'num_source'      => $source,
                'num_destination' => $destination,
                'date_operation'  => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to('/client/operations')->with('message_succes', 'Opération enregistrée avec succès.');
    }

    // GET /client/operations/calcul-frais
    public function operationsCalculFrais()
    {
        $id_type_operation = (int) $this->request->getGet('type');
        $montant = (int) $this->request->getGet('montant');
        $num_destination = trim($this->request->getGet('num_destination') ?? '');
        $inclure_frais = (bool) $this->request->getGet('inclure_frais');

        $client_model = model('ClientModel');
        $operation_model = model('OperationModel');

        $nums_destination_bruts = explode(',', $num_destination);
        $nums_destination = [];
        foreach ($nums_destination_bruts as $num_brut) {
            $num = $client_model->formatNumero($num_brut);
            if ($id_type_operation === 3 && $num === null) {
                return $this->response->setJSON(false);
            }
            $nums_destination[] = $num;
        }

        return $this->response->setJSON($operation_model->calculerFrais($id_type_operation, $montant, $nums_destination, $inclure_frais));
    }

    // GET /client/epargnes
    public function epargnes()
    {
        $epargne_model = model('EpargneModel');

        $client = session()->get('client');

        $epargne = $epargne_model->findEpargneByNumero($client['numero']);

        return view('Client/epargnes', [
            'client' => $client,
            'epargne' => $epargne,
        ]);
    }

    // POST /client/epargnes/update
    public function postEpargnesUpdate()
    {
        $pct_epargne = $this->request->getPost('pct_epargne');

        $client_model = model('ClientModel');

        $client = session()->get('client');

        $client = $client_model->findByNumero($client['numero']);

        $client['pct_epargne'] = $pct_epargne;
        $client_model->save($client);

        return redirect()->to('/client/epargnes')->with('message_succes', 'Taux d’épargne modifié avec succès.');
    }
}
