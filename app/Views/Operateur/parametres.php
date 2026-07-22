<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Paramètres
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1">Paramètres</h1>
        <p class="text-muted mb-0">Paramétrez les promotions.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <form action="<?= base_url('/operateur/promotions/update') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="modal-body p-4">
              <div class="mb-3" id="wrapper_num_destination">
                  <label for="promotion" class="form-label fw-semibold text-secondary">Taux de promotion sur les transferts vers même opérateur</label>
                  <div class="input-group">
                      <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-coins"></i></span>
                      <input type="number" min="0" max="100" step="0.01" class="form-control" id="promotion" name="promotion" value="<?= $promotion ?>">
                      <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-percent"></i></span>
                  </div>
              </div>
          </div>

          <div class="modal-footer border-top bg-light">
              <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 shadow-sm fw-medium">
                  <i class="ph-bold ph-check-circle"></i>
                  <span>Modifier</span>
              </button>
          </div>
      </form>
    </div>
</div>

<?= $this->endSection() ?>
