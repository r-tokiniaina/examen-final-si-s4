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

<!-- Tableau : Situation gain via les différents frais -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Situation gain via les différents frais</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Opération</th>
                            <th class="text-end">Opérateur</th>
                            <th class="text-end">Autres opérateurs</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        $operations = ['retrait' => 'Retrait', 'transfert' => 'Transfert'];
                        ?>
                        <?php foreach ($operations as $cle => $libelle): ?>
                            <?php
                            $montant_operateur = $situation[$cle]['operateur'] ?? 0;
                            $montant_autres    = $situation[$cle]['autres'] ?? 0;
                            $total_ligne       = $montant_operateur + $montant_autres;
                            $grand_total      += $total_ligne;
                            ?>
                            <tr>
                                <td><?= esc($libelle) ?></td>
                                <td class="text-end"><?= number_format($montant_operateur, 0, ',', ' ') ?> Ar</td>
                                <td class="text-end"><?= number_format($montant_autres, 0, ',', ' ') ?> Ar</td>
                                <td class="text-end"><?= number_format($total_ligne, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total général</td>
                            <td class="text-end"><?= number_format(($situation['retrait']['operateur'] ?? 0) + ($situation['transfert']['operateur'] ?? 0), 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format(($situation['retrait']['autres'] ?? 0) + ($situation['transfert']['autres'] ?? 0), 0, ',', ' ') ?> Ar</td>
                            <td class="text-end"><?= number_format($grand_total, 0, ',', ' ') ?> Ar</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tableau : Situation des montants à envoyer à chaque opérateur -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0 p-3">
            <h5 class="card-title mb-3">Situation des montants à envoyer à chaque opérateur</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Opérateur</th>
                            <th class="text-end">Total frais</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $grand_total_frais = 0; ?>
                        <?php $grand_total_envoi = 0; ?>
                        <?php foreach ($montants_envoyer as $row): ?>
                            <?php
                            $total_frais = (float)($row['total_frais'] ?? 0);
                            $pct = (float)($row['pct_commission'] ?? 0);
                            $montant_envoyer = $total_frais * ($pct / 100);
                            $grand_total_frais += $total_frais;
                            $grand_total_envoi += $montant_envoyer;
                            ?>
                            <tr>
                                <td><?= esc($row['libelle'] ?? 'Inconnu') ?></td>
                                <td class="text-end"><?= number_format($total_frais, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($montants_envoyer)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Aucune donnée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td>Total général</td>
                            <td class="text-end"><?= number_format($grand_total_frais, 0, ',', ' ') ?> Ar</td>
                        </tr>
                    </tfoot>
                </table>
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
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
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
