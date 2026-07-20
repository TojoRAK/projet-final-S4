<?php
$space = 'operateur';
$title = 'Tableau de bord';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<p class="text-muted mb-4">Connecté en tant que <strong><?= esc(session()->get('username')) ?></strong></p>

<h2 class="h6 mb-3">Accès rapides</h2>
<div class="row g-3">
    <div class="col-md-3">
        <a href="<?= site_url('prefixes') ?>" class="card text-decoration-none d-block h-100">
            <div class="card-body">
                <i class="bi bi-hash fs-4 mb-2 d-block" style="color: var(--bleu);"></i>
                <div class="fw-semibold text-body">Préfixes</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= site_url('type-operations') ?>" class="card text-decoration-none d-block h-100">
            <div class="card-body">
                <i class="bi bi-diagram-3 fs-4 mb-2 d-block" style="color: var(--bleu);"></i>
                <div class="fw-semibold text-body">Types d'opérations</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= site_url('situation-gain') ?>" class="card text-decoration-none d-block h-100">
            <div class="card-body">
                <i class="bi bi-graph-up-arrow fs-4 mb-2 d-block" style="color: var(--bleu);"></i>
                <div class="fw-semibold text-body">Situation gain</div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= site_url('situation-client') ?>" class="card text-decoration-none d-block h-100">
            <div class="card-body">
                <i class="bi bi-people fs-4 mb-2 d-block" style="color: var(--bleu);"></i>
                <div class="fw-semibold text-body">Situation clients</div>
            </div>
        </a>
    </div>
</div>

<?php $this->endSection() ?>
