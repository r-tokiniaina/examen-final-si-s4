<?php

$solde = model('SoldeModel')->find(session()->get('client')['numero'])['montant'] ?? 0;
$epargne = model('EpargneModel')->findEpargneByNumero(session()->get('client')['numero']);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client − <?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="<?= base_url('/assets/icons/bold/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/libs/bootstrap/bootstrap.css') ?>">
    <?= $this->renderSection('css') ?>
</head>
<body class="bg-light">

    <!-- Gestion des messages flash -->
    <div class="toast-container position-fixed top-0 start-50 translate-middle-x mt-4">
        <?php if (session()->getFlashdata('message_erreur') || session()->getFlashdata('error')): ?>
            <div class="toast show text-bg-danger">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ph-bold ph-seal-warning mx-1"></i>
                        <span class="fw-semibold"><?= session()->getFlashdata('message_erreur') ?? session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" class="btn-close btn-close-white m-auto me-3" data-bs-dismiss="toast"></button>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('message_succes') || session()->getFlashdata('success')): ?>
            <div class="toast show text-bg-success">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="ph-bold ph-seal-check mx-1"></i>
                        <span class="fw-semibold"><?= session()->getFlashdata('message_succes') ?? session()->getFlashdata('success') ?></span>
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

                    <!-- Sidebar Header avec Logo -->
                    <div class="d-flex align-items-center justify-content-between mb-4 border-bottom border-secondary pb-3">
                        <span class="fs-5 fw-bold d-flex align-items-center gap-2">
                            <img src="<?= base_url('/assets/images/logo.png') ?>" alt="Logo" style="max-height: 32px; width: auto;">
                            <span>Côté Client</span>
                        </span>
                        <button class="btn btn-sm text-white d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <i class="ph-bold ph-x fs-4"></i>
                        </button>
                    </div>

                    <!-- Navigation Links -->
                    <ul class="nav nav-pills flex-column mb-auto gap-1">
                        <li class="nav-item">
                            <a href="<?= base_url('client/operations') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('client/operations*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-swap fs-5"></i> Opérations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('client/epargnes') ?>" class="nav-link text-white d-flex align-items-center gap-2 <?= url_is('client/epargnes*') ? 'active' : '' ?>">
                                <i class="ph-bold ph-coins fs-5"></i> Épargnes
                            </a>
                        </li>
                    </ul>

                    <!-- Sidebar Footer (Montant au-dessus de la ligne) -->
                    <div class="mt-auto pt-3">

                        <!-- Montant / Solde (AU-DESSUS de la ligne) -->
                        <div class="d-flex align-items-center gap-2 fw-bold fs-5 mb-3 px-1" style="color: #20c997;">
                            <i class="ph-bold ph-coins text-warning fs-4"></i>
                            <span><?= number_format($solde, 0, ',', ' ') ?> Ar</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3 px-1">
                            <i class="ph-bold ph-coins text-warning fs-4"></i>
                            <span>Épargne : <?= number_format($epargne, 0, ',', ' ') ?> Ar</span>
                        </div>

                        <!-- Ligne de séparation blanche / claire -->
                        <div class="border-top border-secondary pt-3">

                            <!-- Numéro & Déconnexion (EN DESSOUS de la ligne) -->
                            <div class="d-flex align-items-center justify-content-between px-1">
                                <div class="d-flex align-items-center gap-2 text-white fw-semibold">
                                    <i class="ph-bold ph-phone text-primary fs-5"></i>
                                    <span><?= esc(session()->get('client')['numero']) ?></span>
                                </div>
                                <a href="<?= base_url('logout') ?>"
                                   class="btn btn-sm btn-outline-danger border-0 p-1 d-flex align-items-center justify-content-center"
                                   title="Déconnexion"
                                   style="width: 32px; height: 32px; border-radius: 8px;">
                                    <i class="ph-bold ph-sign-out fs-5"></i>
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container-fluid py-4">
                    <?= $this->renderSection('content') ?>
                </div>
            </main>

        </div>
    </div>

    <script src="<?= base_url('/assets/libs/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
