<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nom', 'telephone'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nom'       => 'required|min_length[2]',
        'telephone' => 'required|regex_match[/^(\+2613|03)[0-9]{8}$/]',
    ];

    protected $validationMessages = [
        'nom' => [
            'required'    => 'Le nom est requis',
            'min_length'  => 'Le nom doit contenir au moins 2 caractères',
        ],
        'telephone' => [
            'required'        => 'Le numéro de téléphone est requis',
            'regex_match'     => 'Le numéro doit être au format +2613XXXXXXX ou 03XXXXXXX',
        ],
    ];
    
    public function verifierExistenceNum(string $num)
    {
        if (!preg_match('/^(\+2613|03)[0-9]{8}$/', $num)) {
            return null;
        }
        return $this->getClientByPhone($this->normaliserNumero($num));
    }

    public function normaliserNumero(string $num): string
    {
        if (str_starts_with($num, '+2613')) {
            return '0' . substr($num, 4);
        }

        return $num;
    }
    public function getClientById(int $id)
    {
        return $this->find($id);
    }

    public function getClientByPhone(string $telephone)
    {
        return $this->where('telephone', $telephone)->first();
    }


}
