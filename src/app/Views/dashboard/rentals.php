<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Locations - Administration</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Gestion des Locations de Voitures</div>
        <div class="content">
            <div class="card">
                <?php if (empty($car_rentals)): ?>
                    <div class="empty-state">
                        <p>Aucune location de voiture pour le moment.</p>
                    </div>
                <?php else: ?>
                <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Voiture</th>
                            <th>Période</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($car_rentals as $rental): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($rental['prenom'] . ' ' . $rental['nom']) ?></strong><br>
                                <span class="info-text"><?= htmlspecialchars($rental['email']) ?></span>
                            </td>
                            <td>
                                <?= htmlspecialchars($rental['marque']) ?> <?= htmlspecialchars($rental['modele']) ?><br>
                                <span class="info-text"><?= htmlspecialchars(ucfirst($rental['type'])) ?> • <?= htmlspecialchars(ucfirst($rental['carburant'])) ?></span>
                            </td>
                            <td>
                                <span class="info-text">
                                    Du <?= date('d/m/Y', strtotime($rental['date_debut'])) ?><br>
                                    Au <?= date('d/m/Y', strtotime($rental['date_fin'])) ?>
                                </span>
                            </td>
                            <td><?= number_format((float) $rental['prix_total'], 2) ?> €</td>
                            <td>
                                <span class="badge badge-<?= htmlspecialchars($rental['statut']) ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $rental['statut'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($rental['statut'] === 'en_attente'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int) $rental['id'] ?>">
                                            <input type="hidden" name="statut" value="confirmee">
                                            <button type="submit" class="btn btn-success">Confirmer</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($rental['statut'] !== 'annulee' && $rental['statut'] !== 'terminee'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int) $rental['id'] ?>">
                                            <input type="hidden" name="statut" value="annulee">
                                            <button type="submit" class="btn btn-danger">Annuler</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($rental['statut'] === 'confirmee'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= (int) $rental['id'] ?>">
                                            <input type="hidden" name="statut" value="terminee">
                                            <button type="submit" class="btn btn-warning">Terminer</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
