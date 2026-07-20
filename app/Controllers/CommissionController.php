<?php

namespace App\Controllers;

use App\Models\CommissionModel;
use App\Models\AutresOperateursModel;

class CommissionController extends BaseController
{
    protected CommissionModel $commissionModel;
    protected AutresOperateursModel $autresOperateursModel;

    public function __construct()
    {
        $this->commissionModel = new CommissionModel();
        $this->autresOperateursModel = new AutresOperateursModel();
    }

    public function index()
    {
        return view('operateur/commission/liste', [
            'commissions' => $this->commissionModel->listerCommissions(),
            'operateurs'  => $this->autresOperateursModel->findAllOperateurs(),
        ]);
    }

    public function store()
    {
        $operateur = (int) $this->request->getPost('operateur');
        $valeurPourcentage = (float) $this->request->getPost('valeur');

        if ($operateur <= 0 || $valeurPourcentage < 0 || $valeurPourcentage > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Opérateur ou pourcentage invalide.');
        }

        // pourcentage stocké en fraction (0,05 = 5%), la saisie utilisateur est en points de %
        $valeurFraction = $valeurPourcentage / 100;

        $existe = $this->commissionModel->where('id_operateur', $operateur)->first();

        if ($existe) {
            $this->commissionModel->updateCommission($valeurFraction, $operateur);

            return redirect()->to('commissions')->with('message', 'Commission mise à jour.');
        }

        if (!$this->commissionModel->addConfCommission($valeurFraction, $operateur)) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Impossible d'enregistrer la commission.");
        }

        return redirect()->to('commissions')->with('message', 'Commission ajoutée.');
    }
}
