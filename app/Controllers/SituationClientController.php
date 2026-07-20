<?php

namespace App\Controllers;

use App\Models\ClientModel;

class SituationClientController extends BaseController
{
    protected ClientModel $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    public function index()
    {
        return view('operateur/situation-client/index', [
            'clients' => $this->clientModel->getSituationClients(),
        ]);
    }
}