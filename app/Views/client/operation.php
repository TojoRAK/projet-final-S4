<?php
$space = 'client';
$title = 'Nouvelle opération';
$this->extend('layouts/main');
?>

<?php $this->section('content') ?>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form action="/client/operation" method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Montant</label>
                    <input type="number" name="montant" id="montant" class="form-control" min="1" placeholder="0" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type d'opération</label>
                    <select name="type_operation" id="type_operation" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($types as $type): ?>
                            <option value="<?= esc($type->id) ?>" data-libelle="<?= esc($type->libelle) ?>">
                                <?= esc(ucfirst($type->libelle)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12" id="beneficiaire-field" style="display: none;">
                    <label class="form-label">Numéro(s) bénéficiaire(s)</label>
                    <div id="beneficiaires-list">
                        <div class="input-group mb-2 beneficiaire-row">
                            <input type="text" name="tel_beneficiaire[]" class="form-control beneficiaire-input" placeholder="+2613XXXXXXXX ou 03XXXXXXXX">
                        </div>
                    </div>
                    <button type="button" id="btn-add-beneficiaire" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-plus-lg"></i> Ajouter un bénéficiaire
                    </button>
                </div>

                <div class="col-12" id="payer-frais-field" style="display: none;">
                    <div class="form-check">
                        <input type="checkbox" name="payer_frais" value="1" id="payer_frais" class="form-check-input">
                        <label class="form-check-label" for="payer_frais">
                            Payer les frais de retrait du bénéficiaire
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Valider</button>
                <a href="<?= base_url('client/dashboard') ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
    const typeSelect = document.getElementById('type_operation');
    const beneficiaireField = document.getElementById('beneficiaire-field');
    const beneficiairesList = document.getElementById('beneficiaires-list');
    const btnAddBeneficiaire = document.getElementById('btn-add-beneficiaire');
    const payerFraisField = document.getElementById('payer-frais-field');
    const payerFraisInput = document.getElementById('payer_frais');

    function resetBeneficiaires() {
        beneficiairesList.querySelectorAll('.beneficiaire-row').forEach(function (row, index) {
            if (index === 0) {
                row.querySelector('.beneficiaire-input').value = '';
            } else {
                row.remove();
            }
        });
    }

    typeSelect.addEventListener('change', function () {
        const selectedOption = typeSelect.options[typeSelect.selectedIndex];
        const isTransfert = selectedOption.dataset.libelle === 'transfert';
        const firstInput = beneficiairesList.querySelector('.beneficiaire-input');

        beneficiaireField.style.display = isTransfert ? 'block' : 'none';
        firstInput.required = isTransfert;
        payerFraisField.style.display = isTransfert ? 'block' : 'none';

        if (!isTransfert) {
            resetBeneficiaires();
            payerFraisInput.checked = false;
        }
    });

    btnAddBeneficiaire.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'input-group mb-2 beneficiaire-row';
        row.innerHTML = `
            <input type="text" name="tel_beneficiaire[]" class="form-control beneficiaire-input" placeholder="+2613XXXXXXXX ou 03XXXXXXXX">
            <button type="button" class="btn btn-outline-danger btn-remove-beneficiaire"><i class="bi bi-dash-lg"></i></button>
        `;
        beneficiairesList.appendChild(row);
    });

    beneficiairesList.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.btn-remove-beneficiaire');
        if (removeBtn) {
            removeBtn.closest('.beneficiaire-row').remove();
        }
    });
</script>
<?php $this->endSection() ?>
