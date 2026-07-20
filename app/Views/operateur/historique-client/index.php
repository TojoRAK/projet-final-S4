<?php
$space = 'operateur';
$title = 'Historique — ' . $client['nom'];
$this->extend('layouts/main');
?>

<?php $this->section('topbarActions') ?>
<a href="<?= site_url('situation-client') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Situation client</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<div class="card mb-3">
    <div class="card-body py-3">
        <form class="row g-2 align-items-end" action="<?= site_url('clients/' . $client['id'] . '/historique') ?>" method="get">
            <div class="col-md-2">
                <label class="form-label">Date début</label>
                <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?= esc($filtre['date_debut'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date fin</label>
                <input type="date" class="form-control" id="date_fin" name="date_fin" value="<?= esc($filtre['date_fin'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Montant min</label>
                <input type="number" class="form-control" id="montant_min" name="montant_min" value="<?= esc($filtre['montant_min'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Montant max</label>
                <input type="number" class="form-control" id="montant_max" name="montant_max" value="<?= esc($filtre['montant_max'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select class="form-select" id="id_type_operation" name="id_type_operation">
                    <option value="">Tous</option>
                    <?php foreach ($types as $t): ?>
                        <option value="<?= esc($t['id']) ?>" <?= (string) ($filtre['id_type_operation'] ?? '') === (string) $t['id'] ? 'selected' : '' ?>>
                            <?= esc($t['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filtrer</button>
                <a href="<?= site_url('clients/' . $client['id'] . '/historique') ?>" class="btn btn-outline-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Date</th><th>Montant</th><th>Type</th><th>Frais</th><th>Bénéficiaire</th></tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $ligne): ?>
                    <tr>
                        <td><?= esc($ligne['date']) ?></td>
                        <td><?= number_format($ligne['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($ligne['type']) ?></td>
                        <td><?= number_format($ligne['frais_applique'], 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($ligne['beneficiaire'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>
