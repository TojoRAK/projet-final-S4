<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Tableau de bord opérateur</h1>
    <p>Connecté en tant que : <?= esc(session()->get('username')) ?></p>

    <ul>
        <li><a href="<?= site_url('prefixes') ?>">Configuration des préfixes</a></li>
        <li><a href="<?= site_url('type-operations') ?>">Configuration des types d'opérations et barèmes</a></li>
        <li><a href="<?= site_url('logout') ?>">Déconnexion</a></li>
    </ul>
</body>
</html>