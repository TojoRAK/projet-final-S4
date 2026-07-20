<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheModel extends Model
{
    protected $table         = 'tranches';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['min', 'max', 'frais', 'id_type_operation'];
    protected $returnType    = 'array';
    protected $useTimestamps = false;


    public function addTranche(int $min, int $max, int $frais, int $idTypeOperation): bool|string
    {
        if ($min > $max) {
            return 'Le minimum doit être inférieur ou égal au maximum.';
        }

        if ($this->hasOverlap($min, $max, $idTypeOperation)) {
            return 'Cette tranche chevauche une tranche existante pour ce type.';
        }

        return (bool) $this->insert([
            'min'               => $min,
            'max'               => $max,
            'frais'             => $frais,
            'id_type_operation' => $idTypeOperation,
        ]);
    }


    public function updateTranche(int $id, int $min, int $max, int $frais): bool|string
    {
        $tranche = $this->find($id);

        if (! $tranche) {
            return 'Tranche introuvable.';
        }

        if ($min > $max) {
            return 'Le minimum doit être inférieur ou égal au maximum.';
        }

        if ($this->hasOverlap($min, $max, (int) $tranche['id_type_operation'], $id)) {
            return 'Cette tranche chevauche une tranche existante pour ce type.';
        }

        return (bool) $this->update($id, [
            'min'   => $min,
            'max'   => $max,
            'frais' => $frais,
        ]);
    }


    public function deleteTranche(int $id): bool
    {
        return (bool) $this->delete($id);
    }

    public function findByType(int $idTypeOperation): array
    {
        return $this->where('id_type_operation', $idTypeOperation)
            ->orderBy('min', 'ASC')
            ->findAll();
    }

    
    private function hasOverlap(int $min, int $max, int $idTypeOperation, ?int $excludeId = null): bool
    {
        $builder = $this->where('id_type_operation', $idTypeOperation)
            ->where('min <=', $max)
            ->where('max >=', $min);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }
}
