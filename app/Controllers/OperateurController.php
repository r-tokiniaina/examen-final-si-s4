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
        return view('Operateur/dashboard');
    }

    // GET /operateur/prefixes
    public function prefixes()
    {
        $prefixe_model = model('PrefixeModel');
        $prefixes = $prefixe_model->findAll();
        return view('Operateur/prefixes', ['prefixes' => $prefixes]);
    }

    // POST /operateur/prefixes/new
    public function postPrefixesNew()
    {
        $valeur = $this->request->getPost('valeur');
        $prefixe_model = model('PrefixeModel');

        if (!$prefixe_model->validate(['valeur' => $valeur])) {
            return redirect()->back()->with('message_erreur', $prefixe_model->errors()['valeur']);
        }
        if ($prefixe_model->where('valeur', $valeur)->first() !== null) {
            return redirect()->back()->with('message_erreur', 'Ce préfixe existe déjà');
        }

        $prefixe_model->insert(['valeur' => $valeur]);

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
        return view('Operateur/baremes');
    }
}
