<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

class HistoriqueClientController extends BaseController
{
    protected TransactionModel $transactionModel;
    protected ClientModel $clientModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->transactionModel   = new TransactionModel();
        $this->clientModel        = new ClientModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index(int $idClient)
    {
        $client = $this->clientModel->find($idClient);

        if (! $client) {
            return redirect()->to('situation-client')->with('error', 'Client introuvable.');
        }

        $filtre = [
            'date_debut'        => $this->request->getGet('date_debut'),
            'date_fin'          => $this->request->getGet('date_fin'),
            'montant_min'       => $this->request->getGet('montant_min'),
            'montant_max'       => $this->request->getGet('montant_max'),
            'id_type_operation' => $this->request->getGet('id_type_operation'),
        ];

        return view('operateur/historique-client/index', [
            'client'     => $client,
            'filtre'     => $filtre,
            'types'      => $this->typeOperationModel->findAllTypes(),
            'historique' => $this->transactionModel->voirHistorique($idClient, $filtre),
        ]);
    }
}