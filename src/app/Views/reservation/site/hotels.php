<?php
$nav_hotels = $nav_hotels ?? [];
$hotels = $hotels ?? [];
$cities = $cities ?? [];
$search = (string) ($search ?? '');
$selected_city = (string) ($selected_city ?? '');
$page = (int) ($page ?? 1);
$total_pages = (int) ($total_pages ?? 1);

$search_value = htmlspecialchars($search, ENT_QUOTES);
$selected_city_value = htmlspecialchars($selected_city, ENT_QUOTES);

$buildLink = static function (int $targetPage) use ($search, $selected_city): string {
    $params = [];
    if ($search !== '') {
        $params['q'] = $search;
    }
    if ($selected_city !== '') {
        $params['ville'] = $selected_city;
    }
    $params['page'] = $targetPage;
    return 'hotels.php?' . http_build_query($params);
};

$truncate = static function (string $text, int $max): string {
    if (strlen($text) <= $max) {
        return $text;
    }

    return substr($text, 0, $max) . '...';
};
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos hotels - Seabel Hotels</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('styles.css')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../partials/site_header.php'; ?>

    <main class="page-content">
        <section class="page-hero page-hero-compact">
            <div class="page-hero-inner">
                <p class="page-hero-eyebrow">NOS HOTELS</p>
                <h1 class="page-hero-title">Trouvez votre destination</h1>
                <p class="page-hero-subtitle">Recherchez un hotel par nom ou par ville.</p>
            </div>
        </section>

        <section class="page-section">
            <div class="container">
                <form class="hotel-search" method="GET" action="hotels.php">
                    <input type="text" name="q" placeholder="Rechercher un hotel" value="<?= $search_value ?>">
                    <select name="ville">
                        <option value="">Toutes les villes</option>
                        <?php foreach ($cities as $city): ?>
                            <?php $cityValue = (string) $city; ?>
                            <option value="<?= htmlspecialchars($cityValue, ENT_QUOTES) ?>" <?= $cityValue === $selected_city ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cityValue) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Rechercher</button>
                    <a class="hotel-search-reset" href="hotels.php">Reinitialiser</a>
                </form>

                <?php if ($hotels === []): ?>
                    <p class="empty-state">Aucun hotel ne correspond a votre recherche.</p>
                <?php else: ?>
                    <div class="hotel-grid">
                        <?php foreach ($hotels as $hotel): ?>
                            <?php
                            $imageUrl = trim((string) ($hotel['image_url'] ?? ''));
                            $bgStyle = $imageUrl !== '' ? " style=\"background-image: url('" . htmlspecialchars($imageUrl, ENT_QUOTES) . "')\"" : '';
                            $slug = (string) ($hotel['slug'] ?? '');
                            $description = trim((string) ($hotel['description'] ?? ''));
                            ?>
                            <article class="hotel-card">
                                <a class="hotel-card-media" href="hotel-details.php?slug=<?= htmlspecialchars($slug) ?>"<?= $bgStyle ?>></a>
                                <div class="hotel-card-body">
                                    <div class="hotel-card-top">
                                        <h3><?= htmlspecialchars((string) ($hotel['nom'] ?? '')) ?></h3>
                                        <span class="hotel-card-stars"><?= (int) ($hotel['etoiles'] ?? 0) ?>*</span>
                                    </div>
                                    <div class="hotel-card-meta"><?= htmlspecialchars((string) ($hotel['ville'] ?? '')) ?></div>
                                    <?php if ($description !== ''): ?>
                                        <p class="hotel-card-desc"><?= htmlspecialchars($truncate($description, 140)) ?></p>
                                    <?php endif; ?>
                                    <div class="hotel-card-footer">
                                        <span class="hotel-card-price">A partir de <?= number_format((float) ($hotel['prix_nuit'] ?? 0), 0) ?> EUR / nuit</span>
                                        <a href="hotel-details.php?slug=<?= htmlspecialchars($slug) ?>" class="hotel-card-link">Voir details</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="<?= htmlspecialchars($buildLink($page - 1)) ?>">Precedent</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="active"><?= $i ?></span>
                            <?php else: ?>
                                <a href="<?= htmlspecialchars($buildLink($i)) ?>"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="<?= htmlspecialchars($buildLink($page + 1)) ?>">Suivant</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../partials/site_footer.php'; ?>
</body>
</html>
