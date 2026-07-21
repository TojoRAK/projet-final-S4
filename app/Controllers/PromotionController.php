<?php

namespace App\Controllers;

use App\Models\CommissionModel;
use App\Models\AutreslibellesModel;
use App\Models\PromotionModel;

class PromotionController extends BaseController
{
    protected PromotionModel $promotionModel;

    public function __construct()
    {
        $this->promotionModel = new PromotionModel();
    }

    public function index()
    {
        return view('operateur/promotion/liste', [
            'promotions' => $this->promotionModel->listerPromotions(),
        ]);
    }

    public function store()
    {
        $libelle = (string) $this->request->getPost('libelle');
        $valeurPourcentage = (float) $this->request->getPost('valeur');
        
        if (empty($libelle) || $valeurPourcentage < 0 || $valeurPourcentage > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Libelle ou pourcentage invalide.');
        }
        $valeurFraction = $valeurPourcentage / 100;

        $existe = $this->promotionModel->where('libelle', $libelle)->first();

        if ($existe) {
            $this->promotionModel->updatePromotion($valeurFraction, $libelle);

            return redirect()->to('promotions')->with('message', 'Promotion mise à jour.');
        }

        if (!$this->promotionModel->addConfPromotion($valeurFraction, $libelle)) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Impossible d'enregistrer la promotion.");
        }

        return redirect()->to('promotions')->with('message', 'Promotion ajoutée.');
    }
}
