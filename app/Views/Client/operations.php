<?= $this->extend('Client/layout') ?>

<?= $this->section('title') ?>
Historique des opérations
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1">Historique des opérations</h1>
        <p class="text-muted mb-0">Consultez l'ensemble de vos dépôts, retraits et transferts</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm fw-medium"
            data-bs-toggle="modal"
            data-bs-target="#operationModal">
        <i class="ph-bold ph-plus-circle fs-5"></i>
        <span>Nouvelle opération</span>
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-20 p-3 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ph-bold ph-wallet fs-2 text-white"></i>
                    </div>
                    <div>
                        <span class="text-white-50 small d-block">Solde disponible</span>
                        <h3 class="mb-0 fw-bold"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="ph-bold ph-check-circle me-2 fs-5"></i>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="ph-bold ph-warning-circle me-2 fs-5"></i>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($operations)): ?>
            <div class="text-center py-5">
                <div class="bg-light d-inline-block p-3 rounded-circle mb-3">
                    <i class="ph-bold ph-receipt text-muted fs-1"></i>
                </div>
                <h5 class="fw-semibold">Aucune opération enregistrée</h5>
                <p class="text-muted mb-3">Vos transactions apparaîtront ici dès que vous aurez effectué la première.</p>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#operationModal">
                    Effectuer une première opération
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Type</th>
                            <th>N° Source</th>
                            <th>N° Destination</th>
                            <th class="text-end">Montant</th>
                            <th class="text-end">Frais</th>
                            <th class="pe-4 text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operations as $op): ?>
                            <tr>
                                <td class="ps-4 text-body-secondary fw-medium">
                                    <?= date('d/m/Y H:i', strtotime($op['date_operation'])) ?>
                                </td>

                                <td>
                                    <?php if ($op['type'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-semibold px-2 py-1">
                                            <i class="ph-bold ph-arrow-down-left me-1"></i>Dépôt
                                        </span>
                                    <?php elseif ($op['type'] == 2): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger fw-semibold px-2 py-1">
                                            <i class="ph-bold ph-arrow-up-right me-1"></i>Retrait
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-2 py-1">
                                            <i class="ph-bold ph-arrows-left-right me-1"></i>Transfert
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="fw-medium">
                                    <?= !empty($op['num_source']) ? esc($op['num_source']) : '<span class="text-muted">−</span>' ?>
                                </td>

                                <td class="fw-medium">
                                    <?= !empty($op['num_destination']) ? esc($op['num_destination']) : '<span class="text-muted">−</span>' ?>
                                </td>

                                <td class="text-end fw-bold">
                                    <?= number_format($op['montant'], 0, ',', ' ') ?> Ar
                                </td>

                                <td class="text-end text-muted">
                                    <?= number_format($op['frais'], 0, ',', ' ') ?> Ar
                                </td>

                                <td class="pe-4 text-end fw-bold text-primary">
                                    <?= number_format($op['montant'] + $op['frais'], 0, ',', ' ') ?> Ar
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="operationModal" tabindex="-1" aria-labelledby="operationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="operationModalLabel">
                    <i class="ph-bold ph-plus-circle text-primary me-2"></i>Nouvelle opération
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <form action="<?= base_url('/client/operations/new') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="type" class="form-label fw-semibold text-secondary">Type d'opération</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="" disabled selected>Choisissez le type...</option>
                            <option value="1">Dépôt</option>
                            <option value="2">Retrait</option>
                            <option value="3">Transfert</option>
                        </select>
                    </div>

                    <div class="mb-3" id="wrapper_num_destination">
                        <label for="num_destination" class="form-label fw-semibold text-secondary">Numéro de destination</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-phone"></i></span>
                            <input type="text" class="form-control" id="num_destination" name="num_destination" placeholder="Ex: 0341111111,0342222222">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="montant" class="form-label fw-semibold text-secondary">Montant (Ar)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-coins"></i></span>
                            <input type="number" class="form-control" id="montant" name="montant" placeholder="Ex: 10000" min="100" step="50" required>
                            <span class="input-group-text bg-light fw-bold text-secondary">Ar</span>
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="wrapper_inclure_frais">
                        <div class="form-check form-switch p-3 bg-light rounded border">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="inclure_frais" name="inclure_frais" value="1">
                            <label class="form-check-input-label fw-semibold text-secondary" for="inclure_frais">
                                Inclure les frais de retrait dans le montant inséré
                            </label>
                        </div>
                    </div>


                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Frais d'opération :</span>
                            <span class="fw-bold text-dark" id="display_frais">0 Ar</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Total prélevé/crédité :</span>
                            <span class="fw-bold text-primary" id="display_total">0 Ar</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-2 mt-2">
                            <span class="text-muted small fw-semibold">Solde restant estimé :</span>
                            <span class="fw-bold text-success" id="display_solde_restant"><?= number_format($solde ?? 0, 0, ',', ' ') ?> Ar</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 shadow-sm fw-medium">
                        <i class="ph-bold ph-check-circle"></i>
                        <span>Valider l'opération</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
const pcts_commissions = <?= json_encode($pcts_commissions ?? []) ?>;

document.addEventListener('DOMContentLoaded', function () {
    const baremes = <?= json_encode($baremes ?? []) ?>;
    const soldeActuel = <?= (float) ($solde ?? 0) ?>;
    const typeSelect = document.getElementById('type');
    const wrapperDest = document.getElementById('wrapper_num_destination');
    const inputDest = document.getElementById('num_destination');
    const inputMontant = document.getElementById('montant');
    const displayFrais = document.getElementById('display_frais');
    const displayTotal = document.getElementById('display_total');
    const displaySoldeRestant = document.getElementById('display_solde_restant');
    const wrapperInclureFrais = document.getElementById('wrapper_inclure_frais');
    const checkboxInclureFrais = document.getElementById('inclure_frais');

    function updateCalculs() {
        const typeVal = parseInt(typeSelect.value);
        const montantVal = parseFloat(inputMontant.value) || 0;

        let fraisFixes = 0;
        let commissionVariable = 0;
        let memeOperateur = true;

        if (montantVal > 0 && !isNaN(typeVal)) {
            const match = baremes.find(b =>
                Number(b.type_operation) === typeVal &&
                montantVal >= Number(b.montant_min) &&
                montantVal <= Number(b.montant_max)
            );

            if (match) {
                fraisFixes = Number(match.frais);
            } else {
                console.warn('Aucun barème trouvé pour :', { typeVal, montantVal, baremes });
            }
            if (typeVal === 3) {
                const telNettoye = inputDest.value.replace(/\s+/g, '');
                const prefixe = telNettoye.substring(0, 3);

                // Vérifie si ce préfixe possède une commission dédiée dans pcts_commissions
                if (prefixe && pcts_commissions[prefixe] !== undefined) {
                    const pourcentage = parseFloat(pcts_commissions[prefixe]) || 0;
                    fraisFixes = 0;
                    commissionVariable = montantVal * (pourcentage / 100);
                    memeOperateur = false;
                }
            }
        }

        const totalFrais = fraisFixes + commissionVariable;
        let total = montantVal;

        if ((typeVal === 2 || (typeVal === 3 && memeOperateur)) && checkboxInclureFrais.checked) {
            total = montantVal;
        } else if (typeVal === 2 || typeVal === 3) {
            total = montantVal + totalFrais;
        }

        let soldeRestant = soldeActuel;

        if (typeVal === 1) {
            soldeRestant += montantVal;
        } else if (typeVal === 2 || typeVal === 3) {
            soldeRestant -= total;
        }

        displayFrais.textContent = new Intl.NumberFormat('fr-FR').format(totalFrais) + ' Ar';
        displayTotal.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' Ar';
        displaySoldeRestant.textContent = new Intl.NumberFormat('fr-FR').format(soldeRestant) + ' Ar';

        if (soldeRestant < 0) {
            displaySoldeRestant.className = 'fw-bold text-danger';
        } else {
            displaySoldeRestant.className = 'fw-bold text-success';
        }

        if (typeVal === 2 || (typeVal === 3 && memeOperateur)) {
            wrapperInclureFrais.classList.remove('d-none');
        } else {
            wrapperInclureFrais.classList.add('d-none');
            checkboxInclureFrais.checked = false; // Décoche si les conditions ne sont plus remplies
        }

    }

    typeSelect.addEventListener('change', function () {
        if (this.value === '3') {
            wrapperDest.classList.remove('d-none');
            inputDest.required = true;
        } else {
            wrapperDest.classList.add('d-none');
            inputDest.required = false;
            inputDest.value = '';
        }
        updateCalculs();
    });

    inputMontant.addEventListener('input', updateCalculs);
    inputDest.addEventListener('input', updateCalculs);
    checkboxInclureFrais.addEventListener('change', updateCalculs);

    updateCalculs();
});
</script>

<?= $this->endSection() ?>
