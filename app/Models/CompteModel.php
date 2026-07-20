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

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';


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
