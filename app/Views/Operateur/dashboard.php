<?= $this->extend('Operateur/layout') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<script src="<?= base_url('/assets/libs/chartjs/chart.umd.min.js') ?>"></script>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="border-bottom pb-2 mb-4">
    <h1 class="h2">Tableau de bord</h1>
</div>

<!-- Grille de cartes d'indicateurs rapides -->
<!--<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1">Total Retraits</p>
                    <h3 class="mb-0 fw-bold">1,245</h3>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded">
                    <i class="ph-bold ph-money fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1">Total Transferts</p>
                    <h3 class="mb-0 fw-bold">842</h3>
                </div>
                <div class="bg-info bg-opacity-10 text-info p-3 rounded">
                    <i class="ph-bold ph-arrows-left-right fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted mb-1">Préfixes Actifs</p>
                    <h3 class="mb-0 fw-bold">12</h3>
                </div>
                <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded">
                    <i class="ph-bold ph-tag fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>-->

<!-- Grille des graphiques Chart.js -->
<div class="row g-4">

    <!-- Graphique en Barres (2 couleurs) -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Gains via les frais</h5>
            <div style="position: relative; height:300px;">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Graphique en Courbe -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Nombre de clients</h5>
            <div style="position: relative; height:300px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>

</div>

<script>
    // Configuration commune pour rendre les graphiques responsives
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                stacked: true,
                grid: {
                    color: '#f1f5f9'
                }
            },
            x: {
                stacked: true,
                grid: {
                    display: false
                }
            }
        }
    };

    // 1. Graphique en Barres avec 2 couleurs distinctes
    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            datasets: [
                {
                    label: 'Frais de retrait',
                    data: <?= json_encode($frais_retrait_par_jour) ?>,
                    backgroundColor: '#0d6efd', // Bleu de Bootstrap
                    borderRadius: 4
                },
                {
                    label: 'Frais de transfert',
                    data: <?= json_encode($frais_transfert_par_jour) ?>,
                    backgroundColor: '#0dcaf0', // Turquoise/Info de Bootstrap
                    borderRadius: 4
                }
            ]
        },
        options: chartOptions
    });

    // 2. Graphique en Courbe (Line Chart)
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
            datasets: [{
                label: 'Nb. de clients',
                data: <?= json_encode($nb_clients_par_jour) ?>,
                borderColor: '#198754', // Vert de Bootstrap
                backgroundColor: 'rgba(25, 135, 84, 0.1)', // Légère teinte de fond sous la courbe
                borderWidth: 3,
                tension: 0.3, // Arrondit les angles de la courbe
                fill: true
            }]
        },
        options: chartOptions
    });
</script>
<?= $this->endSection() ?>
