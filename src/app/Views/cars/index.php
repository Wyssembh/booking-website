<?php $car_types = ['economique' => 'Économique', 'compact' => 'Compact', 'berline' => 'Berline', 'suv' => 'SUV', 'luxe' => 'Luxe']; ?>
<?php $carburants = ['essence' => 'Essence', 'diesel' => 'Diesel', 'hybride' => 'Hybride', 'electrique' => 'Électrique']; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Voitures</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Gestion des Voitures</div>
        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; color: #0f3460;">Liste des voitures</h3>
                <a href="<?= htmlspecialchars(app_url('cars/add')) ?>" class="btn">Ajouter une voiture</a>
            </div>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <?php if (empty($cars)): ?>
                <p style="color:#999; text-align:center; padding: 20px;">Aucune voiture pour le moment.</p>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Marque</th>
                        <th>Modele</th>
                        <th>Type</th>
                        <th>Portes</th>
                        <th>Carburant</th>
                        <th>Prix/Jour</th>
                        <th>Image</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $car['marque']) ?></td>
                        <td><?= htmlspecialchars((string) $car['modele']) ?></td>
                        <td><?= htmlspecialchars(ucfirst((string) $car['type'])) ?></td>
                        <td><?= (int) $car['portes'] ?> portes</td>
                        <td><?= htmlspecialchars(ucfirst((string) $car['carburant'])) ?></td>
                        <td><?= number_format((float) $car['prix_par_jour'], 2) ?> €</td>
                        <td>
                            <?php $carImg = car_image_src($car['image'] ?? null); ?>
                            <?php if ($carImg !== null): ?>
                                <img src="<?= htmlspecialchars($carImg) ?>" alt="Image de la voiture" style="max-width: 80px; max-height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <span style="color:#999;">Aucune image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars((string) $car['statut']) ?>">
                                <?= htmlspecialchars(ucfirst((string) $car['statut'])) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= htmlspecialchars(app_url('cars/edit') . '&id=' . (int) $car['id']) ?>" class="btn btn-secondary" style="margin-right: 8px; padding: 6px 12px; font-size: 11px;">Modifier</a>
                            <form method="POST" action="<?= htmlspecialchars(app_url('cars/delete')) ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?= (int) $car['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 11px;">Supprimer</button>
                            </form>
                        </td>
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