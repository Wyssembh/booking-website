<?php
$nav_hotels = $nav_hotels ?? [];
$events = $events ?? [];

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
    <title>Evenements - Seabel Hotels</title>
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
                <p class="page-hero-eyebrow">PROGRAMMATION</p>
                <h1 class="page-hero-title">Evenements a venir</h1>
                <p class="page-hero-subtitle">Retrouvez les spectacles et animations dans nos hotels.</p>
            </div>
        </section>

        <section class="page-section">
            <div class="container">
                <?php if ($events === []): ?>
                    <p class="empty-state">Aucun evenement a venir pour le moment.</p>
                <?php else: ?>
                    <div class="event-grid">
                        <?php foreach ($events as $event): ?>
                            <?php
                            $eventImage = trim((string) ($event['image_url'] ?? ''));
                            $eventStyle = $eventImage !== '' ? " style=\"background-image: url('" . htmlspecialchars($eventImage, ENT_QUOTES) . "')\"" : '';
                            $description = trim((string) ($event['description'] ?? ''));
                            ?>
                            <article class="event-card">
                                <div class="event-card-media"<?= $eventStyle ?>></div>
                                <div class="event-card-body">
                                    <p class="event-card-hotel"><?= htmlspecialchars((string) ($event['hotel'] ?? '')) ?></p>
                                    <h3><?= htmlspecialchars((string) ($event['titre'] ?? '')) ?></h3>
                                    <div class="event-card-meta">
                                        Du <?= htmlspecialchars((string) ($event['date_debut'] ?? '')) ?> au <?= htmlspecialchars((string) ($event['date_fin'] ?? '')) ?>
                                    </div>
                                    <p class="event-card-singer">Avec <?= htmlspecialchars((string) ($event['chanteur'] ?? '')) ?></p>
                                    <?php if ($description !== ''): ?>
                                        <p class="event-card-desc"><?= htmlspecialchars($truncate($description, 160)) ?></p>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/../partials/site_footer.php'; ?>
</body>
</html>
