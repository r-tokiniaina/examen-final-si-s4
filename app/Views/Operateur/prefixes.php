<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Gestion des Préfixes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- En-tête -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h2">Gestion des Préfixes</h1>
    <!-- Bouton d'ajout -->
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2"
            data-bs-toggle="modal"
            data-bs-target="#prefixModal"
            data-mode="add"
            data-action="<?= base_url('operateur/prefixes/new') ?>">
        <i class="ph-bold ph-plus"></i> Nouveau préfixe
    </button>
</div>

<!-- Sélecteur d’opérateur -->
<ul class="nav nav-tabs mb-4" id="typeTab">
    <?php foreach ($operateurs as $operateur): ?>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 fw-bold text-uppercase px-4 <?= $operateur['id'] == $id_operateur ? 'active' : '' ?>"
              href="<?= base_url('operateur/prefixes?operateur=' . $operateur['id']) ?>">
                  <i class="ph-bold ph-buildings"></i> <?= $operateur['libelle'] ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Liste des préfixes configurés</h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Préfixe</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prefixes as $prefixe): ?>
                            <tr>
                                <td><span class="badge bg-secondary fs-6 px-3 py-2"><?= $prefixe['valeur'] ?></span></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#prefixModal"
                                                data-mode="edit"
                                                data-value="<?= $prefixe['valeur'] ?>"
                                                data-action="<?= base_url('operateur/prefixes/' . $prefixe['id'] . '/update') ?>"
                                                title="Modifier">
                                            <i class="ph-bold ph-pencil"></i>
                                        </button>
                                        <a href="<?= base_url('operateur/prefixes/' . $prefixe['id'] . '/delete') ?>" class="btn btn-outline-danger" onclick="return confirm('Supprimer ce préfixe ?')" title="Supprimer">
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
<div class="modal fade" id="prefixModal" tabindex="-1" aria-labelledby="prefixModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prefixModalLabel">Préfixe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="prefixForm" action="" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id_operateur" value="<?= $id_operateur ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="prefix_value" class="form-label">Code préfixe</label>
                        <input type="text" class="form-control" id="prefix_value" name="valeur" placeholder="Ex: 033" required>
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
    const prefixModal = document.getElementById('prefixModal');

    if (prefixModal) {
        prefixModal.addEventListener('show.bs.modal', function (event) {
            // Bouton qui a déclenché l'ouverture
            const button = event.relatedTarget;

            // Extraction des données des attributs data-*
            const mode = button.getAttribute('data-mode');
            const actionUrl = button.getAttribute('data-action');
            const value = button.getAttribute('data-value') || '';

            // Éléments de la modale à modifier
            const modalTitle = prefixModal.querySelector('.modal-title');
            const prefixForm = prefixModal.querySelector('#prefixForm');
            const inputField = prefixModal.querySelector('#prefix_value');
            const submitBtn = prefixModal.querySelector('#submitBtn');
            const submitBtnText = submitBtn.querySelector('span');
            const submitBtnIcon = submitBtn.querySelector('i');

            // Mise à jour de l'action du formulaire et de la valeur de l'input
            prefixForm.setAttribute('action', actionUrl);
            inputField.value = value;

            // Personnalisation visuelle selon le mode (Ajout ou Modification)
            if (mode === 'add') {
                modalTitle.textContent = 'Ajouter un préfixe';
                submitBtnText.textContent = 'Ajouter';
                submitBtn.className = 'btn btn-primary d-flex align-items-center gap-2';
                submitBtnIcon.className = 'ph-bold ph-plus';
            } else {
                modalTitle.textContent = 'Modifier le préfixe';
                submitBtnText.textContent = 'Enregistrer';
                submitBtn.className = 'btn btn-warning d-flex align-items-center gap-2';
                submitBtnIcon.className = 'ph-bold ph-check';
            }
        });
    }
</script>
<?= $this->endSection() ?>
