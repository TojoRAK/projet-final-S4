<?php

namespace App\Controllers;

use App\Models\AutresOperateurModel;
use App\Models\PrefixeModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;
    protected AutresOperateurModel $autresOperateurModel;

    public function __construct()
    {
        $this->prefixeModel         = new PrefixeModel();
        $this->autresOperateurModel = new AutresOperateurModel();
    }

    public function index()
    {
        return view('operateur/prefixe/liste', [
            'prefixes'  => $this->prefixeModel->findAllPrefix(),
            'operateurs'=> $this->autresOperateurModel->findAllOperateurs(),
        ]);
    }

    public function store()
    {
        $prefix       = trim((string) $this->request->getPost('prefix'));
        $idOperateur  = $this->request->getPost('id_operateur');
        $idOperateur  = ($idOperateur === '' || $idOperateur === null) ? null : (int) $idOperateur;

        if (! $this->prefixeModel->addPrefix($prefix, $idOperateur)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Format de préfixe invalide (attendu : 03X, ex. 033).');
        }

        return redirect()->to('prefixes')->with('message', 'Préfixe ajouté.');
    }
}