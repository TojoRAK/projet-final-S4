<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Types d'opérations</title>
</head>
<body>
    <h1>Types d'opérations</h1>
    <p><a href="<?= site_url('dashboard') ?>">&larr; Tableau de bord</a></p>

    <?php if (session()->getFlashdata('error')) : ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <h2>Ajouter un type</h2>
    <form action="<?= site_url('type-operations') ?>" method="post">
        <?= csrf_field() ?>
        <label for="libelle">Libellé</label>
        <input type="text" id="libelle" name="libelle" required>
        <button type="submit">Ajouter</button>
    </form>

    <h2>Liste</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr><th>ID</th><th>Libellé</th><th>Barème</th></tr>
        </thead>
        <tbody>
            <?php foreach ($types as $t) : ?>
                <tr>
                    <td><?= esc($t['id']) ?></td>
                    <td><?= esc($t['libelle']) ?></td>
                    <td><a href="<?= site_url('type-operations/' . $t['id'] . '/tranches') ?>">Voir le barème</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>