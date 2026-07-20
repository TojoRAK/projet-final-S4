<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;

class OperationController extends BaseController
{
    public function index()
    {
        if (!session()->get('client')) {
            return redirect()->to('/login')->with('errors', 'Vous devez être connecté');
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
            return redirect()->to('/login')->with('errors', 'Vous devez être connecté');
        }

        $montant = (int) $this->request->getPost('montant');
        $type_operation = (int) $this->request->getPost('type_operation');
        $tel_beneficiaire = $this->request->getPost('tel_beneficiaire');
        $transactionModel = new TransactionModel();
        $type = $transactionModel->getTypeOperationById($type_operation);

        if ($type && $type->libelle === 'transfert') {
            $success = $transactionModel->transfert($client['id'], $montant, $tel_beneficiaire);
        } else {
            $success = $transactionModel->ajouterMouvement($client['id'], $montant,  $type_operation);
        }

        if (!$success) {
            return redirect()->back()
                ->with('errors', 'Opération impossible')
                ->withInput();
        }

        return redirect()->to('/client/dashboard')
            ->with('success', 'Opération effectuée avec succès');
    }
}
