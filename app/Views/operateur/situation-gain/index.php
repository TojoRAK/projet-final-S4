<?php
$space = 'operateur';
$title = 'Situation des gains';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-label">Retrait</div>
                <div class="stat-value"><?= number_format($situationRetrait['total_montant'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($situationRetrait['nb_transactions'] ?? 0) ?> transactions — <?= number_format($situationRetrait['total_gain'] ?? 0, 0, ',', ' ') ?> Ar de gain</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
                <div class="stat-label">Transfert</div>
                <div class="stat-value"><?= number_format($situationTransfert['total_montant'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($situationTransfert['nb_transactions'] ?? 0) ?> transactions — <?= number_format($situationTransfert['total_gain'] ?? 0, 0, ',', ' ') ?> Ar de gain</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                <div class="stat-label">Gain global</div>
                <div class="stat-value"><?= number_format($situationGlobale['total_gain'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($situationGlobale['nb_transactions'] ?? 0) ?> transactions au total</div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-success mb-4"><i class="bi bi-bar-chart"></i> Graphes (Chart.js) à intégrer — les données ci-dessous alimenteront les courbes Retrait / Transfert / Superposé.</div>

<div class="row g-3">
    <div class="col-md-4">
        <h2 class="h6 mb-3">Évolution — Retrait</h2>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Gain</th></tr></thead>
                    <tbody>
                        <?php foreach (($situationRetrait['evolution'] ?? []) as $row): ?>
                            <tr>
                                <td><?= esc($row['jour']) ?></td>
                                <td><?= number_format($row['gain'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <h2 class="h6 mb-3">Évolution — Transfert</h2>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Gain</th></tr></thead>
                    <tbody>
                        <?php foreach (($situationTransfert['evolution'] ?? []) as $row): ?>
                            <tr>
                                <td><?= esc($row['jour']) ?></td>
                                <td><?= number_format($row['gain'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <h2 class="h6 mb-3">Évolution — Superposé</h2>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Retrait</th><th>Transfert</th></tr></thead>
                    <tbody>
                        <?php foreach ($evolutionCombinee as $row): ?>
                            <tr>
                                <td><?= esc($row['jour']) ?></td>
                                <td><?= number_format($row['retrait'], 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($row['transfert'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection() ?>
