<?php
$nav_hotels = $nav_hotels ?? [];
$home_url = app_url('home');
$hotels_url = app_url('hotels');
$events_url = app_url('events-public');
$hotel_details_base = app_url('hotel-details');
$reservation_url = app_url('reservation');
$favorites_hotels = [];
foreach ($nav_hotels as $navHotel) {
    $hotelId = (int) ($navHotel['id'] ?? 0);
    if ($hotelId <= 0) {
        continue;
    }

    $slug = (string) ($navHotel['slug'] ?? '');
    $detailsUrl = $hotel_details_base . '&id=' . $hotelId;
    if ($slug !== '') {
        $detailsUrl .= '&slug=' . rawurlencode($slug);
    }

    $favorites_hotels[] = [
        'id' => $hotelId,
        'name' => (string) ($navHotel['nom'] ?? ''),
        'url' => $detailsUrl,
    ];
}
?>
<header class="header">
    <div class="header-container">
        <div class="header-left">
            <input type="checkbox" id="menu-toggle" class="menu-toggle">
            <label for="menu-toggle" class="menu-overlay"></label>
            <nav class="side-menu">
                <label for="menu-toggle" class="close-menu">&times;</label>
                <ul>
                    <li><a href="<?= htmlspecialchars($home_url) ?>">Accueil</a></li>
                    <li><a href="<?= htmlspecialchars($hotels_url) ?>">Nos hotels</a></li>
                    
                </ul>
            </nav>
            <label for="menu-toggle" class="menu-button">
                <span class="hamburger"></span>
                <span class="menu-text">MENU</span>
            </label>
        </div>

        <div class="header-center">
            <img src="<?= htmlspecialchars(seabel_logo_url()) ?>" alt="seabel Hotels" class="logo">
        </div>

        <div class="header-right">
            <div class="favorites-menu" data-favorites>
                <button type="button" class="favorites-toggle" data-favorites-toggle aria-haspopup="true" aria-expanded="false">
                    <span class="favorites-icon">&#9733;</span>
                    <span class="favorites-label">Favoris</span>
                    <span class="favorites-count" data-favorites-count>0</span>
                </button>
                <div class="favorites-dropdown" data-favorites-dropdown hidden>
                    <div class="favorites-empty" data-favorites-empty>Aucun favori pour le moment.</div>
                    <ul class="favorites-list" data-favorites-list></ul>
                </div>
            </div>
            <a href="<?= htmlspecialchars($reservation_url) ?>" class="reserve-button">RESERVER</a>
        </div>
    </div>

    <nav class="mobile-menu">
        <ul>
            <li><a href="<?= htmlspecialchars($home_url) ?>">Accueil</a></li>
            <li><a href="<?= htmlspecialchars($hotels_url) ?>">Nos hotels</a></li>
            <li><a href="<?= htmlspecialchars($events_url) ?>">Evenements</a></li>
            <?php foreach ($nav_hotels as $navHotel): ?>
                <?php
                $hotelId = (int) ($navHotel['id'] ?? 0);
                $slug = (string) ($navHotel['slug'] ?? '');
                $detailsUrl = $hotel_details_base . '&id=' . $hotelId;
                if ($slug !== '') {
                    $detailsUrl .= '&slug=' . rawurlencode($slug);
                }
                ?>
                <li><a href="<?= htmlspecialchars($detailsUrl) ?>"><?= htmlspecialchars((string) ($navHotel['nom'] ?? '')) ?></a></li>
            <?php endforeach; ?>
            <li><a href="#press">Presse & News</a></li>
            <li><a href="#media">Photos & Videos</a></li>
            <li><a href="#protocol">Protocole sanitaire</a></li>
        </ul>
    </nav>

    <script>
        (function () {
            const hotelData = <?= json_encode($favorites_hotels, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
            const hotelMap = new Map();
            hotelData.forEach(function (item) {
                if (item && item.id) {
                    hotelMap.set(String(item.id), item);
                }
            });

            const cookieName = 'favorite_hotels';
            const menu = document.querySelector('[data-favorites]');
            const toggleBtn = document.querySelector('[data-favorites-toggle]');
            const dropdown = document.querySelector('[data-favorites-dropdown]');
            const listEl = document.querySelector('[data-favorites-list]');
            const emptyEl = document.querySelector('[data-favorites-empty]');
            const countEl = document.querySelector('[data-favorites-count]');

            function getCookie(name) {
                const escaped = name.replace(/([.*+?^${}()|[\\]\\])/g, '\\$1');
                const match = document.cookie.match(new RegExp('(?:^|; )' + escaped + '=([^;]*)'));
                return match ? decodeURIComponent(match[1]) : '';
            }

            function parseIds(raw) {
                if (!raw) {
                    return [];
                }

                let values = [];
                try {
                    const parsed = JSON.parse(raw);
                    if (Array.isArray(parsed)) {
                        values = parsed;
                    }
                } catch (err) {
                    values = raw.split(',');
                }

                return values
                    .map(function (value) { return parseInt(String(value || ''), 10); })
                    .filter(function (value) { return Number.isFinite(value) && value > 0; });
            }

            function uniqueIds(ids) {
                const seen = new Set();
                const result = [];
                ids.forEach(function (id) {
                    const key = String(id);
                    if (!seen.has(key)) {
                        seen.add(key);
                        result.push(id);
                    }
                });
                return result;
            }

            function getFavoriteIds() {
                return parseIds(getCookie(cookieName));
            }

            function writeFavoriteIds(ids) {
                const normalized = uniqueIds(ids);
                const value = encodeURIComponent(JSON.stringify(normalized));
                document.cookie = cookieName + '=' + value + '; path=/; max-age=31536000; samesite=lax';
            }

            function updateFavoriteButtons(ids) {
                const set = new Set(ids.map(function (id) { return String(id); }));
                document.querySelectorAll('[data-favorite-toggle]').forEach(function (btn) {
                    const id = String(btn.getAttribute('data-hotel-id') || '');
                    const active = set.has(id);
                    btn.classList.toggle('is-active', active);
                    btn.setAttribute('aria-pressed', active ? 'true' : 'false');
                    btn.title = active ? 'Retirer des favoris' : 'Ajouter aux favoris';
                });
            }

            function updateFavoritesUI() {
                const ids = getFavoriteIds();
                if (countEl) {
                    countEl.textContent = String(ids.length);
                }

                if (listEl) {
                    listEl.innerHTML = '';
                    ids.forEach(function (id) {
                            const item = hotelMap.get(String(id));
                            if (!item) {
                                return;
                            }

                            const li = document.createElement('li');
                            li.className = 'favorites-item';

                            const link = document.createElement('a');
                            link.href = item.url;
                            link.textContent = item.name || ('Hotel #' + id);
                            li.appendChild(link);

                            const removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'favorites-remove';
                            removeBtn.title = 'Retirer des favoris';
                            removeBtn.textContent = '✕';
                            removeBtn.addEventListener('click', function (ev) {
                                ev.preventDefault();
                                ev.stopPropagation();
                                toggleFavorite(parseInt(id, 10));
                            });
                            li.appendChild(removeBtn);

                            listEl.appendChild(li);
                        });
                }

                if (emptyEl) {
                    const hasItems = listEl && listEl.childElementCount > 0;
                    emptyEl.style.display = hasItems ? 'none' : 'block';
                }

                updateFavoriteButtons(ids);
            }

            function toggleFavorite(id) {
                const ids = getFavoriteIds();
                const index = ids.indexOf(id);
                if (index >= 0) {
                    ids.splice(index, 1);
                } else {
                    ids.push(id);
                }

                writeFavoriteIds(ids);
                updateFavoritesUI();
            }

            function bindFavoriteToggles() {
                document.querySelectorAll('[data-favorite-toggle]').forEach(function (btn) {
                    if (btn.dataset.bound === 'true') {
                        return;
                    }

                    btn.dataset.bound = 'true';
                    btn.addEventListener('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        const id = parseInt(btn.getAttribute('data-hotel-id') || '0', 10);
                        if (id > 0) {
                            toggleFavorite(id);
                        }
                    });
                });
            }

            function setDropdown(open) {
                if (!dropdown || !toggleBtn) {
                    return;
                }

                dropdown.hidden = !open;
                toggleBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            function bindDropdown() {
                if (!menu || !toggleBtn || !dropdown) {
                    return;
                }

                toggleBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    setDropdown(dropdown.hidden);
                });

                document.addEventListener('click', function (event) {
                    if (!menu.contains(event.target)) {
                        setDropdown(false);
                    }
                });
            }

            function init() {
                bindDropdown();
                bindFavoriteToggles();
                updateFavoritesUI();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</header>
