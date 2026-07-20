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

    public function voirHistorique(int $idClient, array $filtre = []): array
    {
        $idComptes = array_column(
            $this->db->table('compte')->select('id')->where('id_client', $idClient)->get()->getResultArray(),
            'id'
        );

        if (empty($idComptes)) {
            return [];
        }

        $builder = $this->db->table('mouvements')
            ->select(
                'transactions.id AS id_transaction,
                 transactions.date,
                 transactions.montant,
                 transactions.frais_applique,
                 type_operation.id AS id_type_operation,
                 type_operation.libelle AS type,
                 mouvements.sens'
            )
            ->join('transactions', 'transactions.id = mouvements.id_transaction')
            ->join('type_operation', 'type_operation.id = transactions.id_type_operation')
            ->whereIn('mouvements.id_compte', $idComptes);

        if (! empty($filtre['date_debut'])) {
            $builder->where('transactions.date >=', $filtre['date_debut']);
        }

        if (! empty($filtre['date_fin'])) {
            $builder->where('transactions.date <=', $filtre['date_fin']);
        }

        if (! empty($filtre['montant_min'])) {
            $builder->where('transactions.montant >=', (int) $filtre['montant_min']);
        }

        if (! empty($filtre['montant_max'])) {
            $builder->where('transactions.montant <=', (int) $filtre['montant_max']);
        }

        if (! empty($filtre['id_type_operation'])) {
            $builder->where('transactions.id_type_operation', (int) $filtre['id_type_operation']);
        }

        $lignes = $builder->orderBy('transactions.date', 'DESC')->get()->getResultArray();

        foreach ($lignes as &$ligne) {
            $ligne['beneficiaire'] = $this->trouverBeneficiaire((int) $ligne['id_transaction'], $idComptes);
        }

        return $lignes;
    }

 
    private function trouverBeneficiaire(int $idTransaction, array $idComptesClient): ?string
    {
        $autre = $this->db->table('mouvements')
            ->select('clients.nom')
            ->join('compte', 'compte.id = mouvements.id_compte')
            ->join('clients', 'clients.id = compte.id_client')
            ->where('mouvements.id_transaction', $idTransaction)
            ->whereNotIn('mouvements.id_compte', $idComptesClient)
            ->get()
            ->getRowArray();

        return $autre['nom'] ?? null;
    }
}