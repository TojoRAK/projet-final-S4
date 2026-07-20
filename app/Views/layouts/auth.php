<?php
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
<body class="page-login">

    <div class="card" style="width: 100%; max-width: 380px;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-1">
                <!-- <span class="badge-logo">M</span> -->
                <span class="fw-bold">Mobile Money</span>
            </div>
            <p class="text-muted mb-4" style="font-size: 0.85rem;"><?= esc($eyebrow ?? '') ?></p>

            <?php if ($flashSuccess): ?>
                <div class="alert alert-success mb-3"><i class="bi bi-check-circle"></i> <?= esc($flashSuccess) ?></div>
            <?php endif; ?>

            <?php if ($flashError): ?>
                <div class="alert alert-danger mb-3"><i class="bi bi-exclamation-circle"></i> <?= esc($flashError) ?></div>
            <?php endif; ?>

            <?php if ($flashErrors): ?>
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?= is_array($flashErrors) ? esc(implode(' ', $flashErrors)) : esc($flashErrors) ?></span>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
