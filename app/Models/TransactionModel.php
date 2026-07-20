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
        return $this->situationAvecEvolution($idTypeOperation, null, false);
    }


    public function getSituationGainByOperateur(int $idTypeOperation, int $idOperateur): array
    {
        return $this->situationAvecEvolution($idTypeOperation, $idOperateur, false);
    }


    public function getSituationGainAutresOperateurs(int $idTypeOperation): array
    {
        return $this->situationAvecEvolution($idTypeOperation, null, true);
    }


    public function getSituationGlobale(): array
    {
        return [
            'nous'   => $this->situationParOperateur(null, null, false),
            'autres' => $this->situationParOperateur(null, null, true),
        ];
    }


    public function getTotalMontantByOperateur(int $idOperateur): array
    {
        $totaux = $this->situationParOperateur(null, $idOperateur, false);
        $pourcentage = $this->recupererCommission($idOperateur);

        return [
            'total_montant'    => $totaux['total_montant'],
            'pourcentage'      => $pourcentage,
            // $pourcentage est une fraction (0,05 = 5%), pas un nombre entier de %
            'montant_a_payer'  => (int) round($totaux['total_montant'] * $pourcentage),
        ];
    }


    public function getTotalMontantGlobal(): array
    {
        $operateurs   = $this->db->table('autres_operateurs')->get()->getResultArray();
        $totalAPayer  = 0;
        $details      = [];

        foreach ($operateurs as $operateur) {
            $situation = $this->getTotalMontantByOperateur((int) $operateur['id']);
            $details[] = array_merge(['nom_operateur' => $operateur['nom_operateur']], $situation);
            $totalAPayer += $situation['montant_a_payer'];
        }

        return [
            'total_a_payer' => $totalAPayer,
            'details'       => $details,
        ];
    }


    private function situationAvecEvolution(int $idTypeOperation, ?int $idOperateur, bool $autresOperateurs): array
    {
        $totaux = $this->situationParOperateur($idTypeOperation, $idOperateur, $autresOperateurs);

        $builder = $this->requeteBase()
            ->select("DATE(transactions.date) AS jour, COALESCE(SUM(transactions.frais_applique), 0) AS gain")
            ->where('transactions.id_type_operation', $idTypeOperation);

        $builder = $this->appliquerFiltreOperateur($builder, $idOperateur, $autresOperateurs);

        $totaux['evolution'] = $builder->groupBy('jour')->orderBy('jour', 'ASC')->get()->getResultArray();

        return $totaux;
    }


    private function situationParOperateur(?int $idTypeOperation, ?int $idOperateur, bool $autresOperateurs): array
    {
        $builder = $this->requeteBase()->select(
            'COUNT(*) AS nb_transactions,
             COALESCE(SUM(transactions.montant), 0) AS total_montant,
             COALESCE(SUM(transactions.frais_applique), 0) AS total_gain'
        );

        if ($idTypeOperation !== null) {
            $builder->where('transactions.id_type_operation', $idTypeOperation);
        }

        $builder = $this->appliquerFiltreOperateur($builder, $idOperateur, $autresOperateurs);

        $ligne = $builder->get()->getRowArray();

        return [
            'nb_transactions' => (int) ($ligne['nb_transactions'] ?? 0),
            'total_montant'   => (int) ($ligne['total_montant'] ?? 0),
            'total_gain'      => (int) ($ligne['total_gain'] ?? 0),
        ];
    }


    private function requeteBase()
    {
        $sousRequete = $this->db->table('mouvements')
            ->select('mouvements.id_transaction, MAX(conf_prefix.id_operateur) AS id_operateur_attribue')
            ->join('compte', 'compte.id = mouvements.id_compte')
            ->join('clients', 'clients.id = compte.id_client')
            ->join('conf_prefix', 'conf_prefix.prefix = SUBSTR(clients.telephone, 1, 3)', 'left')
            ->groupBy('mouvements.id_transaction')
            ->getCompiledSelect(false);

        return $this->db->table('transactions')
            ->join("({$sousRequete}) AS attribution", 'attribution.id_transaction = transactions.id');
    }


    private function appliquerFiltreOperateur($builder, ?int $idOperateur, bool $autresOperateurs)
    {
        if ($autresOperateurs) {
            return $builder->where('attribution.id_operateur_attribue IS NOT NULL', null, false);
        }

        if ($idOperateur !== null) {
            return $builder->where('attribution.id_operateur_attribue', $idOperateur);
        }

        return $builder->where('attribution.id_operateur_attribue', null);
    }


    private function recupererCommission(int $idOperateur): float
    {
        $ligne = $this->db->table('conf_commission')
            ->select('pourcentage')
            ->where('id_operateur', $idOperateur)
            ->get()
            ->getRowArray();

        return (float) ($ligne['pourcentage'] ?? 0);
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