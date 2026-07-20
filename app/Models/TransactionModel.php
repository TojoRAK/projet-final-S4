<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'transactions';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['id_type_operation', 'montant', 'frais_applique', 'date'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;


    public function getSituationGain(int $idTypeOperation): array
    {
        $totaux = $this->select(
            'COUNT(*) AS nb_transactions,
             COALESCE(SUM(montant), 0) AS total_montant,
             COALESCE(SUM(frais_applique), 0) AS total_gain'
        )
            ->where('id_type_operation', $idTypeOperation)
            ->first();

        $evolution = $this->select(
            "DATE(date) AS jour, COALESCE(SUM(frais_applique), 0) AS gain"
        )
            ->where('id_type_operation', $idTypeOperation)
            ->groupBy('jour')
            ->orderBy('jour', 'ASC')
            ->findAll();

        return [
            'nb_transactions' => (int) ($totaux['nb_transactions'] ?? 0),
            'total_montant'   => (int) ($totaux['total_montant'] ?? 0),
            'total_gain'      => (int) ($totaux['total_gain'] ?? 0),
            'evolution'       => $evolution,
        ];
    }


    public function getSituationGlobale(): array
    {
        $totaux = $this->select(
            'COUNT(*) AS nb_transactions,
             COALESCE(SUM(montant), 0) AS total_montant,
             COALESCE(SUM(frais_applique), 0) AS total_gain'
        )->first();

        return [
            'nb_transactions' => (int) ($totaux['nb_transactions'] ?? 0),
            'total_montant'   => (int) ($totaux['total_montant'] ?? 0),
            'total_gain'      => (int) ($totaux['total_gain'] ?? 0),
        ];
    }
}
