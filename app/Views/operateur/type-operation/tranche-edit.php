<?php
$space = 'operateur';
$title = 'Modifier la tranche';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card" style="max-width: 480px;">
    <div class="card-body">
        <form action="<?= site_url('tranches/' . $tranche['id'] . '/update') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Min</label>
                <input type="number" class="form-control" id="min" name="min" value="<?= esc($tranche['min']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Max</label>
                <input type="number" class="form-control" id="max" name="max" value="<?= esc($tranche['max']) ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Frais</label>
                <input type="number" class="form-control" id="frais" name="frais" value="<?= esc($tranche['frais']) ?>" required>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Enregistrer</button>
                <a href="<?= site_url('type-operations/' . $tranche['id_type_operation'] . '/tranches') ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection() ?>
