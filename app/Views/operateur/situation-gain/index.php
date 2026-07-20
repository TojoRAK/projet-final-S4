<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Situation Gain</title>
</head>

<body>
    <h1>Situation des gains</h1>
    <p><a href="<?= site_url('dashboard') ?>">&larr; Tableau de bord</a></p>

    <h2>Cartes</h2>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Type</th>
                <th>Nb transactions</th>
                <th>Montant total</th>
                <th>Gain (frais)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Retrait</td>
                <td><?= esc($situationRetrait['nb_transactions'] ?? 0) ?></td>
                <td><?= esc($situationRetrait['total_montant'] ?? 0) ?> Ar</td>
                <td><?= esc($situationRetrait['total_gain'] ?? 0) ?> Ar</td>
            </tr>
            <tr>
                <td>Transfert</td>
                <td><?= esc($situationTransfert['nb_transactions'] ?? 0) ?></td>
                <td><?= esc($situationTransfert['total_montant'] ?? 0) ?> Ar</td>
                <td><?= esc($situationTransfert['total_gain'] ?? 0) ?> Ar</td>
            </tr>
            <tr>
                <td><strong>Global (tous types)</strong></td>
                <td><?= esc($situationGlobale['nb_transactions']) ?></td>
                <td><?= esc($situationGlobale['total_montant']) ?> Ar</td>
                <td><?= esc($situationGlobale['total_gain']) ?> Ar</td>
            </tr>
        </tbody>
    </table>

    <p>
        <em>
            chart.js a integrer rehefa manao css
        </em>
    </p>

    <h2>Évolution — Retrait</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Gain</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($situationRetrait['evolution'] ?? []) as $row) : ?>
                <tr>
                    <td><?= esc($row['jour']) ?></td>
                    <td><?= esc($row['gain']) ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Évolution — Transfert</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Gain</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($situationTransfert['evolution'] ?? []) as $row) : ?>
                <tr>
                    <td><?= esc($row['jour']) ?></td>
                    <td><?= esc($row['gain']) ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Évolution — Superposé (Retrait / Transfert)</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Retrait</th>
                <th>Transfert</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evolutionCombinee as $row) : ?>
                <tr>
                    <td><?= esc($row['jour']) ?></td>
                    <td><?= esc($row['retrait']) ?> Ar</td>
                    <td><?= esc($row['transfert']) ?> Ar</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>