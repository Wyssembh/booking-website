<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-auth">
    <div class="login-card">
        <div class="logo-area">
            <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel">
            <h1>Espace Client</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars((string) $error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= htmlspecialchars(app_url('login')) ?>">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="votre@email.com" value="<?= htmlspecialchars((string) ($old['email'] ?? '')) ?>">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="........">
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <div class="register-link">
            Pas encore de compte ? <a href="<?= htmlspecialchars(app_url('register')) ?>">S'inscrire</a>
        </div>
        <div class="back-link">
            <a href="../index.html"><- Retour au site</a>
        </div>
    </div>
</body>
</html>
