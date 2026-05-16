<?php
$old = $old ?? ['nom' => '', 'prenom' => '', 'email' => ''];
$back_url = $back_url ?? app_url('reservation');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-auth">
    <div class="card" style="max-width: 560px;">
        <div class="logo-area">
            <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel">
            <h1>Mon profil</h1>
        </div>

        <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>
        <?php if (!empty($success)): ?><div class="success"><?= htmlspecialchars((string) $success) ?></div><?php endif; ?>

        <form method="POST" action="<?= htmlspecialchars(app_url('profile')) ?>">
            <div class="row">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" required value="<?= htmlspecialchars((string) ($old['nom'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>Prenom</label>
                    <input type="text" name="prenom" required value="<?= htmlspecialchars((string) ($old['prenom'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?= htmlspecialchars((string) ($old['email'] ?? '')) ?>">
            </div>

            <div class="form-group">
                <label>Mot de passe actuel</label>
                <input type="password" name="current_password" placeholder="Laisser vide si inchange">
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Nouveau mot de passe</label>
                    <input type="password" name="new_password" placeholder="Minimum 6 caracteres">
                </div>
                <div class="form-group">
                    <label>Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" placeholder="Ressaisir le mot de passe">
                </div>
            </div>

            <button type="submit" class="btn">Enregistrer</button>
        </form>

        <div class="login-link">
            <a href="<?= htmlspecialchars($back_url) ?>">Retour</a>
        </div>
    </div>
</body>
</html>
