<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Opération</title>
</head>
<body>
    <h1>Nouvelle Opération</h1>

    <?php if (session()->getFlashdata('success')): ?>
        <p><?= esc(session()->getFlashdata('success')) ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <p><?= esc(session()->getFlashdata('errors')) ?></p>
    <?php endif; ?>

    <form action="/client/operation" method="post">
        <?= csrf_field() ?>

        <div>
            <label for="montant">Montant</label>
            <input type="number" name="montant" id="montant" min="1" required>
        </div>

        <div>
            <label for="type_operation">Type d'opération</label>
            <select name="type_operation" id="type_operation" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= esc($type->id) ?>" data-libelle="<?= esc($type->libelle) ?>">
                        <?= esc(ucfirst($type->libelle)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="beneficiaire-field" style="display: none;">
            <label for="tel_beneficiaire">Numéro bénéficiaire</label>
            <input type="text" name="tel_beneficiaire" id="tel_beneficiaire" placeholder="+2613XXXXXXXX ou 03XXXXXXXX">
        </div>

        <button type="submit">Valider</button>
    </form>

    <script>
        const typeSelect = document.getElementById('type_operation');
        const beneficiaireField = document.getElementById('beneficiaire-field');
        const beneficiaireInput = document.getElementById('tel_beneficiaire');

        typeSelect.addEventListener('change', function () {
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            const isTransfert = selectedOption.dataset.libelle === 'transfert';

            beneficiaireField.style.display = isTransfert ? 'block' : 'none';
            beneficiaireInput.required = isTransfert;

            if (!isTransfert) {
                beneficiaireInput.value = '';
            }
        });
    </script>
</body>
</html>