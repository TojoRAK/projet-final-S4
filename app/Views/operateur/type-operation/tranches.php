<?php
$space = 'operateur';
$title = 'Barème — ' . $type['libelle'];
$this->extend('layouts/main');
?>

<?php $this->section('topbarActions') ?>
<a href="<?= site_url('type-operations') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Retour aux types</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="card mb-4" style="max-width: 640px;">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter une tranche</h2>
        <form action="<?= site_url('type-operations/' . $type['id'] . '/tranches') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Min</label>
                    <input type="number" class="form-control" id="min" name="min" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Max</label>
                    <input type="number" class="form-control" id="max" name="max" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Frais</label>
                    <input type="number" class="form-control" id="frais" name="frais" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-plus-lg"></i> Ajouter</button>
        </form>
    </div>
</div>

<h2 class="h6 mb-3">Tranches</h2>
<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Min</th><th>Max</th><th>Frais</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tranches as $tr): ?>
                    <tr>
                        <td><?= esc($tr['min']) ?></td>
                        <td><?= esc($tr['max']) ?></td>
                        <td><?= esc($tr['frais']) ?></td>
                        <td class="text-end">
                            <a href="<?= site_url('tranches/' . $tr['id'] . '/edit') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <form action="<?= site_url('tranches/' . $tr['id'] . '/delete') ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>
