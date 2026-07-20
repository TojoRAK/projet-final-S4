<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompteModel;

class CompteController extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté');
        }

        $client = session()->get('client');
        $compteModel = new CompteModel();

        $compte = $compteModel->getCompteByClient($client['id']);

        if (!$compte) {
            return redirect()->to('/login')->with('error', 'Compte introuvable');
        }

        $solde = $compteModel->voirSolde($compte->id);
        $historique = $compteModel->getHistorique($compte->id);

        return view('client/dashboard', [
            'solde' => $solde,
            'historique' => $historique
        ]);
    }
}
