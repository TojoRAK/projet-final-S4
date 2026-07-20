<?php
$space = 'client';
$title = 'Historique';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card mb-3">
    <div class="card-body py-3">
        <form class="row g-2 align-items-end" action="/client/historique" method="get">
            <div class="col-md-2">
                <label class="form-label">Date début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= esc($filtre['date_debut'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Date fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= esc($filtre['date_fin'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Montant min</label>
                <input type="number" name="montant_min" class="form-control" value="<?= esc($filtre['montant_min'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Montant max</label>
                <input type="number" name="montant_max" class="form-control" value="<?= esc($filtre['montant_max'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">Tous</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= esc($type->id) ?>" <?= (string) ($filtre['type'] ?? '') === (string) $type->id ? 'selected' : '' ?>>
                            <?= esc(ucfirst($type->libelle)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filtrer</button>
                <a href="/client/historique" class="btn btn-outline-secondary">Réinitialiser</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($historique)): ?>
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Frais</th>
                        <th>Sens</th>
                        <th>Bénéficiaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historique as $transaction): ?>
                        <tr>
                            <td><?= esc($transaction->date) ?></td>
                            <td><?= esc(ucfirst($transaction->libelle)) ?></td>
                            <td><?= number_format($transaction->montant, 0, ',', ' ') ?> Ar</td>
                            <td><?= number_format($transaction->frais_applique, 0, ',', ' ') ?> Ar</td>
                            <td><span class="badge-statut"><?= esc($transaction->sens) ?></span></td>
                            <td><?= esc($transaction->beneficiaire ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mb-0">Aucune transaction trouvée</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection() ?>
