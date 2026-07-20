<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
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

    public function ajouterMouvement(int $id_client, int $montant,  int $type_operation): bool
    {
        $compteModel = new CompteModel();
        $compte = $compteModel->getCompteByClient($id_client);

        if (!$compte) {
            return false;
        }

        $type = $this->db->table('type_operation')->where('id', $type_operation)->get()->getRow();
        $sens = ($type && $type->libelle === 'retrait') ? 'debit' : 'credit';
        $frais = $this->getFrais($montant, $type_operation);

        if ($sens === 'debit' && $compte->solde < ($montant + $frais)) {
            return false;
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

        return $this->db->transStatus();
    }

    public function transfert(int $id_client, int $montant, string $tel_beneficiaire): bool
    {
        $authModel = new AuthModel();
        $compteModel = new CompteModel();

        $beneficiaire = $authModel->verifierExistenceNum($tel_beneficiaire);

        if (!$beneficiaire) {
            return false;
        }

        $compteEmetteur = $compteModel->getCompteByClient($id_client);
        $compteBeneficiaire = $compteModel->getCompteByClient($beneficiaire->id);

        if (!$compteEmetteur || !$compteBeneficiaire || $compteEmetteur->id === $compteBeneficiaire->id) {
            return false;
        }

        $type = $this->db->table('type_operation')->where('libelle', 'transfert')->get()->getRow();
        $id_type_operation = $type->id;
        $frais = $this->getFrais($montant,$id_type_operation);

        if ($compteEmetteur->solde < ($montant + $frais)) {
            return false;
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

        return $this->db->transStatus();
    }
}
