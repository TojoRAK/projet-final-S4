<?php
$navItemsClient = [
    ['url' => 'client/dashboard',  'icon' => 'bi-speedometer2',     'label' => 'Tableau de bord'],
    ['url' => 'client/operation',  'icon' => 'bi-arrow-left-right', 'label' => 'Nouvelle opération'],
    ['url' => 'client/historique', 'icon' => 'bi-clock-history',    'label' => 'Historique'],
];

$navItemsOperateur = [
    ['url' => 'dashboard',         'icon' => 'bi-speedometer2',   'label' => 'Tableau de bord'],
    ['url' => 'prefixes',          'icon' => 'bi-hash',           'label' => 'Préfixes'],
    ['url' => 'type-operations',   'icon' => 'bi-diagram-3',      'label' => "Types d'opérations"],
    ['url' => 'situation-gain',    'icon' => 'bi-graph-up-arrow', 'label' => 'Situation gain'],
    ['url' => 'situation-client',  'icon' => 'bi-people',         'label' => 'Situation clients'],
];

$isOperateur = ($space ?? 'client') === 'operateur';
$navItems    = $isOperateur ? $navItemsOperateur : $navItemsClient;
$logoutUrl   = $isOperateur ? 'logout' : 'client/logout';
$currentUrl  = current_url();

$flashErrors = session()->getFlashdata('errors');
$flashError  = session()->getFlashdata('error');
$flashSuccess = session()->getFlashdata('success') ?? session()->getFlashdata('message');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Money</title>
    <link href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <!-- <span class="badge-logo">M</span> -->
            Mobile Money
        </div>
        <ul class="sidebar-nav">
            <?php foreach ($navItems as $item): ?>
                <li>
                    <a class="nav-link<?= str_contains($currentUrl, $item['url']) ? ' active' : '' ?>" href="<?= base_url($item['url']) ?>">
                        <i class="bi <?= esc($item['icon']) ?>"></i> <?= esc($item['label']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="sidebar-footer">
            <a class="nav-link" href="<?= base_url($logoutUrl) ?>"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="eyebrow"><?= esc($eyebrow ?? ($isOperateur ? 'Espace opérateur' : 'Espace client')) ?></div>
                <h1><?= esc($title ?? '') ?></h1>
            </div>
            <?= $this->renderSection('topbarActions') ?>
        </div>

        <div class="content">

            <?php if ($flashSuccess): ?>
                <div class="alert alert-success mb-4"><i class="bi bi-check-circle"></i> <?= esc($flashSuccess) ?></div>
            <?php endif; ?>

            <?php if ($flashError): ?>
                <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-circle"></i> <?= esc($flashError) ?></div>
            <?php endif; ?>

            <?php if ($flashErrors): ?>
                <div class="alert alert-danger mb-4">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?= is_array($flashErrors) ? esc(implode(' ', $flashErrors)) : esc($flashErrors) ?></span>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
