<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Comptes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- En-tête -->
<div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
    <h1 class="h2">Situation des comptes</h1>
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
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Numéro</th>
                            <th>Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($soldes as $solde): ?>
                            <tr>
                                <td><?= $solde['numero'] ?></td>
                                <td><?= number_format($solde['montant'], 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
