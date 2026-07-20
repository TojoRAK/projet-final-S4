<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CompteModel;
use App\Models\TransactionModel;

class CompteController extends BaseController
{
    public function historique()
    {
        if (!session()->get('client')) {
            return redirect()->to('/client/login')->with('errors', 'Vous devez être connecté');
        }

        $client = session()->get('client');
        $compteModel = new CompteModel();
        $transactionModel = new TransactionModel();

        $filtre = [
            'date_debut'   => $this->request->getGet('date_debut'),
            'date_fin'     => $this->request->getGet('date_fin'),
            'montant_min'  => $this->request->getGet('montant_min'),
            'montant_max'  => $this->request->getGet('montant_max'),
            'type'         => $this->request->getGet('type'),
        ];

        $historique = $compteModel->voirHistorique($client['id'], $filtre);

        return view('client/historique', [
            'historique' => $historique,
            'types'      => $transactionModel->getTypesOperation(),
            'filtre'     => $filtre,
        ]);
    }

    public function dashboard()
    {
        if (!session()->get('client')) {
            return redirect()->to('/client/login')->with('error', 'Vous devez être connecté');
        }

        $client = session()->get('client');
        $compteModel = new CompteModel();

        $compte = $compteModel->getCompteByClient($client['id']);

        if (!$compte) {
            return redirect()->to('/client/login')->with('error', 'Compte introuvable');
        }

        $solde = $compteModel->voirSolde($compte->id);
        $historique = $compteModel->getHistorique($compte->id);

        return view('client/dashboard', [
            'solde' => $solde,
            'historique' => $historique
        ]);
    }
}
