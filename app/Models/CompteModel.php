<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteModel extends Model
{
    protected $table = 'compte';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_client', 'solde'];

    protected $useTimestamps = false;


    public function voirSolde(int $id_compte): ?float
    {
        $compte = $this->find($id_compte);
        return $compte ? (float) $compte->solde : null;
    }

    public function getHistorique(int $id_compte): array
    {
        $db = \Config\Database::connect();

        return $db->table('mouvements')
            ->select('mouvements.id, mouvements.sens, transactions.date, transactions.montant, transactions.frais_applique, type_operation.libelle')
            ->join('transactions', 'mouvements.id_transaction = transactions.id')
            ->join('type_operation', 'transactions.id_type_operation = type_operation.id')
            ->where('mouvements.id_compte', $id_compte)
            ->orderBy('transactions.date', 'DESC')
            ->limit(10)
            ->get()
            ->getResultObject();
    }

    public function voirHistorique(int $id_client, array $filtre = []): array
    {
        $compte = $this->getCompteByClient($id_client);

        if (!$compte) {
            return [];
        }
        $builder = $this->db->table('mouvements')
            ->select("mouvements.id, mouvements.sens, transactions.date, transactions.montant, transactions.frais_applique, type_operation.libelle, clients_dest.telephone AS beneficiaire", false)
            ->join('transactions', 'mouvements.id_transaction = transactions.id')
            ->join('type_operation', 'transactions.id_type_operation = type_operation.id')
            ->join('mouvements AS m2', 'm2.id_transaction = mouvements.id_transaction AND m2.id_compte != mouvements.id_compte', 'left')
            ->join('compte AS c2', 'c2.id = m2.id_compte', 'left')
            ->join('clients AS clients_dest', 'clients_dest.id = c2.id_client', 'left')
            ->where('mouvements.id_compte', $compte->id);

        if (!empty($filtre['date_debut'])) {
            $builder->where('transactions.date >=', $filtre['date_debut']);
        }

        if (!empty($filtre['date_fin'])) {
            $builder->where('transactions.date <=', $filtre['date_fin']);
        }

        if (!empty($filtre['montant_min'])) {
            $builder->where('transactions.montant >=', $filtre['montant_min']);
        }

        if (!empty($filtre['montant_max'])) {
            $builder->where('transactions.montant <=', $filtre['montant_max']);
        }

        if (!empty($filtre['type'])) {
            $builder->where('type_operation.id', $filtre['type']);
        }

        return $builder->orderBy('transactions.date', 'DESC')->get()->getResultObject();
    }

    public function updateSolde(int $id_compte, float $montant): bool
    {
        $compte = $this->find($id_compte);

        if (!$compte) {
            return false;
        }

        $newSolde = (float) $compte->solde + $montant;

        return $this->update($id_compte, ['solde' => $newSolde]);
    }

    public function getCompteByClient(int $id_client)
    {
        return $this->where('id_client', $id_client)->first();
    }
}
