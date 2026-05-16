<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-auth">
    <div class="card">
        <div class="logo-area">
            <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel">
            <h1>Creer un compte</h1>
        </div>

        <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>
        <?php if (!empty($success)): ?><div class="success"><?= htmlspecialchars((string) $success) ?> <a href="<?= htmlspecialchars(app_url('login')) ?>">Se connecter</a></div><?php endif; ?>

        <?php if (empty($success)): ?>
        <form method="POST" action="<?= htmlspecialchars(app_url('register')) ?>">
            <div class="row">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="nom" required placeholder="Dupont" value="<?= htmlspecialchars((string) ($old['nom'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>Prenom</label>
                    <input type="text" name="prenom" required placeholder="Jean" value="<?= htmlspecialchars((string) ($old['prenom'] ?? '')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="votre@email.com" value="<?= htmlspecialchars((string) ($old['email'] ?? '')) ?>">
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required placeholder="Minimum 6 caracteres">
            </div>
            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="password2" required placeholder="........">
            </div>
            <button type="submit" class="btn">Creer mon compte</button>
        </form>
        <?php endif; ?>

        <div class="login-link">
            Deja un compte ? <a href="<?= htmlspecialchars(app_url('login')) ?>">Se connecter</a>
        </div>
    </div>
</body>
</html>
