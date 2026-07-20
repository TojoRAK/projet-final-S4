<?php
$space = 'client';
$title = 'Tableau de bord';
$this->extend('layouts/main');
?>

<?php $this->section('topbarActions') ?>
<a href="<?= base_url('client/operation') ?>" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nouvelle opération</a>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<h1 class="visually-hidden">BIENVENUE <?= esc(session()->get('client')['nom'] ?? '') ?></h1>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
                <div class="stat-label">Solde disponible</div>
                <div class="stat-value"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</div>
            </div>
        </div>
    </div>
</div>

<h2 class="h6 mb-3">Dernières transactions</h2>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted mb-0">Aucune transaction</p>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection() ?>
