<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="stylesheet" href="<?= base_url('/assets/icons/bold/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('/assets/libs/bootstrap/bootstrap.css') ?>">
    <?= $this->renderSection('css') ?>
</head>
<body class="bg-light h-100">

    <div class="d-flex h-100">
        <!-- Sidebar avec un thème sombre doux et moderne -->
        <nav class="bg-dark text-secondary p-3 d-flex flex-column h-100" style="width: 260px;">
            <!-- Brand / Logo -->
            <a href="#" class="d-flex align-items-center text-white text-decoration-none py-2 px-3 mb-4">
                <i class="ph-bold ph-aperture me-3 fs-3 text-primary"></i>
                <span class="fw-bold tracking-tight">KUBIX ADMIN</span>
            </a>

            <!-- Navigation -->
            <ul class="nav nav-pills flex-column gap-1 mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active d-flex align-items-center py-2.5 px-3">
                        <i class="ph-fill ph-chart-pie-slice me-3 fs-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-secondary link-light d-flex align-items-center py-2.5 px-3">
                        <i class="ph-bold ph-users-three me-3 fs-5"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-secondary link-light d-flex align-items-center py-2.5 px-3">
                        <i class="ph-bold ph-package me-3 fs-5"></i>
                        <span>Produits</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-secondary link-light d-flex align-items-center py-2.5 px-3">
                        <i class="ph-bold ph-sliders-horizontal me-3 fs-5"></i>
                        <span>Paramètres</span>
                    </a>
                </li>
            </ul>

            <!-- Footer Sidebar / Profil rapide -->
            <div class="border-top border-secondary pt-3 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="ph-bold ph-user-circle fs-3 text-white"></i>
                    <span class="small text-white fw-medium">Alex M.</span>
                </div>
                <a href="#" class="text-secondary link-danger"><i class="ph-bold ph-sign-out fs-5"></i></a>
            </div>
        </nav>

        <!-- Zone de Contenu Principal fluide -->
        <div class="flex-grow-1 d-flex flex-column overflow-auto">
            <!-- Topbar discrète pour aérer le haut -->
            <header class="navbar navbar-light bg-white border-bottom px-4 py-3 justify-content-end">
                <div class="text-muted small"><?= date('d M Y') ?></div>
            </header>

            <main class="p-4">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="<?= base_url('/assets/libs/bootstrap/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
