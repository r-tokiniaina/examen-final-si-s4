<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }

    // GET /login
    public function login()
    {
        return view('User/login');
    }

    // POST /login
    public function postLogin()
    {
        $email = $this->request->getPost('email');
        $mot_de_passe = $this->request->getPost('mot_de_passe');

        $user = $this->userModel->findByEmailAndMotDePasse($email, $mot_de_passe);
        if ($user === false) {
            return redirect()->back()->withInput()->with('message_erreur', 'Identifiants invalides');
        }

        $session = session();
        $session->set('user', $user);
        return redirect()->to("/{$user['role']}/dashboard")->with('message_succes', 'Connexion réussie');
    }

    // GET /logout
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login')->with('message_succes', 'Vous avez été déconnecté avec succès');
    }
}
