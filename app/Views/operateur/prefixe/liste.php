<?php
$space = 'operateur';
$title = 'Configuration des préfixes';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card mb-4" style="max-width: 560px;">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter un préfixe</h2>
        <form action="<?= site_url('prefixes') ?>" method="post" class="d-flex gap-2 align-items-end">
            <?= csrf_field() ?>
            <div>
                <label class="form-label">Préfixe (ex: 033)</label>
                <input type="text" class="form-control" id="prefix" name="prefix" maxlength="3" required>
            </div>
            <div class="flex-grow-1">
                <label class="form-label">Opérateur</label>
                <select class="form-select" id="id_operateur" name="id_operateur">
                    <option value="">Nous</option>
                    <?php foreach ($operateurs as $op) : ?>
                        <option value="<?= esc($op['id']) ?>"><?= esc($op['nom_operateur']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</button>
        </form>
    </div>
</div>

<h2 class="h6 mb-3">Préfixes configurés</h2>
<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Préfixe</th><th>Opérateur</th></tr>
            </thead>
            <tbody>
                <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td><?= esc($p['id']) ?></td>
                        <td><?= esc($p['prefix']) ?></td>
                        <td><?= esc($p['nom_operateur'] ?? 'Nous') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>