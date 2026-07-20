<?php
$space = 'operateur';
$title = "Types d'opérations";
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card mb-4" style="max-width: 480px;">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter un type</h2>
        <form action="<?= site_url('type-operations') ?>" method="post" class="d-flex gap-2 align-items-end">
            <?= csrf_field() ?>
            <div class="flex-grow-1">
                <label class="form-label">Libellé</label>
                <input type="text" class="form-control" id="libelle" name="libelle" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter</button>
        </form>
    </div>
</div>

<h2 class="h6 mb-3">Liste</h2>
<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>ID</th><th>Libellé</th><th class="text-end">Barème</th></tr>
            </thead>
            <tbody>
                <?php foreach ($types as $t): ?>
                    <tr>
                        <td><?= esc($t['id']) ?></td>
                        <td><?= esc($t['libelle']) ?></td>
                        <td class="text-end">
                            <a href="<?= site_url('type-operations/' . $t['id'] . '/tranches') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> Voir le barème</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>
