<?php
$space = 'operateur';
$title = 'Situation des clients';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>Client</th><th>Téléphone</th><th>Solde</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><span class="avatar-mini"><?= esc(strtoupper(substr($c['nom'], 0, 2))) ?></span><?= esc($c['nom']) ?></td>
                        <td><?= esc($c['telephone']) ?></td>
                        <td><?= number_format($c['solde'], 0, ',', ' ') ?> Ar</td>
                        <td class="text-end">
                            <a href="<?= site_url('clients/' . $c['id_client'] . '/historique') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>
