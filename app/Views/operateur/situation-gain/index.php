<?php
$space = 'operateur';
$title = 'Situation des gains';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<h2 class="h6 mb-3">Nous</h2>
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
                <div class="stat-label">Gain global — nous</div>
                <div class="stat-value"><?= number_format($situationGlobale['nous']['total_gain'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($situationGlobale['nous']['nb_transactions'] ?? 0) ?> transactions au total</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="h6 mb-3">Évolution — Retrait (nous)</h3>
                <?php if (! empty($situationRetrait['evolution'])) : ?>
                    <div style="height: 140px;">
                        <canvas id="chart-retrait" data-evolution='<?= json_encode($situationRetrait['evolution']) ?>'></canvas>
                    </div>
                <?php else : ?>
                    <p class="text-muted small mb-0">Aucune donnée sur cette période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="h6 mb-3">Évolution — Transfert (nous)</h3>
                <?php if (! empty($situationTransfert['evolution'])) : ?>
                    <div style="height: 140px;">
                        <canvas id="chart-transfert" data-evolution='<?= json_encode($situationTransfert['evolution']) ?>'></canvas>
                    </div>
                <?php else : ?>
                    <p class="text-muted small mb-0">Aucune donnée sur cette période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h3 class="h6 mb-3">Évolution — Superposé (Retrait / Transfert, nous)</h3>
        <?php if (! empty($evolutionCombinee)) : ?>
            <div style="height: 160px;">
                <canvas id="chart-superpose" data-evolution='<?= json_encode($evolutionCombinee) ?>'></canvas>
            </div>
        <?php else : ?>
            <p class="text-muted small mb-0">Aucune donnée sur cette période.</p>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3 mb-5">
    <div class="col-md-4">
        <h3 class="h6 mb-3">Détail — Retrait (nous)</h3>
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
        <h3 class="h6 mb-3">Détail — Transfert (nous)</h3>
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
        <h3 class="h6 mb-3">Détail — Superposé (nous)</h3>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Retrait</th><th>Transfert</th></tr></thead>
                    <tbody>
                        <?php foreach (($evolutionCombinee ?? []) as $row): ?>
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

<h2 class="h6 mb-3">Autres opérateurs (agrégat)</h2>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
                <div class="stat-label">Retrait</div>
                <div class="stat-value"><?= number_format($autresRetrait['total_montant'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($autresRetrait['nb_transactions'] ?? 0) ?> transactions — <?= number_format($autresRetrait['total_gain'] ?? 0, 0, ',', ' ') ?> Ar de gain</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
                <div class="stat-label">Transfert</div>
                <div class="stat-value"><?= number_format($autresTransfert['total_montant'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($autresTransfert['nb_transactions'] ?? 0) ?> transactions — <?= number_format($autresTransfert['total_gain'] ?? 0, 0, ',', ' ') ?> Ar de gain</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-globe"></i></div>
                <div class="stat-label">Gain global — autres opérateurs</div>
                <div class="stat-value"><?= number_format($situationGlobale['autres']['total_gain'] ?? 0, 0, ',', ' ') ?> Ar</div>
                <div class="stat-trend"><i class="bi bi-hash"></i> <?= esc($situationGlobale['autres']['nb_transactions'] ?? 0) ?> transactions au total</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="h6 mb-3">Évolution — Retrait (autres opérateurs)</h3>
                <?php if (! empty($autresRetrait['evolution'])) : ?>
                    <div style="height: 140px;">
                        <canvas id="chart-retrait-autres" data-evolution='<?= json_encode($autresRetrait['evolution']) ?>'></canvas>
                    </div>
                <?php else : ?>
                    <p class="text-muted small mb-0">Aucune donnée sur cette période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="h6 mb-3">Évolution — Transfert (autres opérateurs)</h3>
                <?php if (! empty($autresTransfert['evolution'])) : ?>
                    <div style="height: 140px;">
                        <canvas id="chart-transfert-autres" data-evolution='<?= json_encode($autresTransfert['evolution']) ?>'></canvas>
                    </div>
                <?php else : ?>
                    <p class="text-muted small mb-0">Aucune donnée sur cette période.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-5">
    <div class="col-md-6">
        <h3 class="h6 mb-3">Détail — Retrait (autres opérateurs)</h3>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Gain</th></tr></thead>
                    <tbody>
                        <?php foreach (($autresRetrait['evolution'] ?? []) as $row): ?>
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
    <div class="col-md-6">
        <h3 class="h6 mb-3">Détail — Transfert (autres opérateurs)</h3>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Gain</th></tr></thead>
                    <tbody>
                        <?php foreach (($autresTransfert['evolution'] ?? []) as $row): ?>
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
</div>

<h2 class="h6 mb-3">Détail par opérateur</h2>
<div class="card mb-5">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead><tr><th>Opérateur</th><th>Retrait — gain</th><th>Transfert — gain</th></tr></thead>
            <tbody>
                <?php foreach (($parOperateur ?? []) as $ligne): ?>
                    <tr>
                        <td><?= esc($ligne['nom_operateur']) ?></td>
                        <td><?= number_format($ligne['retrait']['total_gain'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['transfert']['total_gain'] ?? 0, 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<h2 class="h6 mb-3">Situation des montants à payer</h2>
<p class="text-muted small">Montant total des transactions attribuées à chaque opérateur, avec la commission négociée.</p>
<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead><tr><th>Opérateur</th><th>Montant total</th><th>Commission</th><th>À payer</th></tr></thead>
            <tbody>
                <?php foreach (($situationMontant['details'] ?? []) as $ligne): ?>
                    <tr>
                        <td><?= esc($ligne['nom_operateur']) ?></td>
                        <td><?= number_format($ligne['total_montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['pourcentage'] * 100, 2, ',', ' ') ?> %</td>
                        <td><?= number_format($ligne['montant_a_payer'], 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
                <tr class="table-primary">
                    <td colspan="3" class="text-end fw-bold">Total à payer</td>
                    <td class="fw-bold"><?= number_format($situationMontant['total_a_payer'] ?? 0, 0, ',', ' ') ?> Ar</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/graphes.js') ?>"></script>
<?php $this->endSection() ?>