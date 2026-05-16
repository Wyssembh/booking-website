<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Reservation</div>
        <div class="content">
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-label">Total reservations</div>
                    <div class="kpi-value"><?= (int) ($stats['total'] ?? 0) ?></div>
                </div>
                <div class="kpi-card green">
                    <div class="kpi-label">Confirmees</div>
                    <div class="kpi-value"><?= (int) ($stats['confirmees'] ?? 0) ?></div>
                </div>
                <div class="kpi-card orange">
                    <div class="kpi-label">En attente</div>
                    <div class="kpi-value"><?= (int) ($stats['en_attente'] ?? 0) ?></div>
                </div>
                <div class="kpi-card red">
                    <div class="kpi-label">Annulees</div>
                    <div class="kpi-value"><?= (int) ($stats['annulees'] ?? 0) ?></div>
                </div>
                <div class="kpi-card gold">
                    <div class="kpi-label">Revenus confirmes</div>
                    <div class="kpi-value"><?= number_format((float) ($stats['revenus'] ?? 0), 0, ',', ' ') ?> EUR</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="card">
                    <div class="chart-title">Reservations par mois</div>
                    <canvas id="chartMois" height="100"></canvas>
                </div>
                <div class="card">
                    <div class="chart-title">Repartition par hotel</div>
                    <canvas id="chartHotel" height="200"></canvas>
                </div>
            </div>

            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h3 style="margin: 0; color: #0f3460;">Toutes les reservations</h3>
                    <input type="text" class="search-input" id="searchInput" placeholder="Rechercher client, hotel...">
                </div>
                <table id="reservTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Hotel</th>
                            <th>Chambre</th>
                            <th>Arrivee</th>
                            <th>Depart</th>
                            <th>Pers.</th>
                            <th>Total</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td><?= (int) $r['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars((string) ($r['prenom'] . ' ' . $r['nom'])) ?></strong><br>
                                <small style="color:#999"><?= htmlspecialchars((string) $r['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars((string) $r['hotel']) ?></td>
                            <td><?= htmlspecialchars((string) $r['chambre']) ?></td>
                            <td><?= date('d/m/Y', strtotime((string) $r['date_arrivee'])) ?></td>
                            <td><?= date('d/m/Y', strtotime((string) $r['date_depart'])) ?></td>
                            <td><?= (int) $r['nb_personnes'] ?></td>
                            <td><?= number_format((float) $r['prix_total'], 0) ?> EUR</td>
                            <td>
                                <form method="POST" class="statut-form" style="display:flex;align-items:center;" action="<?= htmlspecialchars(app_url('dashboard')) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                    <select name="statut">
                                        <option value="en_attente" <?= $r['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="confirmee" <?= $r['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmee</option>
                                        <option value="annulee" <?= $r['statut'] === 'annulee' ? 'selected' : '' ?>>Annulee</option>
                                    </select>
                                    <button type="submit">OK</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        new Chart(document.getElementById('chartMois'), {
            type: 'bar',
            data: {
                labels: <?= $mois_labels ?: '[]' ?>,
                datasets: [{
                    label: 'Reservations',
                    data: <?= $mois_data ?: '[]' ?>,
                    backgroundColor: '#0f3460',
                    borderRadius: 6
                }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        new Chart(document.getElementById('chartHotel'), {
            type: 'doughnut',
            data: {
                labels: <?= $hotel_labels ?: '[]' ?>,
                datasets: [{
                    data: <?= $hotel_data ?: '[]' ?>,
                    backgroundColor: ['#0f3460', '#e94560', '#16213e']
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });

        document.getElementById('searchInput').addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#reservTable tbody tr').forEach((row) => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
