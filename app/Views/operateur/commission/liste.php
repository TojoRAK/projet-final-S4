<?php
$space = 'operateur';
$title = 'Commissions';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card mb-4" style="max-width: 480px;">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter / modifier une commission</h2>
        <?php if (empty($operateurs)): ?>
            <p class="text-muted mb-0">Aucun autre opérateur configuré pour l'instant.</p>
        <?php else: ?>
            <form action="<?= site_url('commissions') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">Opérateur</label>
                        <select class="form-select" id="operateur" name="operateur" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach ($operateurs as $op): ?>
                                <option value="<?= esc($op['id']) ?>"><?= esc($op['nom_operateur']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="valeur" name="valeur" min="0" max="100" step="0.01" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-check2"></i> Enregistrer</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<h2 class="h6 mb-3">Commissions configurées</h2>
<div class="card">
    <div class="card-body">
        <?php if (!empty($commissions)): ?>
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Opérateur</th><th>Commission</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($commissions as $c): ?>
                        <tr>
                            <td><?= esc($c['nom_operateur']) ?></td>
                            <td><?= esc($c['pourcentage']) ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mb-0">Aucune commission configurée</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection() ?>
