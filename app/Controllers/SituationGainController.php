<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TypeOperationModel;

class SituationGainController extends BaseController
{
    protected TransactionModel $transactionModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->transactionModel   = new TransactionModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $retrait   = $this->typeOperationModel->where('libelle', 'retrait')->first();
        $transfert = $this->typeOperationModel->where('libelle', 'transfert')->first();

        $situationRetrait   = $retrait ? $this->transactionModel->getSituationGain((int) $retrait['id']) : null;
        $situationTransfert = $transfert ? $this->transactionModel->getSituationGain((int) $transfert['id']) : null;
        $situationGlobale   = $this->transactionModel->getSituationGlobale();

        return view('operateur/situation-gain/index', [
            'situationRetrait'   => $situationRetrait,
            'situationTransfert' => $situationTransfert,
            'situationGlobale'   => $situationGlobale,
            'evolutionCombinee'  => $this->combinerEvolutions($situationRetrait, $situationTransfert),
        ]);
    }


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
