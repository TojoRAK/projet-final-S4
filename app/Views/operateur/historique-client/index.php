<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique — <?= esc($client['nom']) ?></title>
</head>
<body>
    <h1>Historique — <?= esc($client['nom']) ?></h1>
    <p><a href="<?= site_url('situation-client') ?>">&larr; Situation client</a></p>

    <h2>Filtres</h2>
    <form action="<?= site_url('clients/' . $client['id'] . '/historique') ?>" method="get">
        <label for="date_debut">Date début</label>
        <input type="date" id="date_debut" name="date_debut" value="<?= esc($filtre['date_debut'] ?? '') ?>">

        <label for="date_fin">Date fin</label>
        <input type="date" id="date_fin" name="date_fin" value="<?= esc($filtre['date_fin'] ?? '') ?>">

        <label for="montant_min">Montant min</label>
        <input type="number" id="montant_min" name="montant_min" value="<?= esc($filtre['montant_min'] ?? '') ?>">

        <label for="montant_max">Montant max</label>
        <input type="number" id="montant_max" name="montant_max" value="<?= esc($filtre['montant_max'] ?? '') ?>">

        <label for="id_type_operation">Type</label>
        <select id="id_type_operation" name="id_type_operation">
            <option value="">Tous</option>
            <?php foreach ($types as $t) : ?>
                <option value="<?= esc($t['id']) ?>" <?= (string) ($filtre['id_type_operation'] ?? '') === (string) $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrer</button>
        <a href="<?= site_url('clients/' . $client['id'] . '/historique') ?>">Réinitialiser</a>
    </form>

    <h2>Transactions</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr><th>Date</th><th>Montant</th><th>Type</th><th>Frais</th><th>Bénéficiaire</th></tr>
        </thead>
        <tbody>
            <?php foreach ($historique as $ligne) : ?>
                <tr>
                    <td><?= esc($ligne['date']) ?></td>
                    <td><?= esc($ligne['montant']) ?> Ar</td>
                    <td><?= esc($ligne['type']) ?></td>
                    <td><?= esc($ligne['frais_applique']) ?> Ar</td>
                    <td><?= esc($ligne['beneficiaire'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>