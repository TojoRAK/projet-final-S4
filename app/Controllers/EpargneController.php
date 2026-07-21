<?php

namespace App\Controllers;


use App\Models\AutreslibellesModel;
use App\Models\PromotionModel;
use App\Models\EpargneModel;

class EpargneController extends BaseController
{
    protected EpargneModel $epargneModel;

    public function __construct()
    {
        $this->$epargneModel = new EpargneModel();
    }

    public function index()
    {
        return view('operateur/epargne/liste', [
            'eprgne' => $this->epargneModel->listerEpargne(),
        ]);
    }


    public function store()
    {
        $idClient = (int) $this->request->getPost('id_client');
        $pourcentage = (float) $this->request->getPost('pourcentage');
        
        if (empty($libelle) || $pourcentage < 0 || $pourcentage > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Libelle ou pourcentage invalide.');
        }
        $valeurFraction = $pourcentage / 100;

        $existe = $this->epargneModel->where('id_client', $idClient)->first();

        if ($existe) {
            $this->epargneModel->updateEpargne($idClient, $pourcentage);

            return redirect()->to('epargne')->with('message', 'eparge mise à jour.');
        }

        if (!$this->epargneModel->addConfEpargne($idClient, $pourcentage)) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Impossible d'enregistrer la eparge.");
        }

        return redirect()->to('epargne')->with('message', 'eparge ajoutée.');
    }
}
