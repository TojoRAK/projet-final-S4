<?php
$space = 'operateur';
$title = 'Promotions';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card mb-4" style="max-width: 480px;">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter / modifier une promotion</h2>
      
            <form action="<?= site_url('promotions') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">Libelle</label>
                    <input type="text" name="libelle" id="">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="valeur" name="valeur" min="0" max="100" step="0.01" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-check2"></i> Enregistrer</button>
            </form>
    </div>
</div>

<h2 class="h6 mb-3">Promotion configurées</h2>
<div class="card">
    <div class="card-body">
        <?php if (!empty($promotions)): ?>
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>Libelle</th><th>Valeur</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($promotions as $c): ?>
                        <tr>
                            <td><?= esc($c['libelle']) ?></td>
                            <td><?= esc((float) $c['valeur'] * 100) ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mb-0">Aucune promotion configurée</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection() ?>
