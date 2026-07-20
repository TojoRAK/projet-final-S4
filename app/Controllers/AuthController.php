<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperateurModel;

class AuthController extends BaseController
{
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('operateur/auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $operateur = $this->operateurModel->findByUsername($username);

        if (! $operateur || ! password_verify($password, $operateur['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Identifiants incorrects.');
        }

        session()->regenerate();
        session()->set([
            'operateur_id' => $operateur['id'],
            'username'     => $operateur['username'],
            'isLoggedIn'   => true,
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')->with('message', 'Déconnecté.');
    }
}
