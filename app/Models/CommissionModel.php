<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table = 'conf_commission';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_operateur', 'pourcentage'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function addConfCommission(float $valeur, int $operateur): bool
    {
        if ($this->where('id_operateur', $operateur)->first()) {
            return false;
        }

        return (bool) $this->insert([
            'id_operateur' => $operateur,
            'pourcentage'  => $valeur,
        ]);
    }

    public function updateCommission(float $valeur, int $operateur): bool
    {
        $existing = $this->where('id_operateur', $operateur)->first();

        if (!$existing) {
            return false;
        }

        return $this->update($existing['id'], ['pourcentage' => $valeur]);
    }

    public function getCommissionByOperateur(int $operateur): float
    {
        $row = $this->where('id_operateur', $operateur)->first();

        return $row ? (float) $row['pourcentage'] : 0.0;
    }

    public function listerCommissions(): array
    {
        return $this->db->table('conf_commission')
            ->select('conf_commission.id, conf_commission.pourcentage, autres_operateurs.id AS id_operateur, autres_operateurs.nom_operateur')
            ->join('autres_operateurs', 'autres_operateurs.id = conf_commission.id_operateur')
            ->orderBy('autres_operateurs.nom_operateur', 'ASC')
            ->get()
            ->getResultArray();
    }
}
