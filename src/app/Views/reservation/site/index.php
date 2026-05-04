<?php
$featured_hotels = $featured_hotels ?? [];
$panel_classes = ['panel-rym', 'panel-aladin', 'panel-alhambra'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Hotels & Resorts</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(asset_url('styles.css')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@300;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../partials/site_header.php'; ?>

    <section class="hero-slider">
        <?php for ($i = 0; $i < 3; $i++): ?>
            <input type="radio" name="slider" id="slide<?= $i + 1 ?>" <?= $i === 0 ? 'checked' : '' ?>>
        <?php endfor; ?>

        <div class="slides">
            <?php foreach ($featured_hotels as $index => $hotel): ?>
                <?php
                $imageUrl = trim((string) ($hotel['image_url'] ?? ''));
                $bgStyle = $imageUrl !== '' ? " style=\"background-image: url('" . htmlspecialchars($imageUrl, ENT_QUOTES) . "')\"" : '';
                $hotelName = (string) ($hotel['nom'] ?? '');
                $stars = (int) ($hotel['etoiles'] ?? 0);
                ?>
                <div class="slide"<?= $bgStyle ?>>
                    <div class="slide-content">
                        <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_sigle_blanc-10.svg" alt="Seabel" class="slide-logo">
                        <h1 class="slide-title">Le charme discret</h1>
                        <div class="slide-hotel"><?= htmlspecialchars($hotelName) ?></div>
                        <img src="<?= htmlspecialchars(stars_image_url($stars)) ?>" alt="Stars" class="stars">
                        <div class="slide-cta">VIVEZ L'EXPERIENCE</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <label for="slide3" class="slider-arrow arrow-left arrow-left-1">‹</label>
        <label for="slide2" class="slider-arrow arrow-right arrow-right-1">›</label>
        <label for="slide1" class="slider-arrow arrow-left arrow-left-2">‹</label>
        <label for="slide3" class="slider-arrow arrow-right arrow-right-2">›</label>
        <label for="slide2" class="slider-arrow arrow-left arrow-left-3">‹</label>
        <label for="slide1" class="slider-arrow arrow-right arrow-right-3">›</label>

        <div class="slider-nav">
            <label for="slide1" class="nav-dot"></label>
            <label for="slide2" class="nav-dot"></label>
            <label for="slide3" class="nav-dot"></label>
        </div>

        <div class="scroll-indicator">
            <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/icons/arrow_bottom-3.png" alt="Scroll Down">
        </div>
    </section>

    <section class="about-section">
        <div class="container">
            <div class="about-eyebrow">DECOUVREZ LES HOTELS </div>
            <h2 class="about-title">Bienvenue</h2>
            <div class="about-content">
                <p>Notre site de booking vous permet de réserver vos séjours dans les meilleurs hotels.</p>
                <p>Que vous recherchiez la detente au bord de la mer, des activites sportives ou des moments de decouverte culturelle, notre site vous propose une large selection d'hebergements pour tous les goûts et tous les budgets.</p>
                <a href="hotels.php" class="about-link">Voir plus</a>
            </div>
        </div>
    </section>

    <!-- <section class="events-section" id="events">
        <div class="container">
            <div class="events-header">
                <div class="events-eyebrow">PROGRAMMATION</div>
                <h2 class="events-title">Evenements a venir</h2>
                <p class="events-subtitle">Decouvrez les spectacles, soirees et animations prevus dans nos hotels.</p>
            </div>
        </div>

        <div class="events-hero-slider" id="eventsSlider">
            <div class="events-slides" id="eventsSlides">
                <div class="event-loading">Chargement des evenements...</div>
            </div>
            <button type="button" class="events-slider-arrow events-arrow-left" id="eventsPrev" aria-label="Evenement precedent">‹</button>
            <button type="button" class="events-slider-arrow events-arrow-right" id="eventsNext" aria-label="Evenement suivant">›</button>
            <div class="events-slider-nav" id="eventsDots"></div>
        </div>
    </section> -->

    <section class="hotels-showcase">
        <div class="hotel-panel panel-selection">
            <div class="panel-content">
                <div class="selection-eyebrow">CHOISISSEZ VOTRE HOTEL </div>
                <h3 class="selection-title">Besoin de vacances ?</h3>
                <p class="selection-text">Hotels clubs pour des sejours tout compris en famille ou entre amis. Choisissez votre destination et vivez des moments inoubliables.</p>
            </div>
        </div>

        <?php foreach ($featured_hotels as $index => $hotel): ?>
            <?php
            $imageUrl = trim((string) ($hotel['image_url'] ?? ''));
            $bgStyle = $imageUrl !== '' ? " style=\"background-image: url('" . htmlspecialchars($imageUrl, ENT_QUOTES) . "')\"" : '';
            $panelClass = $panel_classes[$index] ?? 'panel-rym';
            ?>
            <a href="hotel-details.php?slug=<?= htmlspecialchars((string) ($hotel['slug'] ?? '')) ?>" class="hotel-panel <?= htmlspecialchars($panelClass) ?>"<?= $bgStyle ?>>
                <div class="panel-content">
                    <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_sigle_blanc-10.svg" alt="Wave Logo" class="hotel-wave">
                    <h3 class="hotel-name"><?= nl2br(htmlspecialchars((string) ($hotel['nom'] ?? ''))) ?></h3>
                    <div class="hotel-location"><?= htmlspecialchars((string) ($hotel['ville'] ?? '')) ?></div>
                    <img src="<?= htmlspecialchars(stars_image_url((int) ($hotel['etoiles'] ?? 0))) ?>" alt="Stars" class="hotel-stars">
                    <?php if (!empty($hotel['prix_nuit'])): ?>
                        <div class="hotel-price">A partir de <?= number_format((float) $hotel['prix_nuit'], 0) ?> EUR / nuit</div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    </section>

    <?php include __DIR__ . '/../partials/site_footer.php'; ?>

    <script>
        (function () {
            const slider = document.getElementById('eventsSlider');
            const slidesWrap = document.getElementById('eventsSlides');
            const prevBtn = document.getElementById('eventsPrev');
            const nextBtn = document.getElementById('eventsNext');
            const dotsWrap = document.getElementById('eventsDots');

            if (!slider || !slidesWrap || !prevBtn || !nextBtn || !dotsWrap) {
                return;
            }

            let currentIndex = 0;
            let eventsCount = 0;

            const formatDate = function (rawDate) {
                if (!rawDate) {
                    return '';
                }

                const date = new Date(rawDate + 'T00:00:00');
                return date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            };

            const escapeHtml = function (value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            };

            const truncate = function (value, max) {
                const text = String(value || '');
                return text.length > max ? text.slice(0, max) + '...' : text;
            };

            const renderDots = function () {
                dotsWrap.innerHTML = '';
                for (let i = 0; i < eventsCount; i += 1) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'events-nav-dot' + (i === currentIndex ? ' active' : '');
                    dot.setAttribute('aria-label', 'Aller a l evenement ' + (i + 1));
                    dot.addEventListener('click', function () {
                        goTo(i);
                    });
                    dotsWrap.appendChild(dot);
                }
            };

            const showOrHideControls = function () {
                const show = eventsCount > 1;
                prevBtn.style.display = show ? 'flex' : 'none';
                nextBtn.style.display = show ? 'flex' : 'none';
                dotsWrap.style.display = show ? 'flex' : 'none';
            };

            const goTo = function (index) {
                if (eventsCount === 0) {
                    return;
                }

                currentIndex = (index + eventsCount) % eventsCount;
                slidesWrap.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

                const dots = dotsWrap.querySelectorAll('.events-nav-dot');
                dots.forEach(function (dot, dotIndex) {
                    dot.classList.toggle('active', dotIndex === currentIndex);
                });
            };

            fetch('src/index.php?route=events-feed')
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Impossible de charger les evenements.');
                    }

                    return response.json();
                })
                .then(function (payload) {
                    const events = Array.isArray(payload.events) ? payload.events : [];

                    if (events.length === 0) {
                        slidesWrap.innerHTML = '<p class="event-empty">Aucun evenement a venir pour le moment.</p>';
                        showOrHideControls();
                        return;
                    }

                    eventsCount = events.length;

                    slidesWrap.innerHTML = events.map(function (eventItem) {
                        const imageUrl = String(eventItem.image_url || '').trim();
                        const safeUrl = imageUrl ? encodeURI(imageUrl) : '';
                        const slideClass = safeUrl ? 'events-slide' : 'events-slide events-slide-fallback';
                        const slideStyle = safeUrl ? ' style="background-image: url(\'' + safeUrl + '\')"' : '';
                        const fallbackLabel = safeUrl ? '' : '<div class="events-slide-fallback-label">Seabel Event</div>';

                        return '<div class="' + slideClass + '"' + slideStyle + '>'
                            + fallbackLabel
                            + '<div class="event-slide-content">'
                            + '<div class="event-slide-hotel">' + escapeHtml(eventItem.hotel) + '</div>'
                            + '<h3 class="event-slide-title">' + escapeHtml(eventItem.titre) + '</h3>'
                            + '<p class="event-slide-date">Du ' + escapeHtml(formatDate(eventItem.date_debut)) + ' au ' + escapeHtml(formatDate(eventItem.date_fin)) + '</p>'
                            + '<p class="event-slide-singer">Avec ' + escapeHtml(eventItem.chanteur) + '</p>'
                            + '<p class="event-slide-description">' + escapeHtml(truncate(eventItem.description, 170)) + '</p>'
                            + '<a class="event-slide-cta" href="reservation.php?event_id=' + encodeURIComponent(eventItem.id) + '">RESERVER CET EVENEMENT</a>'
                            + '</div>'
                            + '</div>';
                    }).join('');

                    renderDots();
                    showOrHideControls();
                    goTo(0);

                    prevBtn.addEventListener('click', function () {
                        goTo(currentIndex - 1);
                    });

                    nextBtn.addEventListener('click', function () {
                        goTo(currentIndex + 1);
                    });
                })
                .catch(function () {
                    slidesWrap.innerHTML = '<p class="event-empty">Les evenements sont momentanement indisponibles.</p>';
                    showOrHideControls();
                });
        })();
    </script>
</body>
</html>
