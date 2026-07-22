<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Opérateur − <?= $this->renderSection('title') ?></title>
  <link rel="stylesheet" href="<?= base_url('/assets/icons/bold/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('/assets/libs/bootstrap/bootstrap.css') ?>">
  <?= $this->renderSection('head') ?>
</head>
<body class="bg-light">

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

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar Navigation -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-dark text-white collapse min-vh-100 p-0" id="sidebarMenu">
                <div class="d-flex flex-column h-100 p-3">

                    <!-- Sidebar Header -->
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary pb-3">
                        <span class="fs-5 fw-bold d-flex align-items-center gap-2">
                            <img src="<?= base_url('/assets/images/logo.png') ?>" alt="Logo" style="max-height: 32px; width: auto;">
                            <span>Côté Opérateur</span>
                        </span>
                        <button class="btn btn-sm text-white d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <i class="ph-bold ph-x fs-4"></i>
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <ul class="nav nav-pills flex-column mb-auto gap-1">
                        <li class="nav-item">
                            <a href="<?= base_url('operateur/dashboard') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('operateur/dashboard*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-chart-pie-slice fs-5"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('operateur/comptes') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('operateur/comptes*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-users-three fs-5"></i> Comptes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('operateur/autres-operateurs') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('operateur/autres-operateurs*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-buildings fs-5"></i> Autres opérateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('operateur/prefixes') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('operateur/prefixes*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-hash fs-5"></i> Préfixes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('operateur/baremes') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('operateur/baremes*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-scales fs-5"></i> Barèmes de frais
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('operateur/parametres') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('operateur/parametres*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-gear fs-5"></i> Paramètres
                            </a>
                        </li>
                    </ul>

                    <!-- Sidebar Footer -->
                    <div class="border-top border-secondary pt-3">
                        <a href="<?= base_url('logout') ?>" class="nav-link text-danger d-flex align-items-center gap-2">
                            <i class="ph-bold ph-sign-out fs-5"></i> Déconnexion
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <!-- Dynamic View Area -->
                <div class="container-fluid py-4">
                    <?= $this->renderSection('content') ?>
                </div>

            </main>
        </div>
    </div>

    <script src="<?= base_url('/assets/libs/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
