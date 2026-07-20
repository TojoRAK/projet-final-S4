<?php
$title = 'Connexion';
$eyebrow = 'Espace client';
$this->extend('layouts/auth');
?>

<?php $this->section('content') ?>

<form action="/client/login" method="post">
    <?= csrf_field() ?>
    <div class="mb-4">
        <label class="form-label">Numéro de téléphone</label>
        <input type="text" class="form-control" name="telephone" id="telephone" placeholder="+2613XXXXXXXX ou 03XXXXXXXX" required autofocus>
    </div>
    <button type="submit" class="btn btn-primary w-100 justify-content-center">Se connecter</button>
    <small>Numéro test : 0322152576</small>
    <a href="<?= site_url('/login')?>">Login Opérateur</a>
</form>

<?php $this->endSection() ?>
