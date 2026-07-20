<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobileMoney − Espace Client</title>
    <link rel="stylesheet" href="<?= base_url('/assets/icons/bold/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/libs/bootstrap/bootstrap.css') ?>">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center justify-content-center">

<!-- Gestion des messages flash -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x mt-4">
    <?php if (session()->getFlashdata('message_erreur')): ?>
        <div class="toast show text-bg-danger">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ph-bold ph-seal-warning mx-1"></i>
                    <span class="fw-semibold"><?= session()->getFlashdata('message_erreur') ?></span>
                </div>
                <button type="button" class="btn-close btn-close-white m-auto me-3" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('message_succes')): ?>
        <div class="toast show text-bg-success">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ph-bold ph-seal-check mx-1"></i>
                    <span class="fw-semibold"><?= session()->getFlashdata('message_succes') ?></span>
                </div>
                <button type="button" class="btn-close btn-close-white m-auto me-3" data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4">
            <div class="card shadow-sm p-4">
                <div class="card-body">

                    <!-- Logo d'en-tête -->
                    <div class="text-center mb-4">
                        <img src="<?= base_url('/assets/images/logo.png') ?>" class="w-50 mb-2" alt="Logo">
                        <h1 class="fw-bold">Espace Client</h1>
                        <p class="text-body-secondary">Saisissez votre numéro pour vous connecter</p>
                    </div>

                    <!-- Le formulaire pointe vers la route POST /login -->
                    <form action="<?= base_url('/login') ?>" method="post" class="needs-validation">
                        <?= csrf_field() ?>

                        <!-- Numéro de téléphone -->
                        <div class="mb-4">
                            <label for="numero" class="form-label text-body-secondary fw-medium mb-0">Numéro de téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <!-- J'ai changé l'icône email par une icône téléphone -->
                                    <i class="ph-bold ph-phone"></i>
                                </span>
                                <!-- Champ adapté au numéro -->
                                <input type="text" name="numero" id="numero" class="form-control" placeholder="ex: 033 12 345 67" value="<?= old('numero') ?? '033 12 345 67' ?>" required>
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ph-bold ph-sign-in mx-1"></i>
                            Se connecter
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('/assets/libs/bootstrap/bootstrap.bundle.min.js') ?>"></script>

</body>
</html>
