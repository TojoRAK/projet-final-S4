<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table         = 'clients';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nom', 'telephone'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

  
    public function getSituationClients(): array
    {
        return $this->select(
            'clients.id AS id_client,
             clients.nom,
             clients.telephone,
             compte.id AS id_compte,
             compte.solde'
        )
            ->join('compte', 'compte.id_client = clients.id')
            ->orderBy('clients.nom', 'ASC')
            ->findAll();
    }
}
