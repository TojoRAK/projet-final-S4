<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'conf_prefix';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['prefix', 'id_operateur'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;


    public function addPrefix(string $prefix, ?int $idOperateur = null): bool
    {
        if (! $this->isValidFormat($prefix)) {
            return false;
        }

        return (bool) $this->insert([
            'prefix'       => $prefix,
            'id_operateur' => $idOperateur,
        ]);
    }
    public function trouverOperateurParTelephone(string $telephone): ?int
    {
        $prefixTel = substr(preg_replace('/\D/', '', $telephone), 0, 3);

        $ligne = $this->where('prefix', $prefixTel)->first();

        return $ligne['id_operateur'] ?? null;
    }

    public function findAllPrefix(): array
    {
        return $this->select('conf_prefix.*, autres_operateurs.nom_operateur')
            ->join('autres_operateurs', 'autres_operateurs.id = conf_prefix.id_operateur', 'left')
            ->orderBy('conf_prefix.prefix', 'ASC')
            ->findAll();
    }

    private function isValidFormat(string $prefix): bool
    {
        return (bool) preg_match('/^03[0-9]$/', $prefix);
    }
}
