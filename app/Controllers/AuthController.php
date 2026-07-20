<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;

class AuthController extends BaseController
{


    public function showLogin()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $authModel = new AuthModel();
        $telephone = $this->request->getPost('telephone');

        $client = $authModel->verifierExistenceNum($telephone);

        if (!$client) {
            return redirect()->back()
                ->with('errors', 'Numéro de téléphone invalide ou inexistant')
                ->withInput();
        }

        session()->set([
            'client_id' => $client->id,
            'client_nom' => $client->nom,
            'telephone' => $client->telephone,
            'logged_in' => true,
        ]);

        return redirect()->to('/client/dashboard')
            ->with('success', 'Bienvenue ' . $client->nom . '!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')
            ->with('success', 'Vous avez été déconnecté');
    }
}
