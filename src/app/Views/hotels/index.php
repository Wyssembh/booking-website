<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Gestion des Hotels</div>
        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; color: #0f3460;">Liste des hotels</h3>
                <a href="<?= htmlspecialchars(app_url('admin/hotels/add')) ?>" class="btn">Ajouter un hotel</a>
            </div>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <?php if (empty($hotels)): ?>
                <p style="color:#999; text-align:center; padding: 20px;">Aucun hotel pour le moment.</p>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Etoiles</th>
                        <th>Prix/Nuit</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hotels as $hotel): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $hotel['nom']) ?></td>
                        <td><?= htmlspecialchars((string) $hotel['ville']) ?></td>
                        <td><?= (int) $hotel['etoiles'] ?></td>
                        <td>
                            <?php if ($hotel['prix_nuit'] !== null): ?>
                                <?= number_format((float) $hotel['prix_nuit'], 2) ?> EUR
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($hotel['image_url'])): ?>
                                <img src="<?= htmlspecialchars((string) $hotel['image_url']) ?>" alt="Image hotel" style="max-width: 80px; max-height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <span style="color:#999;">Aucune image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= htmlspecialchars(app_url('admin/hotels/edit') . '&id=' . (int) $hotel['id']) ?>" class="btn btn-secondary" style="margin-right: 8px; padding: 6px 12px; font-size: 11px;">Modifier</a>
                            <form method="POST" action="<?= htmlspecialchars(app_url('admin/hotels/delete')) ?>" style="display:inline;" onsubmit="return confirm('Supprimer cet hotel ?');">
                                <input type="hidden" name="id" value="<?= (int) $hotel['id'] ?>">
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
