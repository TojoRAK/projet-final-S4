<?php

namespace App\Models;

use CodeIgniter\Model;

class AutresOperateurModel extends Model
{
    protected $table         = 'autres_operateurs';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nom_operateur'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function findAllOperateurs(): array
    {
        return $this->orderBy('nom_operateur', 'ASC')->findAll();
    }
}