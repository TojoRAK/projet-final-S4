<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>

<body>
    <?php if (!empty($errors)): ?>
        <div
            style="margin: 0 0 16px 0; padding: 10px 12px; border: 1px solid #f5c2c7; background: #f8d7da; color: #842029; border-radius: 8px;">
            <div><?= esc($errors) ?></div>

        </div>
    <?php endif; ?>
    <form action="/login" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="telephone">Numéro de téléphone:</label>
            <input type="text" name="telephone" id="telephone" placeholder="+2613XXXXXXXX ou 03XXXXXXXX" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>

</body>

</html>