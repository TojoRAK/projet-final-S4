<?php

namespace App\Controllers;

use App\Models\TrancheModel;
use App\Models\TypeOperationModel;

class TrancheController extends BaseController
{
    protected TrancheModel $trancheModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->trancheModel       = new TrancheModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index(int $idTypeOperation)
    {
        $type = $this->typeOperationModel->find($idTypeOperation);

        if (! $type) {
            return redirect()->to('type-operations')->with('error', 'Type introuvable.');
        }

        return view('operateur/type-operation/tranches', [
            'type'     => $type,
            'tranches' => $this->trancheModel->findByType($idTypeOperation),
        ]);
    }

    public function store(int $idTypeOperation)
    {
        $min   = (int) $this->request->getPost('min');
        $max   = (int) $this->request->getPost('max');
        $frais = (int) $this->request->getPost('frais');

        $result = $this->trancheModel->addTranche($min, $max, $frais, $idTypeOperation);

        if (is_string($result)) {
            return redirect()->back()->withInput()->with('error', $result);
        }

        return redirect()->to("type-operations/{$idTypeOperation}/tranches")->with('message', 'Tranche ajoutée.');
    }

    public function edit(int $id)
    {
        $tranche = $this->trancheModel->find($id);

        if (! $tranche) {
            return redirect()->to('type-operations')->with('error', 'Tranche introuvable.');
        }

        return view('operateur/type-operation/tranche-edit', ['tranche' => $tranche]);
    }

    public function update(int $id)
    {
        $min   = (int) $this->request->getPost('min');
        $max   = (int) $this->request->getPost('max');
        $frais = (int) $this->request->getPost('frais');

        $tranche         = $this->trancheModel->find($id);
        $idTypeOperation = $tranche['id_type_operation'] ?? null;

        $result = $this->trancheModel->updateTranche($id, $min, $max, $frais);

        if (is_string($result)) {
            return redirect()->back()->withInput()->with('error', $result);
        }

        return redirect()->to("type-operations/{$idTypeOperation}/tranches")->with('message', 'Tranche modifiée.');
    }

    public function delete(int $id)
    {
        $tranche         = $this->trancheModel->find($id);
        $idTypeOperation = $tranche['id_type_operation'] ?? null;

        $this->trancheModel->deleteTranche($id);

        return redirect()->to("type-operations/{$idTypeOperation}/tranches")->with('message', 'Tranche supprimée.');
    }
}