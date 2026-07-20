<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class OperateurController extends BaseController
{
    // GET /operateur/login
    public function login()
    {
        return view('Operateur/login');
    }

    // POST /operateur/login
    public function postLogin()
    {
        $email = $this->request->getPost('email');
        $mot_de_passe = $this->request->getPost('mot_de_passe');

        $admin_model = model('AdminModel');
        $admin = $admin_model->findByEmailAndMotDePasse($email, $mot_de_passe);
        if ($admin === false) {
            return redirect()->back()->withInput()->with('message_erreur', 'Identifiants invalides');
        }

        $session = session();
        $session->set('admin', $admin);
        return redirect()->to('/operateur/dashboard')->with('message_succes', 'Connexion réussie');
    }

    // GET /operateur/dashboard
    public function dashboard()
    {
        $operation_model = model('OperationModel');
        $nb_clients_par_jour = [12, 19, 3, 5, 2, 3, 7];
        $frais_retrait_par_jour = [12, 19, 3, 5, 2, 3, 7];
        $frais_transfert_par_jour = [3, 19, 3, 5, 5, 3, 7];
        // $frais_retrait_par_jour = $operation_model->findAllPourTypeParJour(2, date('Y-m-d'));
        // $frais_transfert_par_jour = $operation_model->findAllPourTypeParJour(3, date('Y-m-d'));
        return view('Operateur/dashboard', [
            'nb_clients_par_jour' => $nb_clients_par_jour,
            'frais_retrait_par_jour' => $frais_retrait_par_jour,
            'frais_transfert_par_jour' => $frais_transfert_par_jour,
        ]);
    }

    // GET /operateur/autres-operateurs
    public function autresOperateurs()
    {
        $operateur_model = model('OperateurModel');

        return view('Operateur/autresOperateurs', [
            'operateurs' => $operateur_model->findAll(),
        ]);
    }

    // POST /operateur/autres-operateurs/new
    public function postAutresOperateursNew()
    {
        $data = [
            'type_operation' => $this->request->getPost('type_operation'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ];
        $operateur_model = model('OperateurModel');

        if (!$operateur_model->validate($data)) {
            return redirect()->back()->with('message_erreur', implode(' ; ', $operateur_model->errors()));
        }

        $operateur_model->insert($data);

        return redirect()->back()->with('message_succes', 'Opérateur ajouté avec succès');
    }

    // POST /operateur/autres-operateurs/(:num)/update
    public function postAutresOperateursUpdate($id)
    {
        $data = [
            'id' => $id,
            'libelle' => $this->request->getPost('libelle'),
            'pct_commission' => $this->request->getPost('pct_commission'),
        ];
        $operateur_model = model('OperateurModel');

        if (!$operateur_model->validate($data)) {
            return redirect()->back()->with('message_erreur', implode(' ; ', $operateur_model->errors()));
        }

        $operateur_model->save($data);

        return redirect()->back()->with('message_succes', 'Opérateur modifié avec succès');
    }

    // GET /operateur/autres-operateurs/(:num)/delete
    public function autresOperateursDelete($id)
    {
        $operateur_model = model('OperateurModel');
        $prefixe_model = model('PrefixeModel');

        if ($operateur_model->where('id', $id)->first() === null) {
            return redirect()->back()->withInput()->with('message_erreur', 'Vous essayer de supprimer un opérateur qui n’existe pas');
        }

        $prefixe_model->where('id_operateur', $id)->delete();
        $operateur_model->delete($id);

        return redirect()->back()->with('message_succes', 'Opérateur supprimé avec succès');
    }

    // GET /operateur/prefixes
    public function prefixes()
    {
        $id_operateur = $this->request->getGet('operateur') ?? 0;
        $prefixe_model = model('PrefixeModel');
        $operateur_model = model('OperateurModel');

        $operateurs = array_merge([['id' => 0, 'libelle' => 'Nous']], $operateur_model->findAll());
        return view('Operateur/prefixes', [
            'id_operateur' => $id_operateur,
            'operateurs' => $operateurs,
            'prefixes' => $prefixe_model->findAllByOperateur($id_operateur),
        ]);
    }

    // POST /operateur/prefixes/new
    public function postPrefixesNew()
    {
        $data = [
            'id_operateur' => $this->request->getPost('id_operateur'),
            'valeur' => $this->request->getPost('valeur'),
        ];

        $prefixe_model = model('PrefixeModel');

        if (!$prefixe_model->validate($data)) {
            return redirect()->back()->with('message_erreur', $prefixe_model->errors()['valeur']);
        }
        if ($prefixe_model->where('valeur', $data['valeur'])->first() !== null) {
            return redirect()->back()->with('message_erreur', 'Ce préfixe existe déjà');
        }

        $prefixe_model->insert($data);

        return redirect()->back()->with('message_succes', 'Préfixe ajouté avec succès');
    }

    // POST /operateur/prefixes/(:num)/update
    public function postPrefixesUpdate($id)
    {
        $valeur = $this->request->getPost('valeur');
        $prefixe_model = model('PrefixeModel');

        $ancien_prefixe = $prefixe_model->where('id', $id)->first();

        if ($ancien_prefixe === null) {
            return redirect()->back()->withInput()->with('message_erreur', 'Vous essayer de modifier un préfixe qui n’existe pas');
        }
        if (!$prefixe_model->validate(['valeur' => $valeur])) {
            return redirect()->back()->withInput()->with('message_erreur', $prefixe_model->errors()['valeur']);
        }
        if ($ancien_prefixe !== $valeur && $prefixe_model->where('valeur', $valeur)->first() !== null) {
            return redirect()->back()->withInput()->with('message_erreur', 'Ce préfixe existe déjà');
        }

        $prefixe_model->save(['id' => $id, 'valeur' => $valeur]);

        return redirect()->back()->with('message_succes', 'Préfixe modifié avec succès');
    }

    // GET /operateur/prefixes/(:num)/delete
    public function prefixesDelete($id)
    {
        $prefixe_model = model('PrefixeModel');

        if ($prefixe_model->where('id', $id)->first() === null) {
            return redirect()->back()->withInput()->with('message_erreur', 'Vous essayer de supprimer un préfixe qui n’existe pas');
        }

        $prefixe_model->delete($id);

        return redirect()->back()->with('message_succes', 'Préfixe supprimé avec succès');
    }

    // GET /operateur/baremes
    public function baremes()
    {
        $id_type_operation = $this->request->getGet('type') ?? 2;
        $type_operation_model = model('TypeOperationModel');
        $bareme_frais_model = model('BaremeFraisModel');

        return view('Operateur/baremes', [
            'id_type_operation' => $id_type_operation,
            'types_operations' => $type_operation_model->findAllAvecFrais(),
            'baremes_frais' => $bareme_frais_model->findAllByTypeOperation($id_type_operation),
        ]);
    }

    // POST /operateur/baremes/new
    public function postBaremesNew()
    {
        $data = [
            'type_operation' => $this->request->getPost('type_operation'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ];
        $bareme_frais_model = model('BaremeFraisModel');

        if (!$bareme_frais_model->validate($data)) {
            return redirect()->back()->with('message_erreur', implode(' ; ', $bareme_frais_model->errors()));
        }
        if ($data['montant_min'] > $data['montant_max']) {
            return redirect()->back()->with('message_erreur', 'Le montant maximum doit être supérieur ou égal au montant minimum');
        }

        $bareme_frais_model->insert($data);

        return redirect()->back()->with('message_succes', 'Barème ajouté avec succès');
    }

    // POST /operateur/baremes/(:num)/update
    public function postBaremesUpdate($id)
    {
        $data = [
            'id' => $id,
            'type_operation' => $this->request->getPost('type_operation'),
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ];
        $bareme_frais_model = model('BaremeFraisModel');

        if (!$bareme_frais_model->validate($data)) {
            return redirect()->back()->with('message_erreur', implode(' ; ', $bareme_frais_model->errors()));
        }
        if ($data['montant_min'] > $data['montant_max']) {
            return redirect()->back()->with('message_erreur', 'Le montant maximum doit être supérieur ou égal au montant minimum');
        }

        $bareme_frais_model->save($data);

        return redirect()->back()->with('message_succes', 'Barème modifié avec succès');
    }

    // GET /operateur/baremes/(:num)/delete
    public function baremesDelete($id)
    {
        $bareme_frais_model = model('BaremeFraisModel');

        if ($bareme_frais_model->where('id', $id)->first() === null) {
            return redirect()->back()->withInput()->with('message_erreur', 'Vous essayer de supprimer un barème qui n’existe pas');
        }

        $bareme_frais_model->delete($id);

        return redirect()->back()->with('message_succes', 'Barème supprimé avec succès');
    }
}
