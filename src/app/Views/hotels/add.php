<?php
$old = $old ?? [
    'nom' => '',
    'ville' => '',
    'adresse' => '',
    'description' => '',
    'image_url' => '',
    'etoiles' => '3',
    'prix_nuit' => '',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Hotel</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Ajouter un Hotel</div>
        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; color: #0f3460;">Nouveau hotel</h3>
                <a href="<?= htmlspecialchars(app_url('admin/hotels')) ?>" class="btn btn-secondary">Retour a la liste</a>
            </div>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(app_url('admin/hotels/add')) ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="nom" required value="<?= htmlspecialchars((string) $old['nom']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Ville *</label>
                        <input type="text" name="ville" required value="<?= htmlspecialchars((string) $old['ville']) ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Adresse *</label>
                        <input type="text" name="adresse" required value="<?= htmlspecialchars((string) $old['adresse']) ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Description</label>
                        <textarea name="description" rows="4"><?= htmlspecialchars((string) $old['description']) ?></textarea>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Image (URL)</label>
                        <input type="url" name="image_url" placeholder="https://..." value="<?= htmlspecialchars((string) $old['image_url']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Etoiles</label>
                        <input type="number" name="etoiles" min="1" max="5" value="<?= htmlspecialchars((string) $old['etoiles']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Prix par nuit (EUR)</label>
                        <input type="number" name="prix_nuit" step="0.01" min="0.01" value="<?= htmlspecialchars((string) $old['prix_nuit']) ?>">
                    </div>
                </div>
                <div style="display:flex; gap: 15px; flex-wrap:wrap; margin-top: 20px;">
                    <button type="submit" class="btn">Ajouter l hotel</button>
                    <a href="<?= htmlspecialchars(app_url('admin/hotels')) ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        </div>
    </main>
</body>
</html>
