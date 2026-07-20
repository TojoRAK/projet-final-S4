<?php
$title = 'Connexion';
$eyebrow = 'Espace opérateur';
$this->extend('layouts/auth');
?>

<?php $this->section('content') ?>

<form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label">Nom d'utilisateur</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" required autofocus>
    </div>
    <div class="mb-4">
        <label class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 justify-content-center">Se connecter</button>
    <small>username : admin - password : admin</small>
    <a href="<?= site_url('client/login')?>">Login client</a>
</form>

<?php $this->endSection() ?>
