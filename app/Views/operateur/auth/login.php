<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion opérateur</h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')) : ?>
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>

        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="<?= esc(old('username')) ?>" required autofocus>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>