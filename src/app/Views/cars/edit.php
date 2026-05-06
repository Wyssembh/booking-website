<?php $car_types = ['economique' => 'Économique', 'compact' => 'Compact', 'berline' => 'Berline', 'suv' => 'SUV', 'luxe' => 'Luxe']; ?>
<?php $carburants = ['essence' => 'Essence', 'diesel' => 'Diesel', 'hybride' => 'Hybride', 'electrique' => 'Électrique']; ?>
<?php $statuts = ['disponible' => 'Disponible', 'louee' => 'Louée', 'entretien' => 'En entretien']; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Voiture</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Modifier la Voiture</div>
        <div class="content">

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(app_url('cars/edit')) ?>" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int) $car['id'] ?>">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Marque *</label>
                        <input type="text" name="marque" value="<?= htmlspecialchars((string) $car['marque']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Modele *</label>
                        <input type="text" name="modele" value="<?= htmlspecialchars((string) $car['modele']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type">
                            <?php foreach ($car_types as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= $key === $car['type'] ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre de portes</label>
                        <input type="number" name="portes" min="2" max="5" value="<?= (int) $car['portes'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Carburant</label>
                        <select name="carburant">
                            <?php foreach ($carburants as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= $key === $car['carburant'] ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Prix par jour (€) *</label>
                        <input type="number" name="prix_par_jour" step="0.01" min="0.01" value="<?= number_format((float) $car['prix_par_jour'], 2, '.', '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut">
                            <?php foreach ($statuts as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= $key === $car['statut'] ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Image de la voiture</label>
                        <input type="file" name="image" accept="image/*" style="padding: 8px; border: 1.5px solid #e0e0e0; border-radius: 8px; width: 100%; box-sizing: border-box;">
                        <small style="color: #666; font-size: 12px;">Formats acceptés: JPG, PNG, GIF, WebP. Taille max: 5MB</small>
                        <?php $previewImg = car_image_src($car['image'] ?? null); ?>
                        <?php if ($previewImg !== null): ?>
                            <div style="margin-top: 10px;">
                                <small style="color: #666;">Image actuelle:</small><br>
                                <img src="<?= htmlspecialchars($previewImg) ?>" alt="Image actuelle" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px; margin-top: 5px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div style="display:flex; gap: 15px; flex-wrap:wrap; margin-top: 20px;">
                    <button type="submit" class="btn">Enregistrer</button>
                    <a href="<?= htmlspecialchars(app_url('cars')) ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>

            <form method="POST" action="<?= htmlspecialchars(app_url('cars/delete')) ?>" style="margin-top: 20px;">
                <input type="hidden" name="id" value="<?= (int) $car['id'] ?>">
                <button type="submit" class="btn btn-danger">Supprimer la voiture</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>