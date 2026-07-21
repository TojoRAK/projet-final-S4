<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table = 'conf_epargne';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_client', 'pourcentage'];
    protected $returnType = 'array';
    protected $useTimestamps = false;


    public function getEpargne($id)
    {
        return $this->find($id);
    }

    public function addConfEpargne(float $pourcentage, int $id_client): bool
    {
        if ($this->where('id_client', $id_client)->first()) {
            return false;
        }

        return (bool) $this->insert([
            'pourcentage' => $pourcentage,
            'id_client' => $id_client,
        ]);
    }

    public function updateEpargne(int $id_client, float $pourcentage): bool
    {


        $client = $this->find($id_client);

        if (!$client) {
            return false;
        }

        // $newepargne = (float) $montant * $pourcentage;

        return $this->update($id_client, ['pourcentage' => $pourcentage]);
    }

    //   public function updateSolde(int $id_compte, float $montant): bool
    // {
    //     $compte = $this->find($id_compte);

    //     if (!$compte) {
    //         return false;
    //     }

    //     $newSolde = (float) $compte->solde + $montant;

    //     return $this->update($id_compte, ['solde' => $newSolde]);
    // }

  

    public function listerEpargne(): array
    {
        return $this->findAll();
        // ->get()->getResultArray();
    }

    public function getEpargneByClient(int $id_client)
    {
        return $this->where('id_client', $id_client)->first();
    }


}
