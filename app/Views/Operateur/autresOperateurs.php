<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Autres opérateurs
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- En-tête -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h2">Gestion des Autres opérateurs</h1>
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
            data-bs-toggle="modal"
            data-bs-target="#operateurModal"
            data-mode="add"
            data-action="<?= base_url('operateur/autres-operateurs/new') ?>">
        <i class="ph-bold ph-plus"></i> Nouvel opérateur
    </button>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Liste des autres opérateurs</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>% de commission</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operateurs as $operateur): ?>
                            <tr>
                                <td><?= $operateur['libelle'] ?></td>
                                <td><?= number_format($operateur['pct_commission'], 2, ',', ' ') ?> %</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#operateurModal"
                                                data-mode="edit"
                                                data-libelle="<?= $operateur['libelle'] ?>"
                                                data-commission="<?= $operateur['pct_commission'] ?>"
                                                data-action="<?= base_url('operateur/autres-operateurs/' . $operateur['id'] . '/update') ?>"
                                                title="Modifier">
                                            <i class="ph-bold ph-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('operateur/autres-operateurs/' . $operateur['id'] . '/delete') ?>" class="btn btn-outline-danger" onclick="return confirm('Supprimer cet opérateur ?')" title="Supprimer">
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
<div class="modal fade" id="operateurModal" tabindex="-1" aria-labelledby="operateurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="operateurModalLabel">Opérateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="baremeForm" action="" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="libelle" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="libelle" name="libelle" required>
                    </div>
                    <div class="mb-3">
                        <label for="pct_commission" class="form-label">Taux de commission</label>
                        <input type="number" step="0.01" min="0" max="100" class="form-control" id="pct_commission" name="pct_commission" required>
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
    const operateurModal = document.getElementById('operateurModal');

    if (operateurModal) {
        operateurModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Récupération de tous les attributs data-*
            const mode = button.getAttribute('data-mode');
            const actionUrl = button.getAttribute('data-action');
            const libelleVal = button.getAttribute('data-libelle') || '';
            const commissionVal = button.getAttribute('data-commission') || '';

            // Sélection des éléments de la modale
            const modalTitle = operateurModal.querySelector('.modal-title');
            const baremeForm = operateurModal.querySelector('#baremeForm');
            const inputLibelle = operateurModal.querySelector('#libelle');
            const inputCommission = operateurModal.querySelector('#pct_commission');
            const submitBtn = operateurModal.querySelector('#submitBtn');
            const submitBtnText = submitBtn.querySelector('span');
            const submitBtnIcon = submitBtn.querySelector('i');

            // Attribution de l'action du formulaire et des valeurs
            baremeForm.setAttribute('action', actionUrl);
            inputLibelle.value = libelleVal;
            inputCommission.value = commissionVal;

            // Ajustement visuel selon le mode
            if (mode === 'add') {
                modalTitle.textContent = 'Ajouter un opérateur';
                submitBtnText.textContent = 'Ajouter';
                submitBtn.className = 'btn btn-primary d-flex align-items-center gap-2';
                submitBtnIcon.className = 'ph-bold ph-plus';
            } else {
                modalTitle.textContent = 'Modifier l’opérateur';
                submitBtnText.textContent = 'Enregistrer';
                submitBtn.className = 'btn btn-warning d-flex align-items-center gap-2';
                submitBtnIcon.className = 'ph-bold ph-check';
            }
        });
    }
</script>
<?= $this->endSection() ?>
