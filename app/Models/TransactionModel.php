<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_type_operation', 'montant', 'frais_applique', 'date'];

    protected $useTimestamps = false;

    public function getTypesOperation(): array
    {
        return $this->db->table('type_operation')->get()->getResultObject();
    }

    public function getTypeOperationById(int $id)
    {
        return $this->db->table('type_operation')->where('id', $id)->get()->getRow();
    }

    public function getFrais(int $montant, int $id_type_operation): int
    {
        $tranche = $this->db->table('tranches')
            ->where('id_type_operation', $id_type_operation)
            ->where('min <=', $montant)
            ->where('max >=', $montant)
            ->get()
            ->getRow();

        return $tranche ? (int) $tranche->frais : 0;
    }

    public function ajouterMouvement(int $id_client, int $montant, int $type_operation)
    {
        $compteModel = new CompteModel();
        $compte = $compteModel->getCompteByClient($id_client);

        if (!$compte) {
            return 'Compte introuvable';
        }

        $type = $this->db->table('type_operation')->where('id', $type_operation)->get()->getRow();
        $sens = ($type && $type->libelle === 'retrait') ? 'debit' : 'credit';
        $frais = $this->getFrais($montant, $type_operation);

        if ($sens === 'debit' && $compte->solde < ($montant + $frais)) {
            return "Solde insuffisant (frais de {$frais} Ar inclus)";
        }

        $this->db->transStart();

        $id_transaction = $this->insert([
            'id_type_operation' => $type_operation,
            'montant'           => $montant,
            'frais_applique'    => $frais,
            'date'              => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('mouvements')->insert([
            'id_transaction' => $id_transaction,
            'id_compte'      => $compte->id,
            'sens'           => $sens,
        ]);

        $variation = $sens === 'credit' ? $montant : -($montant + $frais);
        $compteModel->updateSolde($compte->id, $variation);

        $this->db->transComplete();

        return $this->db->transStatus() ? true : 'Erreur technique, veuillez réessayer';
    }

    public function transfert(int $id_client, int $montant, string $tel_beneficiaire, bool $payer_frais = false)
    {
        $authModel = new AuthModel();
        $compteModel = new CompteModel();

        $beneficiaire = $authModel->verifierExistenceNum($tel_beneficiaire);

        if (!$beneficiaire) {
            return 'Numéro de bénéficiaire invalide ou inexistant';
        }

        $compteEmetteur = $compteModel->getCompteByClient($id_client);
        $compteBeneficiaire = $compteModel->getCompteByClient($beneficiaire->id);

        if (!$compteEmetteur || !$compteBeneficiaire) {
            return 'Compte introuvable';
        }

        if ($compteEmetteur->id === $compteBeneficiaire->id) {
            return 'Vous ne pouvez pas transférer vers votre propre compte';
        }

        $type = $this->db->table('type_operation')->where('libelle', 'transfert')->get()->getRow();
        $id_type_operation = $type->id;

        $frais_transfert = $this->getFrais($montant, $id_type_operation);
        $frais_retrait_prepaye = 0;

        if ($payer_frais) {
            $prefixeModel = new PrefixeModel();

            if ($prefixeModel->estNotreOperateur($tel_beneficiaire)) {
                $type_retrait = $this->db->table('type_operation')->where('libelle', 'retrait')->get()->getRow();
                $frais_retrait_prepaye = $this->getFrais($montant, $type_retrait->id);
            }
        }

        $frais = $frais_transfert + $frais_retrait_prepaye;

        if ($compteEmetteur->solde < ($montant + $frais)) {
            return "Solde insuffisant (frais de {$frais} Ar inclus)";
        }

        $this->db->transStart();

        $id_transaction = $this->insert([
            'id_type_operation' => $id_type_operation,
            'montant'           => $montant,
            'frais_applique'    => $frais,
            'date'              => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('mouvements')->insert([
            'id_transaction' => $id_transaction,
            'id_compte'      => $compteEmetteur->id,
            'sens'           => 'debit',
        ]);

        $this->db->table('mouvements')->insert([
            'id_transaction' => $id_transaction,
            'id_compte'      => $compteBeneficiaire->id,
            'sens'           => 'credit',
        ]);

        $compteModel->updateSolde($compteEmetteur->id, -($montant + $frais));
        $compteModel->updateSolde($compteBeneficiaire->id, $montant);

        $this->db->transComplete();

        return $this->db->transStatus() ? true : 'Erreur technique, veuillez réessayer';
    }

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
