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
        return view('Operateur/prefixes');
    }

    // GET /operateur/baremes
    public function baremes()
    {
        return view('Operateur/baremes');
    }
}
