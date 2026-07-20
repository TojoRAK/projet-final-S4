<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
</head>
<body>
    <h1>Historique des transactions</h1>
    <a href="<?= base_url('/client/dashboard') ?>">Retour au tableau de bord</a>

    <h2>Filtres</h2>
    <form action="/client/historique" method="get">
        <div>
            <label for="date_debut">Date début</label>
            <input type="date" name="date_debut" id="date_debut" value="<?= esc($filtre['date_debut'] ?? '') ?>">

            <label for="date_fin">Date fin</label>
            <input type="date" name="date_fin" id="date_fin" value="<?= esc($filtre['date_fin'] ?? '') ?>">
        </div>

        <div>
            <label for="montant_min">Montant min</label>
            <input type="number" name="montant_min" id="montant_min" value="<?= esc($filtre['montant_min'] ?? '') ?>">

            <label for="montant_max">Montant max</label>
            <input type="number" name="montant_max" id="montant_max" value="<?= esc($filtre['montant_max'] ?? '') ?>">
        </div>

        <div>
            <label for="type">Type de transaction</label>
            <select name="type" id="type">
                <option value="">Tous</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= esc($type->id) ?>" <?= (string) ($filtre['type'] ?? '') === (string) $type->id ? 'selected' : '' ?>>
                        <?= esc(ucfirst($type->libelle)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit">Filtrer</button>
        <a href="/client/historique">Réinitialiser</a>
    </form>

    <h2>Liste</h2>
    <?php if (!empty($historique)): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Sens</th>
                    <th>Bénéficiaire</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $transaction): ?>
                    <tr>
                        <td><?= esc($transaction->date) ?></td>
                        <td><?= esc($transaction->libelle) ?></td>
                        <td><?= esc($transaction->montant) ?> Ar</td>
                        <td><?= esc($transaction->frais_applique) ?> Ar</td>
                        <td><?= esc($transaction->sens) ?></td>
                        <td><?= esc($transaction->beneficiaire ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune transaction trouvée</p>
    <?php endif; ?>
</body>
</html>