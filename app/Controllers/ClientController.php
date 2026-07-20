<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ClientController extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    // ==========================================
    // AUTHENTIFICATION
    // ==========================================

    public function login()
    {
        if (session()->get('client_logged_in')) {
            return redirect()->to('/client/operations');
        }

        return view('Client/login');
    }

    public function postLogin()
    {
        $numero = trim($this->request->getPost('numero') ?? '');
        $clientModel = new ClientModel();

        $client = $clientModel->where('numero', $numero)->first();

        if ($client) {
            $sessionData = [
                'client_id'        => $client['id'],
                'client_numero'    => $client['numero'],
                'client_logged_in' => true
            ];
            session()->set($sessionData);

            return redirect()->to('/client/operations')->with('success', 'Connexion réussie.');
        }

        return redirect()->back()->with('error', 'Numéro de téléphone introuvable.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // ==========================================
    // ESPACE CLIENT : OPÉRATIONS
    // ==========================================

    public function operations()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $numeroClient = session()->get('client_numero');
        $db = \Config\Database::connect();

        // Récupération de l'historique des opérations
        $operations = $db->table('operations')
            ->groupStart()
                ->where('num_source', $numeroClient)
                ->orWhere('num_destination', $numeroClient)
            ->groupEnd()
            ->orderBy('date_operation', 'DESC')
            ->get()
            ->getResultArray();

        // Calcul du solde actuel du client
        $solde = $this->calculerSolde($numeroClient);

        $data = [
            'operations' => $operations,
            'solde'      => $solde // Transmis directement au layout
        ];

        return view('Client/operations', $data);
    }

    public function newOperation()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $numeroSource = session()->get('client_numero');

        $type           = (int) $this->request->getPost('type');
        $montant        = (int) $this->request->getPost('montant');
        $numDestination = trim($this->request->getPost('num_destination') ?? '');

        // Calcul des frais (par exemple 0 pour le moment)
        $frais = 0;

        $source      = ($type === 1) ? '' : $numeroSource;
        $destination = ($type === 2) ? '' : $numDestination;

        $data = [
            'type'            => $type,
            'montant'         => $montant,
            'frais'           => $frais,
            'num_source'      => $source,
            'num_destination' => $destination,
            'date_operation'  => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $db->table('operations')->insert($data);

        return redirect()->to('/client/operations')->with('success', 'Opération enregistrée avec succès.');
    }

    // ==========================================
    // MÉTHODES PRIVÉES DE CALCUL & AUTH
    // ==========================================

    /**
     * Calcule le solde courant d'un client à partir de la table 'operations'
     */
    private function calculerSolde(string $numero): int
    {
        $db = \Config\Database::connect();

        // Total des crédits reçus (Dépôts + Transferts reçus)
        $creditRow = $db->table('operations')
            ->selectSum('montant')
            ->where('num_destination', $numero)
            ->whereIn('type', [1, 3])
            ->get()
            ->getRow();
        $totalCredit = (int) ($creditRow->montant ?? 0);

        // Total des débits effectués (Retraits + Transferts envoyés + Frais)
        $debitRow = $db->table('operations')
            ->select('SUM(montant + frais) as total_debit')
            ->where('num_source', $numero)
            ->whereIn('type', [2, 3])
            ->get()
            ->getRow();
        $totalDebit = (int) ($debitRow->total_debit ?? 0);

        return $totalCredit - $totalDebit;
    }

    private function checkAuth()
    {
        if (!session()->get('client_logged_in')) {
            return redirect()->to('/login');
        }
        return null;
    }
}
