<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Hotel</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Modifier un Hotel</div>
        <div class="content">

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(app_url('admin/hotels/edit')) ?>">
                <input type="hidden" name="id" value="<?= (int) $hotel['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="nom" required value="<?= htmlspecialchars((string) $hotel['nom']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Ville *</label>
                        <input type="text" name="ville" required value="<?= htmlspecialchars((string) $hotel['ville']) ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Adresse *</label>
                        <input type="text" name="adresse" required value="<?= htmlspecialchars((string) $hotel['adresse']) ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?= htmlspecialchars((string) ($hotel['description'] ?? '')) ?></textarea>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Image (URL)</label>
                        <input type="url" name="image_url" placeholder="https://..." value="<?= htmlspecialchars((string) ($hotel['image_url'] ?? '')) ?>">
                        <?php if (!empty($hotel['image_url'])): ?>
                            <div style="margin-top: 10px;">
                                <small style="color: #666;">Image actuelle:</small><br>
                                <img src="<?= htmlspecialchars((string) $hotel['image_url']) ?>" alt="Image hotel" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Etoiles</label>
                        <input type="number" name="etoiles" min="1" max="5" value="<?= (int) $hotel['etoiles'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Prix par nuit (EUR)</label>
                        <input type="number" name="prix_nuit" step="0.01" min="0.01" value="<?= $hotel['prix_nuit'] !== null ? number_format((float) $hotel['prix_nuit'], 2, '.', '') : '' ?>">
                    </div>
                </div>
                <div style="display:flex; gap: 15px; flex-wrap:wrap; margin-top: 20px;">
                    <button type="submit" class="btn">Enregistrer</button>
                    <a href="<?= htmlspecialchars(app_url('admin/hotels')) ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>

            <form method="POST" action="<?= htmlspecialchars(app_url('admin/hotels/delete')) ?>" style="margin-top: 20px;" onsubmit="return confirm('Supprimer cet hotel ?');">
                <input type="hidden" name="id" value="<?= (int) $hotel['id'] ?>">
                <button type="submit" class="btn btn-danger">Supprimer l hotel</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
