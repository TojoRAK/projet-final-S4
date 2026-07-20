<?php

namespace App\Models;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table         = 'type_operation';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['libelle'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

 
    public function addType(string $libelle): bool
    {
        $libelle = trim($libelle);

        if ($libelle === '') {
            return false;
        }

        try {
            return (bool) $this->insert(['libelle' => $libelle]);
        } catch (DatabaseException $e) {
            return false; 
        }
    }

    public function findAllTypes(): array
    {
        return $this->orderBy('libelle', 'ASC')->findAll();
    }
}