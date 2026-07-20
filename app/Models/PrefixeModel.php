<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'conf_prefix';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['prefix'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;

   
    public function addPrefix(string $prefix): bool
    {
        if (! $this->isValidFormat($prefix)) {
            return false;
        }

        return (bool) $this->insert(['prefix' => $prefix]);
    }

    public function findAllPrefix(): array
    {
        return $this->orderBy('prefix', 'ASC')->findAll();
    }

    public function estNotreOperateur(string $numero): bool
    {
        $conf = $this->trouverPrefixe($numero);

        return $conf !== null && $conf['id_operateur'] === null;
    }

    public function getOperateurByNumero(string $numero): ?int
    {
        $conf = $this->trouverPrefixe($numero);

        return $conf['id_operateur'] ?? null;
    }

    private function trouverPrefixe(string $numero): ?array
    {
        $authModel = new AuthModel();
        $normalise = $authModel->normaliserNumero($numero);
        $prefixe = substr($normalise, 0, 3);

        return $this->where('prefix', $prefixe)->first();
    }

    private function isValidFormat(string $prefix): bool
    {
        return (bool) preg_match('/^03[0-9]$/', $prefix);
    }
}