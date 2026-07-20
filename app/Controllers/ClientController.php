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

            return redirect()->to('/client/operations')->with('message_succes', 'Connexion réussie.');
        }

        return redirect()->back()->with('message_erreur', 'Numéro de téléphone introuvable.');
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

        $solde_model = model('SoldeModel');
        $operation_model = model('OperationModel');
        $prefixe_model = model('PrefixeModel');
        $bareme_frais_model = model('BaremeFraisModel');

        $numeroClient = session()->get('client_numero');

        $prefixes = $prefixe_model->join('operateurs', 'id_operateur = operateurs.id')
                                   ->select('valeur, pct_commission')
                                   ->findAll();
        $pcts_commissions = array_column($prefixes, 'pct_commission', 'valeur');

        $operations = $operation_model->findAllPourNumero($numeroClient);

        $solde = $solde_model->find($numeroClient)['montant'];

        $data = [
            'operations'       => $operations,
            'baremes'          => $bareme_frais_model->findAll(),
            'pcts_commissions' => $pcts_commissions,
            'solde'            => $solde
        ];

        return view('Client/operations', $data);
    }

    public function newOperation()
    {
        if ($redirect = $this->checkAuth()) {
            return $redirect;
        }

        $bareme_frais_model = model('BaremeFraisModel');
        $operation_model = model('OperationModel');
        $prefixe_model = model('PrefixeModel');

        $prefixes = $prefixe_model->join('operateurs', 'id_operateur = operateurs.id')
                                   ->select('valeur, pct_commission')
                                   ->findAll();
        $pcts_commissions = array_column($prefixes, 'pct_commission', 'valeur');

        $numeroClient = session()->get('client_numero');

        $type           = (int) $this->request->getPost('type');
        $montant        = (int) $this->request->getPost('montant');
        $numDestination = trim($this->request->getPost('num_destination') ?? '');
        $inclureFrais   = (bool) $this->request->getPost('inclure_frais');


        $source      = '';
        $destination = '';

        if ($type == 1) {
            $destination = $numeroClient;
        }
        else if ($type == 2) {
            $source = $numeroClient;
        }

        if ($type == 3) {
            $source = $numeroClient;
            $destinations = explode(',', $numDestination);

            // 1. Nettoyer complètement la chaîne en enlevant espaces, virgules et points-virgules
            $chaineBrute = preg_replace('/[,;\s]+/', '', $numDestination);
            $destinations = [];

            // 2. Extraire la liste des numéros selon le même format que le JS
            if (strlen($chaineBrute) <= 10) {
                if (!empty($chaineBrute)) {
                    $destinations[] = $chaineBrute;
                }
            } else {
                // Découpe par blocs de 9 ou 10 chiffres (ex: commençant par 03 ou 3)
                preg_match_all('/(03\d{7}|3\d{7})/', $chaineBrute, $matches);
                $destinations = $matches[0] ?? [];
            }

            // Déterminer s'ils appartiennent tous au même opérateur avec commission
            $memeOperateur = false;
            $pourcentageCommission = 0;

            if (!empty($destinations)) {
                $premierPrefixe = null;
                $tousMemePrefixeValide = true;

                foreach ($destinations as $d) {
                    // Récupère le préfixe à 3 chiffres (ajoute le '0' si l'utilisateur a écrit '3X...')
                    $prefixe = str_starts_with($d, '0') ? substr($d, 0, 3) : '0' . substr($d, 0, 2);

                    if (!isset($pcts_commissions[$prefixe])) {
                        $tousMemePrefixeValide = false;
                        break;
                    }

                    if ($premierPrefixe === null) {
                        $premierPrefixe = $prefixe;
                    } elseif ($prefixe !== $premierPrefixe) {
                        $tousMemePrefixeValide = false;
                        break;
                    }
                }

                if ($tousMemePrefixeValide && $premierPrefixe !== null) {
                    $memeOperateur = true;
                    $pourcentageCommission = (float)$pcts_commissions[$premierPrefixe];
                }
            }

            // 3. Calcul des frais finaux par transaction
            if ($memeOperateur) {
                // Si même opérateur : frais fixes annulés, place à la commission variable
                $fraisUnitaire = $montant * ($pourcentageCommission / 100);
            } else {
                // Sinon : barème classique récupéré depuis la base de données
                $fraisUnitaire = (int) $bareme_frais_model->findFraisByTypeAndMontant($type, $montant);
            }

            // 4. Insertion des opérations individuelles
            $date = date('Y-m-d H:i:s');
            foreach ($destinations as $d) {
                // Si l'option 'inclure_frais' est cochée, on ajuste le montant net envoyé
                $montantFinal = $montant;
                if ($inclureFrais && $memeOperateur) {
                    $montantFinal = $montant - $fraisUnitaire;
                }

                $data = [
                    'type'            => $type,
                    'montant'         => $montantFinal,
                    'frais'           => $fraisUnitaire,
                    'num_source'      => $source,
                    'num_destination' => $d,
                    'date_operation'  => $date
                ];
                $operation_model->insert($data);
            }
        } else {
            // Logique pour Dépôt (1) et Retrait (2)
            $frais = (int) $bareme_frais_model->findFraisByTypeAndMontant($type, $montant);
            $montantFinal = $montant;

            // Retrait avec option inclure_frais cochée
            if ($type == 2 && $inclureFrais) {
                $montantFinal = $montant - $frais;
            }
            $data = [
                'type'            => $type,
                'montant'         => $montantFinal,
                'frais'           => $frais,
                'num_source'      => $source,
                'num_destination' => $destination,
                'date_operation'  => date('Y-m-d H:i:s')
            ];
            $operation_model->insert($data);
        }

        return redirect()->to('/client/operations')->with('message_succes', 'Opération enregistrée avec succès.');
    }


    // ==========================================
    // MÉTHODES PRIVÉES DE CALCUL & AUTH
    // ==========================================

    private function checkAuth()
    {
        if (!session()->get('client_logged_in')) {
            return redirect()->to('/login');
        }
        return null;
    }
}
