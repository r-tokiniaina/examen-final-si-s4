<?= $this->extend('Client/layout') ?>

<?= $this->section('title') ?>
Épargnes
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-1">Épargnes</h1>
        <p class="text-muted mb-0">Consultez votre taux d’épargne</p>
    </div>
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
                        <span class="text-white-50 small d-block">Épargne actuel</span>
                        <h3 class="mb-0 fw-bold"><?= number_format($epargne ?? 0, 0, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <form action="<?= base_url('/client/epargnes/update') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="modal-body p-4">
              <div class="mb-3" id="wrapper_num_destination">
                  <label for="pct_epargne" class="form-label fw-semibold text-secondary">Taux d’épargne</label>
                  <div class="input-group">
                      <span class="input-group-text bg-light text-muted"><i class="ph-bold ph-coins"></i></span>
                      <input type="number" min="0" max="100" step="0.01" class="form-control" id="pct_epargne" name="pct_epargne" value="<?= $client['pct_epargne'] ?>">
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
