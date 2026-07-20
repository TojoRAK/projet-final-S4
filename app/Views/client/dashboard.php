<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
</head>
<body>
    

    <h1>BIENVENUE <?= esc(session()->get('client')['nom'] ?? '') ?></h1>
    <a href="<?= base_url('/client/operation') ?>">Nouvelle transaction</a>
    <a href="<?= base_url('/client/historique') ?>">Historique de transactions</a>

    <div>
        <h2>Solde</h2>
        <p><?= $solde ?? 0 ?> Ar</p>
    </div>

    <div>
        <h2>Historique des 10 dernières transactions</h2>
        <?php if (!empty($historique)): ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Frais</th>
                        <th>Sens</th>
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
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune transaction</p>
        <?php endif; ?>
    </div>

    <div>
        <a href="/client/logout">Se déconnecter</a>
    </div>
</body>
</html>