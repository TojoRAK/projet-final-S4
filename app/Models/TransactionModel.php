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

    public function transfert(int $id_client, int $montant, array $tel_beneficiaires, bool $payer_frais = false)
    {
        $tel_beneficiaires = array_values(array_unique(array_filter(array_map('trim', $tel_beneficiaires))));

        if (empty($tel_beneficiaires)) {
            return 'Aucun bénéficiaire renseigné';
        }

        $authModel = new AuthModel();
        $compteModel = new CompteModel();
        $prefixeModel = new PrefixeModel();
        $commissionModel = new CommissionModel();

        $compteEmetteur = $compteModel->getCompteByClient($id_client);

        if (!$compteEmetteur) {
            return 'Compte introuvable';
        }

        $type = $this->db->table('type_operation')->where('libelle', 'transfert')->get()->getRow();
        $id_type_operation = $type->id;
        $type_retrait = $this->db->table('type_operation')->where('libelle', 'retrait')->get()->getRow();

        $nombreBeneficiaires = count($tel_beneficiaires);
        $montantParBeneficiaire = intdiv($montant, $nombreBeneficiaires);
        $reste = $montant % $nombreBeneficiaires;

        if ($montantParBeneficiaire < 1) {
            return 'Montant trop faible pour être réparti entre tous les bénéficiaires';
        }

        $envois = [];
        $coutTotal = 0;

        foreach ($tel_beneficiaires as $index => $tel) {
            $id_autre_operateur = $prefixeModel->getOperateurByNumero($tel);

            if ($nombreBeneficiaires > 1 && $id_autre_operateur !== null) {
                return "Envoi multiple impossible : le bénéficiaire {$tel} n'utilise pas notre opérateur";
            }

            $compteBeneficiaire = null;

            if ($id_autre_operateur === null) {
                $beneficiaire = $authModel->verifierExistenceNum($tel);

                if (!$beneficiaire) {
                    return "Numéro de bénéficiaire invalide ou inexistant : {$tel}";
                }

                $compteBeneficiaire = $compteModel->getCompteByClient($beneficiaire->id);

                if (!$compteBeneficiaire) {
                    return "Compte introuvable pour le bénéficiaire {$tel}";
                }

                if ($compteBeneficiaire->id === $compteEmetteur->id) {
                    return 'Vous ne pouvez pas transférer vers votre propre compte';
                }
            } elseif (!preg_match('/^(\+2613|03)[0-9]{8}$/', $tel)) {
                return "Numéro de bénéficiaire invalide : {$tel}";
            }

            $montantBase = $montantParBeneficiaire + ($index === 0 ? $reste : 0);
            $frais_transfert = $this->getFrais($montantBase, $id_type_operation);
            $frais_retrait_prepaye = 0;
            $commission = 0;

            if ($id_autre_operateur !== null) {
                $pourcentage = $commissionModel->getCommissionByOperateur($id_autre_operateur);
                $commission = (int) round($montantBase * $pourcentage / 100);
            }

            if ($payer_frais && $id_autre_operateur === null) {
                $frais_retrait_prepaye = $this->getFrais($montantBase, $type_retrait->id);
            }

            $montantTransaction = $montantBase + $frais_retrait_prepaye;
            $frais = $frais_transfert + $commission;

            $envois[] = [
                'compte'  => $compteBeneficiaire,
                'montant' => $montantTransaction,
                'frais'   => $frais,
            ];

            $coutTotal += $montantTransaction + $frais;
        }

        if ($compteEmetteur->solde < $coutTotal) {
            return "Solde insuffisant (frais inclus, total requis : {$coutTotal} Ar)";
        }

        $this->db->transStart();

        foreach ($envois as $envoi) {
            $id_transaction = $this->insert([
                'id_type_operation' => $id_type_operation,
                'montant'           => $envoi['montant'],
                'frais_applique'    => $envoi['frais'],
                'date'              => date('Y-m-d H:i:s'),
            ]);

            $this->db->table('mouvements')->insert([
                'id_transaction' => $id_transaction,
                'id_compte'      => $compteEmetteur->id,
                'sens'           => 'debit',
            ]);

            $compteModel->updateSolde($compteEmetteur->id, -($envoi['montant'] + $envoi['frais']));

            if ($envoi['compte'] !== null) {
                $this->db->table('mouvements')->insert([
                    'id_transaction' => $id_transaction,
                    'id_compte'      => $envoi['compte']->id,
                    'sens'           => 'credit',
                ]);

                $compteModel->updateSolde($envoi['compte']->id, $envoi['montant']);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus() ? true : 'Erreur technique, veuillez réessayer';
    }

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