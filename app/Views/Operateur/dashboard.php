<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="border-bottom pb-2 mb-4">
    <h1 class="h2">Tableau de bord</h1>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1">Préfixes</p>
                    <h3 class="mb-0 fw-bold">1,245</h3>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                    <i class="ph-bold ph-tag fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1">Barèmes de frais</p>
                    <h3 class="mb-0 fw-bold">18</h3>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                    <i class="ph-bold ph-scales fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
