<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobileMoney − Login</title>
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
                        <img src="<?= base_url('/assets/images/logo.png') ?>" class="w-50 mb-2">
                        <h1 class="fw-bold">Côté opérateur</h1>
                        <p class="text-body-secondary">Connectez-vous pour accéder à votre espace</p>
                    </div>

                    <form action="#" method="post" class="needs-validation">
                        <?= csrf_field() ?>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-body-secondary fw-medium mb-0">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-bold ph-envelope-simple"></i>
                                </span>
                                <input type="text" name="email" id="email" class="form-control" value="<?= old('email') ?? 'admin@root.dev' ?>">
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-4">
                            <label for="mot_de_passe" class="form-label text-body-secondary fw-medium mb-0">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph-bold ph-lock"></i>
                                </span>
                                <input type="password" name="mot_de_passe" id="mot_de_passe" class="form-control" value="<?= old('mot_de_passe') ?? '1234' ?>">
                                <button class="btn btn-outline-secondary" type="button" id="btn_mot_de_passe">
                                    <i class="ph-bold ph-eye"></i>
                                </button>
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

<script>
    const btn_mot_de_passe = document.querySelector('#btn_mot_de_passe');
    const input_mot_de_passe = document.querySelector('#mot_de_passe');
    const icone_btn_mot_de_passe = document.querySelector('#btn_mot_de_passe i');

    btn_mot_de_passe.addEventListener('click', function () {
        // Vérifie si le champ est actuellement un mot de passe
        const type = input_mot_de_passe.getAttribute('type');
        if (type === 'text') {
            input_mot_de_passe.setAttribute('type', 'password');
            icone_btn_mot_de_passe.className = 'ph-bold ph-eye';
        } else {
            input_mot_de_passe.setAttribute('type', 'text');
            icone_btn_mot_de_passe.className = 'ph-bold ph-eye-slash';
        }
    });
</script>

</body>
</html>
