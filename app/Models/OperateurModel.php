<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table         = 'operateur';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['username', 'password', 'date_creation'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

  
    public function createOperateur(string $username, string $plainPassword): int|false
    {
        return $this->insert([
            'username' => $username,
            'password' => password_hash($plainPassword, PASSWORD_DEFAULT),
        ]);
    }
}