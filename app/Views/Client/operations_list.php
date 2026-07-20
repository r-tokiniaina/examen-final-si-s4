<?= $this->extend('Client/layout') ?>

<?= $this->section('title') ?>
Historique des opérations
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- En-tête de la page -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1">Historique des opérations</h1>
        <p class="text-muted mb-0">Consultez l'ensemble de vos dépôts, retraits et transferts</p>
    </div>
    <!-- Bouton d'ouverture du Popup -->
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm fw-medium"
            data-bs-toggle="modal"
            data-bs-target="#operationModal">
        <i class="ph-bold ph-plus-circle fs-5"></i>
        <span>Nouvelle opération</span>
    </button>
</div>

<!-- Messages Flash (Succès / Erreur) -->
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

<!-- Carte contenant le tableau -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (empty($operations)): ?>
            <!-- État vide si aucune opération -->
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
            <!-- Tableau responsive -->
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
                                <!-- Date basée sur date_operation -->
                                <td class="ps-4 text-body-secondary fw-medium">
                                    <?= date('d/m/Y H:i', strtotime($op['date_operation'])) ?>
                                </td>

                                <!-- Badge du type d'opération -->
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

                                <!-- Numéro Source -->
                                <td class="fw-medium">
                                    <?= !empty($op['num_source']) ? esc($op['num_source']) : '<span class="text-muted">−</span>' ?>
                                </td>

                                <!-- Numéro Destination -->
                                <td class="fw-medium">
                                    <?= !empty($op['num_destination']) ? esc($op['num_destination']) : '<span class="text-muted">−</span>' ?>
                                </td>

                                <!-- Montant -->
                                <td class="text-end fw-bold">
                                    <?= number_format($op['montant'], 0, ',', ' ') ?> Ar
                                </td>

                                <!-- Frais -->
                                <td class="text-end text-muted">
                                    <?= number_format($op['frais'], 0, ',', ' ') ?> Ar
                                </td>

                                <!-- Total (Montant + Frais) -->
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

<!-- ========================================== -->
<!-- MODALE POP-UP : NOUVELLE OPÉRATION         -->
<!-- ========================================== -->
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
                    <!-- Sélection du type -->
                    <div class="mb-3">
                        <label for="type" class="form-label fw-semibold text-secondary">Type d'opération</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="" disabled selected>Choisissez le type...</option>
                            <option value="1">Dépôt</option>
                            <option value="2">Retrait</option>
                            <option value="3">Transfert</option>
                        </select>
                    </div>

                    <!-- Numéro de destination -->
                    <div class="mb-3">
                        <label for="num_destination" class="form-label fw-semibold text-secondary">Numéro de destination</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-phone"></i></span>
                            <input type="text" class="form-control" id="num_destination" name="num_destination" placeholder="Ex: 033 11 223 34">
                        </div>
                        <div class="form-text text-muted">Optionnel en cas de retrait simple.</div>
                    </div>

                    <!-- Montant -->
                    <div class="mb-3">
                        <label for="montant" class="form-label fw-semibold text-secondary">Montant (Ar)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-coins"></i></span>
                            <input type="number" class="form-control" id="montant" name="montant" placeholder="Ex: 10000" min="100" step="50" required>
                            <span class="input-group-text bg-light fw-bold text-secondary">Ar</span>
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

<?= $this->endSection() ?>
