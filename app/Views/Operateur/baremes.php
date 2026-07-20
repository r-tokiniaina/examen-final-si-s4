<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Barèmes de frais
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- En-tête -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h2">Gestion des Barèmes de frais</h1>
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
            data-bs-toggle="modal"
            data-bs-target="#baremeModal"
            data-mode="add"
            data-action="<?= base_url('operateur/baremes/new') ?>">
        <i class="ph-bold ph-plus"></i> Nouveau barème
    </button>
</div>

<!-- Sélecteur de type (Retrait / Transfert) -->
<ul class="nav nav-tabs mb-4" id="typeTab">
    <?php foreach ($types_operations as $type_operation): ?>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 fw-bold text-uppercase px-4 <?= $type_operation['id'] == $id_type_operation ? 'active' : '' ?>"
              href="<?= base_url('operateur/baremes?type=' . $type_operation['id']) ?>">
                <i class="ph-bold ph-arrows-left-right"></i> <?= $type_operation['libelle'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Liste des barèmes configurés</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Montant Minimum</th>
                            <th>Montant Maximum</th>
                            <th>Frais</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($baremes_frais as $bareme): ?>
                            <tr>
                                <td><?= number_format($bareme['montant_min'], 0, '.', ' ') ?> Ar</td>
                                <td><?= number_format($bareme['montant_max'], 0, '.', ' ') ?> Ar</td>
                                <td><?= number_format($bareme['frais'], 0, '.', ' ') ?> Ar</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#baremeModal"
                                                data-mode="edit"
                                                data-min="<?= $bareme['montant_min'] ?>"
                                                data-max="<?= $bareme['montant_max'] ?>"
                                                data-frais="<?= $bareme['frais'] ?>"
                                                data-action="<?= base_url('operateur/baremes/' . $bareme['id'] . '/update') ?>"
                                                title="Modifier">
                                            <i class="ph-bold ph-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('operateur/baremes/' . $bareme['id'] . '/delete') ?>" class="btn btn-outline-danger" onclick="return confirm('Supprimer ce barème ?')" title="Supprimer">
                                            <i class="ph-bold ph-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- L'UNIQUE POP-UP (AJOUT & MODIFICATION)     -->
<!-- ========================================== -->
<div class="modal fade" id="baremeModal" tabindex="-1" aria-labelledby="baremeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="baremeModalLabel">Barème</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="baremeForm" action="" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="type_operation" value="<?= $id_type_operation ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="montant_min" class="form-label">Montant Minimum</label>
                        <input type="number" step="0.01" class="form-control" id="montant_min" name="montant_min" required>
                    </div>
                    <div class="mb-3">
                        <label for="montant_max" class="form-label">Montant Maximum</label>
                        <input type="number" step="0.01" class="form-control" id="montant_max" name="montant_max" required>
                    </div>
                    <div class="mb-3">
                        <label for="frais" class="form-label">Frais</label>
                        <input type="number" step="0.01" class="form-control" id="frais" name="frais" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary d-flex align-items-center gap-2">
                        <i></i> <span>Valider</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script d'aiguillage de la modale -->
<script>
    const baremeModal = document.getElementById('baremeModal');

    if (baremeModal) {
        baremeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Récupération de tous les attributs data-*
            const mode = button.getAttribute('data-mode');
            const actionUrl = button.getAttribute('data-action');
            const minVal = button.getAttribute('data-min') || '';
            const maxVal = button.getAttribute('data-max') || '';
            const fraisVal = button.getAttribute('data-frais') || '';

            // Sélection des éléments de la modale
            const modalTitle = baremeModal.querySelector('.modal-title');
            const baremeForm = baremeModal.querySelector('#baremeForm');
            const inputMin = baremeModal.querySelector('#montant_min');
            const inputMax = baremeModal.querySelector('#montant_max');
            const inputFrais = baremeModal.querySelector('#frais');
            const submitBtn = baremeModal.querySelector('#submitBtn');
            const submitBtnText = submitBtn.querySelector('span');
            const submitBtnIcon = submitBtn.querySelector('i');

            // Attribution de l'action du formulaire et des valeurs
            baremeForm.setAttribute('action', actionUrl);
            inputMin.value = minVal;
            inputMax.value = maxVal;
            inputFrais.value = fraisVal;

            // Ajustement visuel selon le mode
            if (mode === 'add') {
                modalTitle.textContent = 'Ajouter un barème';
                submitBtnText.textContent = 'Ajouter';
                submitBtn.className = 'btn btn-primary d-flex align-items-center gap-2';
                submitBtnIcon.className = 'ph-bold ph-plus';
            } else {
                modalTitle.textContent = 'Modifier le barème';
                submitBtnText.textContent = 'Enregistrer';
                submitBtn.className = 'btn btn-warning d-flex align-items-center gap-2';
                submitBtnIcon.className = 'ph-bold ph-check';
            }
        });
    }
</script>
<?= $this->endSection() ?>
