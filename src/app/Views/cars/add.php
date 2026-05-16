<?php $car_types = ['economique' => 'Économique', 'compact' => 'Compact', 'berline' => 'Berline', 'suv' => 'SUV', 'luxe' => 'Luxe']; ?>
<?php $carburants = ['essence' => 'Essence', 'diesel' => 'Diesel', 'hybride' => 'Hybride', 'electrique' => 'Électrique']; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Voiture</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Ajouter une nouvelle voiture</div>
        <div class="content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h3 style="margin: 0; color: #0f3460;">Nouvelle voiture</h3>
                <a href="<?= htmlspecialchars(app_url('cars')) ?>" class="btn btn-secondary">Retour à la liste</a>
            </div>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <?php
            $repopulate = empty($success);
            $oldMarque = $repopulate ? (string) ($_POST['marque'] ?? '') : '';
            $oldModele = $repopulate ? (string) ($_POST['modele'] ?? '') : '';
            $oldType = $repopulate ? (string) ($_POST['type'] ?? 'economique') : 'economique';
            $oldPortes = $repopulate ? (string) ($_POST['portes'] ?? '4') : '4';
            $oldCarburant = $repopulate ? (string) ($_POST['carburant'] ?? 'essence') : 'essence';
            $oldPrix = $repopulate ? (string) ($_POST['prix_par_jour'] ?? '') : '';
            ?>
            <form method="POST" action="<?= htmlspecialchars(app_url('cars/add')) ?>" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Marque *</label>
                        <input type="text" name="marque" required placeholder="Ex: Renault" value="<?= htmlspecialchars($oldMarque) ?>">
                    </div>
                    <div class="form-group">
                        <label>Modele *</label>
                        <input type="text" name="modele" required placeholder="Ex: Clio" value="<?= htmlspecialchars($oldModele) ?>">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type">
                            <?php foreach ($car_types as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= ($oldType === $key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre de portes</label>
                        <input type="number" name="portes" min="2" max="5" value="<?= htmlspecialchars($oldPortes) ?>">
                    </div>
                    <div class="form-group">
                        <label>Carburant</label>
                        <select name="carburant">
                            <?php foreach ($carburants as $key => $label): ?>
                                <option value="<?= htmlspecialchars($key) ?>" <?= ($oldCarburant === $key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Prix par jour (€) *</label>
                        <input type="number" name="prix_par_jour" step="0.01" min="0.01" required placeholder="35.00" value="<?= htmlspecialchars($oldPrix) ?>">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Image de la voiture</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                        <small style="color: #666; font-size: 12px;">Formats acceptés : JPG, PNG, GIF, WebP. Taille max : 5&nbsp;Mo.</small>
                    </div>
                </div>
                <div style="display:flex; gap: 15px; flex-wrap:wrap; margin-top: 20px;">
                    <button type="submit" class="btn">Ajouter la voiture</button>
                    <a href="<?= htmlspecialchars(app_url('cars')) ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        </div>
    </main>
</body>
</html>
