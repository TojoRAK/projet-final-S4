<?php

namespace App\Controllers;

use App\Models\TypeOperationModel;

class TypeOperationController extends BaseController
{
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        return view('operateur/type-operation/liste', [
            'types' => $this->typeOperationModel->findAllTypes(),
        ]);
    }

    public function store()
    {
        $libelle = (string) $this->request->getPost('libelle');

        if (! $this->typeOperationModel->addType($libelle)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Libellé invalide ou déjà existant.');
        }

        return redirect()->to('type-operations')->with('message', 'Type ajouté.');
    }
}