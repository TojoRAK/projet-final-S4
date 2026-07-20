<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Configuration des préfixes — Fifaliana</title>
</head>
<body>
    <h1>Configuration des préfixes</h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <h2>Ajouter un préfixe</h2>
    <form action="<?= site_url('prefixes') ?>" method="post">
        <?= csrf_field() ?>

        <label for="prefix">Préfixe (ex: 033)</label>
        <input type="text" id="prefix" name="prefix" maxlength="3" required>

        <button type="submit">Ajouter</button>
    </form>

    <h2>Préfixes configurés</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Préfixe</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prefixes as $p) : ?>
                <tr>
                    <td><?= esc($p['id']) ?></td>
                    <td><?= esc($p['prefix']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>