<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class OperationController extends BaseController
{
    public function index()
    {
        if (!session()->get('client')) {
            return redirect()->to('/client/login')->with('errors', 'Vous devez être connecté');
        }

        $transactionModel = new TransactionModel();

        return view('client/operation', [
            'types' => $transactionModel->getTypesOperation(),
        ]);
    }

    public function store()
    {
        $client = session()->get('client');

        if (!$client) {
            return redirect()->to('/client/login')->with('errors', 'Vous devez être connecté');
        }

        $montant = (int) $this->request->getPost('montant');
        $type_operation = (int) $this->request->getPost('type_operation');
        $tel_beneficiaire = $this->request->getPost('tel_beneficiaire');
        $payer_frais = (bool) $this->request->getPost('payer_frais');
        $transactionModel = new TransactionModel();
        $type = $transactionModel->getTypeOperationById($type_operation);

        if ($type && $type->libelle === 'transfert') {
            $result = $transactionModel->transfert($client['id'], $montant, $tel_beneficiaire, $payer_frais);
        } else {
            $result = $transactionModel->ajouterMouvement($client['id'], $montant, $type_operation);
        }

        if ($result !== true) {
            return redirect()->back()
                ->with('errors', $result)
                ->withInput();
        }

        return redirect()->to('/client/dashboard')
            ->with('success', 'Opération effectuée avec succès');
    }
}
