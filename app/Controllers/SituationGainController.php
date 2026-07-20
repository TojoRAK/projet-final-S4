<?php

namespace App\Controllers;

use App\Models\AutresOperateurModel;
use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

class SituationGainController extends BaseController
{
    protected TransactionModel $transactionModel;
    protected TypeOperationModel $typeOperationModel;
    protected AutresOperateurModel $autresOperateurModel;

    public function __construct()
    {
        $this->transactionModel     = new TransactionModel();
        $this->typeOperationModel   = new TypeOperationModel();
        $this->autresOperateurModel = new AutresOperateurModel();
    }

    public function index()
    {
        $retrait    = $this->typeOperationModel->where('libelle', 'retrait')->first();
        $transfert  = $this->typeOperationModel->where('libelle', 'transfert')->first();
        $operateurs = $this->autresOperateurModel->findAllOperateurs();

        $situationRetrait   = $retrait ? $this->transactionModel->getSituationGain((int) $retrait['id']) : null;
        $situationTransfert = $transfert ? $this->transactionModel->getSituationGain((int) $transfert['id']) : null;

        $autresRetrait   = $retrait ? $this->transactionModel->getSituationGainAutresOperateurs((int) $retrait['id']) : null;
        $autresTransfert = $transfert ? $this->transactionModel->getSituationGainAutresOperateurs((int) $transfert['id']) : null;

        $parOperateur = [];
        foreach ($operateurs as $operateur) {
            $idOp = (int) $operateur['id'];
            $parOperateur[] = [
                'nom_operateur' => $operateur['nom_operateur'],
                'retrait'       => $retrait ? $this->transactionModel->getSituationGainByOperateur((int) $retrait['id'], $idOp) : null,
                'transfert'     => $transfert ? $this->transactionModel->getSituationGainByOperateur((int) $transfert['id'], $idOp) : null,
            ];
        }

        $situationGlobale = $this->transactionModel->getSituationGlobale();
        $situationMontant = $this->transactionModel->getTotalMontantGlobal();

        return view('operateur/situation-gain/index', [
            'situationRetrait'   => $situationRetrait,
            'situationTransfert' => $situationTransfert,
            'autresRetrait'      => $autresRetrait,
            'autresTransfert'    => $autresTransfert,
            'parOperateur'       => $parOperateur,
            'situationGlobale'   => $situationGlobale,
            'situationMontant'   => $situationMontant,
            'evolutionCombinee'  => $this->combinerEvolutions($situationRetrait, $situationTransfert),
        ]);
    }

    /**
     * Fusionne les deux évolutions journalières par date, pour le graphe superposé.
     */
    private function combinerEvolutions(?array $situationRetrait, ?array $situationTransfert): array
    {
        $parJour = [];

        foreach ($situationRetrait['evolution'] ?? [] as $row) {
            $parJour[$row['jour']]['retrait'] = (int) $row['gain'];
        }

        foreach ($situationTransfert['evolution'] ?? [] as $row) {
            $parJour[$row['jour']]['transfert'] = (int) $row['gain'];
        }

        ksort($parJour);

        $resultat = [];

        foreach ($parJour as $jour => $valeurs) {
            $resultat[] = [
                'jour'      => $jour,
                'retrait'   => $valeurs['retrait'] ?? 0,
                'transfert' => $valeurs['transfert'] ?? 0,
            ];
        }

        return $resultat;
    }
}