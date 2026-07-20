<?php

namespace App\Controllers;

use App\Models\PrefixeModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
    }

    public function index()
    {
        return view('operateur/prefixe/liste', [
            'prefixes' => $this->prefixeModel->findAllPrefix(),
        ]);
    }

    public function store()
    {
        $prefix = trim((string) $this->request->getPost('prefix'));

        if (! $this->prefixeModel->addPrefix($prefix)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Format de préfixe invalide (attendu : 03X, ex. 033).');
        }

        return redirect()->to('prefixes')->with('message', 'Préfixe ajouté.');
    }
}