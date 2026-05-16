<?php $taxi_types_json = json_encode($taxi_types); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la reservation de taxi</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-client">
    <div class="topbar">
        <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel">
        <div class="topbar-right">
            <span>Bonjour, <?= htmlspecialchars((string) ($_SESSION['prenom'] ?? 'Client')) ?></span>
            <a href="<?= htmlspecialchars(app_url('taxi')) ?>">Mes taxis</a>
            <a href="<?= htmlspecialchars(app_url('logout')) ?>">Deconnexion</a>
        </div>
    </div>

    <div class="container">
        <h2>Modifier la reservation de taxi</h2>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

            <div class="reservation-info">
                <p><strong>Hotel associe :</strong> <?= htmlspecialchars((string) $taxi_reservation['reservation_hotel']) ?></p>
                <p><strong>Date d'arrivee hotel :</strong> <?= htmlspecialchars((string) date('d/m/Y', strtotime((string) $taxi_reservation['date_arrivee']))) ?></p>
                <p><strong>Date de depart hotel :</strong> <?= htmlspecialchars((string) date('d/m/Y', strtotime((string) $taxi_reservation['date_depart']))) ?></p>
            </div>

            <form method="POST" action="<?= htmlspecialchars(app_url('taxi/edit')) ?>">
                <input type="hidden" name="id" value="<?= (int) $taxi_reservation['id'] ?>">
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Adresse de depart</label>
                        <input type="text" name="adresse_depart" value="<?= htmlspecialchars((string) $taxi_reservation['adresse_depart']) ?>" required>
                    </div>
                    <div class="form-group full">
                        <label>Adresse d'arrivee</label>
                        <input type="text" name="adresse_arrivee" value="<?= htmlspecialchars((string) $taxi_reservation['adresse_arrivee']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Date de prise en charge</label>
                        <input type="date" name="date_arrivee" value="<?= htmlspecialchars((string) date('Y-m-d', strtotime((string) $taxi_reservation['date_heure']))) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Heure de prise en charge</label>
                        <input type="time" name="heure_arrivee" value="<?= htmlspecialchars((string) date('H:i', strtotime((string) $taxi_reservation['date_heure']))) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Type de taxi</label>
                        <select name="type" required>
                            <?php foreach ($taxi_types as $type => $prix): ?>
                                <option value="<?= htmlspecialchars($type) ?>" <?= $type === $taxi_reservation['type'] ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($type)) ?> - <?= htmlspecialchars((string) $prix) ?> EUR</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre de passagers</label>
                        <input type="number" name="nb_passagers" min="1" max="6" value="<?= (int) $taxi_reservation['nb_passagers'] ?>">
                    </div>
                </div>
                <div style="display:flex; gap: 15px; flex-wrap:wrap; margin-top: 20px;">
                    <button type="submit" class="btn">Enregistrer</button>
                    <a href="<?= htmlspecialchars(app_url('taxi')) ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>

            <form method="POST" action="<?= htmlspecialchars(app_url('taxi/delete')) ?>" style="margin-top: 20px;">
                <input type="hidden" name="id" value="<?= (int) $taxi_reservation['id'] ?>">
                <button type="submit" class="btn btn-danger">Supprimer la reservation</button>
            </form>
        </div>
    </div>
</body>
</html>
