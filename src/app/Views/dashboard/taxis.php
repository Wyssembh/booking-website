<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations Taxis - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Reservations de Taxis</div>
        <div class="content">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h3 style="margin: 0; color: #0f3460;">Toutes les reservations de taxi a venir</h3>
                </div>

                <?php if (empty($taxi_reservations)): ?>
                    <p style="color:#999; text-align:center; padding: 20px;">Aucune reservation de taxi a venir.</p>
                <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Reservation Hotel</th>
                            <th>Depart</th>
                            <th>Arrivee</th>
                            <th>Date / Heure</th>
                            <th>Type</th>
                            <th>Passagers</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($taxi_reservations as $tr): ?>
                        <tr>
                            <td><?= (int) $tr['id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars((string) ($tr['prenom'] . ' ' . $tr['nom'])) ?></strong><br>
                                <small style="color:#999"><?= htmlspecialchars((string) $tr['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars((string) ($tr['reservation_hotel'] ?? '')) ?> #<?= (int) ($tr['reservation_id'] ?? 0) ?></td>
                            <td><?= htmlspecialchars((string) $tr['adresse_depart']) ?></td>
                            <td><?= htmlspecialchars((string) $tr['adresse_arrivee']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime((string) $tr['date_heure'])) ?></td>
                            <td><?= htmlspecialchars(ucfirst((string) $tr['type'])) ?></td>
                            <td><?= (int) $tr['nb_passagers'] ?></td>
                            <td><?= number_format((float) $tr['prix_total'], 2) ?> EUR</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>