<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Barème — <?= esc($type['libelle']) ?></title>
</head>
<body>
    <h1>Barème de frais — <?= esc($type['libelle']) ?></h1>
    <p><a href="<?= site_url('type-operations') ?>">&larr; Retour aux types</a></p>

    <?php if (session()->getFlashdata('error')) : ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <p><?= esc(session()->getFlashdata('message')) ?></p>
    <?php endif; ?>

    <h2>Ajouter une tranche</h2>
    <form action="<?= site_url('type-operations/' . $type['id'] . '/tranches') ?>" method="post">
        <?= csrf_field() ?>
        <label for="min">Min</label>
        <input type="number" id="min" name="min" required>
        <label for="max">Max</label>
        <input type="number" id="max" name="max" required>
        <label for="frais">Frais</label>
        <input type="number" id="frais" name="frais" required>
        <button type="submit">Ajouter</button>
    </form>

    <h2>Tranches</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr><th>Min</th><th>Max</th><th>Frais</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($tranches as $tr) : ?>
                <tr>
                    <td><?= esc($tr['min']) ?></td>
                    <td><?= esc($tr['max']) ?></td>
                    <td><?= esc($tr['frais']) ?></td>
                    <td>
                        <a href="<?= site_url('tranches/' . $tr['id'] . '/edit') ?>">Modifier</a>
                        <form action="<?= site_url('tranches/' . $tr['id'] . '/delete') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>