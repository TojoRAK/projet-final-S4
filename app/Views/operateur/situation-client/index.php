<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Situation Client</title>
</head>
<body>
    <h1>Situation des comptes clients</h1>
    <p><a href="<?= site_url('dashboard') ?>">&larr; Tableau de bord</a></p>

    <table border="1" cellpadding="4">
        <thead>
            <tr><th>Client</th><th>Téléphone</th><th>Solde</th></tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $c) : ?>
                <tr>
                    <td><?= esc($c['nom']) ?></td>
                    <td><?= esc($c['telephone']) ?></td>
                    <td><?= esc($c['solde']) ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>