<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table = 'conf_promotion';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'valeur'];
    protected $returnType = 'array';
    protected $useTimestamps = false;


    public function getPromotion($id)
    {
        return $this->find($id);
    }
    public function addConfPromotion(float $valeur, string $libelle): bool
    {
        if ($this->where('libelle', $libelle)->first()) {
            return false;
        }

        return (bool) $this->insert([
            'libelle' => $libelle,
            'valeur' => $valeur,
        ]);
    }

    public function updatePromotion(float $valeur, string $libelle): bool
    {
        $existing = $this->where('libelle', $libelle)->first();

        if (!$existing) {
            return false;
        }

        return $this->update($existing['id'], ['valeur' => $valeur]);
    }
    public function listerPromotions(): array
    {
        return $this->findAll();
        // ->get()->getResultArray();
    }


}
