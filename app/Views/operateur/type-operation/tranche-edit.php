<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une tranche — Fifaliana</title>
</head>
<body>
    <h1>Modifier la tranche</h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <p><?= esc(session()->getFlashdata('error')) ?></p>
    <?php endif; ?>

    <form action="<?= site_url('tranches/' . $tranche['id'] . '/update') ?>" method="post">
        <?= csrf_field() ?>
        <label for="min">Min</label>
        <input type="number" id="min" name="min" value="<?= esc($tranche['min']) ?>" required>
        <label for="max">Max</label>
        <input type="number" id="max" name="max" value="<?= esc($tranche['max']) ?>" required>
        <label for="frais">Frais</label>
        <input type="number" id="frais" name="frais" value="<?= esc($tranche['frais']) ?>" required>
        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="<?= site_url('type-operations/' . $tranche['id_type_operation'] . '/tranches') ?>">&larr; Retour au barème</a></p>
</body>
</html>