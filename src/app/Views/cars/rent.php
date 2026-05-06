<?php
$statut_labels = [
    'en_attente' => 'En attente',
    'confirmee' => 'Confirmée',
    'annulee' => 'Annulée',
    'terminee' => 'Terminée',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Voiture - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_rent_calendar_styles.php'; ?>
</head>
<body class="layout-seabel-client layout-seabel-client-wide">
    <header class="topbar">
        <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel Hotels">
        <div class="topbar-right">
            <span>Bonjour, <?= htmlspecialchars((string) ($_SESSION['prenom'] ?? 'Client')) ?></span>
            <a href="<?= htmlspecialchars(app_url('reservation')) ?>">Réservations</a>
            <a href="<?= htmlspecialchars(app_url('logout')) ?>">Déconnexion</a>
        </div>
    </header>

    <div class="container">
        <h2>Location de voiture</h2>

        <?php if (!empty($success)): ?><div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>

        <div class="card">
            <h3 style="margin-bottom: 8px; color: #0f3460;">Choisissez votre voiture</h3>
            <p class="hint">Cliquez sur une voiture pour voir les disponibilités et réserver vos dates.</p>
            <?php if (empty($available_cars)): ?>
                <div class="empty-state">Aucune voiture disponible pour le moment. Merci de réessayer plus tard.</div>
            <?php else: ?>
            <div class="cars-grid" id="cars-rent-grid" style="margin-top: 24px;">
                <?php foreach ($available_cars as $car): ?>
                <?php
                    $carLabel = $car['marque'] . ' ' . $car['modele'];
                    $rentCarImg = car_image_src($car['image'] ?? null);
                ?>
                <button type="button"
                    class="car-card"
                    data-car-id="<?= (int) $car['id'] ?>"
                    data-car-label="<?= htmlspecialchars($carLabel, ENT_QUOTES, 'UTF-8') ?>"
                    data-daily-price="<?= htmlspecialchars((string) (float) $car['prix_par_jour'], ENT_QUOTES, 'UTF-8') ?>"
                >
                    <?php if ($rentCarImg !== null): ?>
                        <div class="car-image">
                            <img src="<?= htmlspecialchars($rentCarImg) ?>" alt="<?= htmlspecialchars($carLabel) ?>">
                        </div>
                    <?php else: ?>
                        <div class="car-image">Pas d’image</div>
                    <?php endif; ?>
                    <div class="car-card-inner">
                        <div class="car-info">
                            <h4 class="car-title"><?= htmlspecialchars((string) $car['marque']) ?> <?= htmlspecialchars((string) $car['modele']) ?></h4>
                            <div class="car-details">
                                <span class="car-type"><?= htmlspecialchars(ucfirst((string) $car['type'])) ?></span>
                                <span class="car-specs"><?= (int) $car['portes'] ?> portes • <?= htmlspecialchars(ucfirst((string) $car['carburant'])) ?></span>
                            </div>
                            <div class="car-price"><?= number_format((float) $car['prix_par_jour'], 2, ',', ' ') ?> € / jour</div>
                            <div class="car-cta">Voir le calendrier →</div>
                        </div>
                    </div>
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($user_rentals)): ?>
        <div class="card">
            <h3 style="margin-bottom: 20px; color: #0f3460;">Mes locations</h3>
            <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Voiture</th>
                        <th>Période</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_rentals as $rental): ?>
                    <?php
                        $st = (string) $rental['statut'];
                        $can_cancel = $st === 'en_attente'
                            || ($st === 'confirmee' && strtotime((string) $rental['date_debut']) > strtotime('today'));
                    ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars((string) $rental['marque']) ?> <?= htmlspecialchars((string) $rental['modele']) ?><br>
                            <small style="color: #666;"><?= htmlspecialchars(ucfirst((string) $rental['type'])) ?> • <?= htmlspecialchars(ucfirst((string) $rental['carburant'])) ?></small>
                        </td>
                        <td>
                            Du <?= date('d/m/Y', strtotime((string) $rental['date_debut'])) ?><br>
                            au <?= date('d/m/Y', strtotime((string) $rental['date_fin'])) ?>
                        </td>
                        <td><?= number_format((float) $rental['prix_total'], 2, ',', ' ') ?> €</td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars($st) ?>">
                                <?= htmlspecialchars($statut_labels[$st] ?? str_replace('_', ' ', $st)) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($can_cancel): ?>
                            <form method="POST" action="<?= htmlspecialchars(app_url('cars/rent/cancel')) ?>" style="display:inline;" onsubmit="return confirm('Annuler cette location ?');">
                                <input type="hidden" name="rental_id" value="<?= (int) $rental['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-small">Annuler</button>
                            </form>
                            <?php else: ?>
                            <span style="color:#999; font-size:12px;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div id="calendar-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Calendrier de disponibilité</h3>
                <button type="button" class="close" onclick="closeModal()" aria-label="Fermer">&times;</button>
            </div>
            <div class="modal-body">
                <div id="calendar-container"></div>
                <form id="rental-form" method="POST" action="<?= htmlspecialchars(app_url('cars/rent/create')) ?>" style="display: none;">
                    <input type="hidden" name="car_id" id="selected-car-id">
                    <div class="form-grid" style="margin-top: 20px;">
                        <div class="form-group">
                            <label for="date-debut">Date de début *</label>
                            <input type="date" name="date_debut" id="date-debut" required readonly aria-readonly="true">
                        </div>
                        <div class="form-group">
                            <label for="date-fin">Date de fin *</label>
                            <input type="date" name="date_fin" id="date-fin" required readonly aria-readonly="true">
                        </div>
                    </div>
                    <div id="rental-summary" class="rental-summary" style="display: none;"></div>
                    <div class="btn-wrap">
                        <button type="submit" class="btn" id="btn-confirm-rental">Confirmer la location</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                    </div>
                </form>
                <p id="calendar-hint" class="hint" style="margin-top: 16px;">chargement…</p>
            </div>
        </div>
    </div>

    <script>
(function () {
    const API_BOOKED_RAW = <?= json_encode(app_url('cars/rent/api/booked-dates'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;

    function bookingApiUrl(carId) {
        var base = String(API_BOOKED_RAW || '');
        try {
            if (/^https?:\/\//i.test(base)) {
                base = String(new URL(base).href);
            } else if (base.charAt(0) === '/') {
                base = window.location.origin + base;
            } else {
                base = new URL(base, window.location.href).href;
            }
        } catch (e) {
            base = String(API_BOOKED_RAW || '');
        }
        return base + (base.indexOf('?') === -1 ? '?' : '&') + 'car_id=' + encodeURIComponent(String(carId));
    }

    let selectedCarId = null;
    let selectedDates = [];
    let bookedDates = [];
    let bookedSet = new Set();
    let calendarView = { month: new Date().getMonth(), year: new Date().getFullYear() };
    let dailyPrice = 0;

    function normalizeBookedList(raw) {
        if (!Array.isArray(raw)) return [];
        return raw.map(function (d) {
            if (typeof d !== 'string') return '';
            var m = String(d).match(/^(\d{4})-(\d{2})-(\d{2})/);
            return m ? m[1] + '-' + m[2] + '-' + m[3] : '';
        }).filter(Boolean);
    }

    function syncBookedSet() {
        bookedSet = new Set(bookedDates);
    }

    function countFreeDaysInMonth(month, year) {
        var n = 0;
        var d = new Date(year, month, 1);
        var last = new Date(year, month + 1, 0);
        while (d <= last) {
            var ds = formatLocalYMD(d);
            if (!isPastDate(d) && !bookedSet.has(ds)) n++;
            d.setDate(d.getDate() + 1);
        }
        return n;
    }

    function formatLocalYMD(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }

    function parseYMD(s) {
        const p = s.split('-').map(Number);
        return new Date(p[0], p[1] - 1, p[2]);
    }

    function startOfDay(d) {
        return new Date(d.getFullYear(), d.getMonth(), d.getDate());
    }

    function isPastDate(d) {
        const today = startOfDay(new Date());
        return startOfDay(d) < today;
    }

    function isSameDay(a, b) {
        return a.getFullYear() === b.getFullYear() &&
            a.getMonth() === b.getMonth() &&
            a.getDate() === b.getDate();
    }

    function monthName(m) {
        return ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'][m];
    }

    function setHint(text) {
        const el = document.getElementById('calendar-hint');
        if (el) el.textContent = text;
    }

    function daysBetweenInclusive(startStr, endStr) {
        const a = parseYMD(startStr).getTime();
        const b = parseYMD(endStr).getTime();
        return Math.max(1, Math.round((b - a) / 86400000));
    }

    function rangeConflicts(startStr, endStr) {
        let d = parseYMD(startStr);
        const end = parseYMD(endStr);
        while (d <= end) {
            const ds = formatLocalYMD(d);
            if (bookedSet.has(ds)) return true;
            if (isPastDate(d)) return true;
            d = new Date(d.getFullYear(), d.getMonth(), d.getDate() + 1);
        }
        return false;
    }

    window.selectCar = function (carId, carName, pricePerDay) {
        selectedCarId = carId;
        selectedDates = [];
        dailyPrice = typeof pricePerDay === 'number' && !isNaN(pricePerDay) ? pricePerDay : 0;

        const now = new Date();
        calendarView.month = now.getMonth();
        calendarView.year = now.getFullYear();

        document.getElementById('modal-title').textContent = 'Disponibilités — ' + carName;
        document.getElementById('selected-car-id').value = String(carId);
        document.getElementById('rental-form').style.display = 'none';
        document.getElementById('rental-summary').style.display = 'none';
        const hint = document.getElementById('calendar-hint');
        if (hint) hint.style.display = 'block';

        const modal = document.getElementById('calendar-modal');
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');

        document.getElementById('calendar-container').innerHTML = '<div class="calendar-loading">Chargement des disponibilités…</div>';
        setHint('Choisissez une première date, puis une seconde pour définir la période.');

        loadBookedDates(carId);
    };

    window.closeModal = function () {
        const modal = document.getElementById('calendar-modal');
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        selectedDates = [];
        document.getElementById('rental-form').style.display = 'none';
        document.getElementById('rental-summary').style.display = 'none';
        document.getElementById('calendar-container').innerHTML = '';
        setHint('');
    };

    function loadBookedDates(carId) {
        const url = bookingApiUrl(carId);
        fetch(url, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
            .then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function (data) {
                if (data.error) {
                    bookedDates = [];
                    syncBookedSet();
                    generateCalendar();
                    setHint(String(data.error));
                    return;
                }
                bookedDates = normalizeBookedList(data.booked_dates);
                syncBookedSet();
                generateCalendar();
                setHint('Première étape : cliquez sur une date verte (libre). Deuxième clic : date de fin — la période inclut ces deux jours.');
            })
            .catch(function () {
                bookedDates = [];
                syncBookedSet();
                generateCalendar();
                setHint('Impossible de charger les réservations. Rechargez la page ou réessayez plus tard.');
            });
    }

    window.changeMonth = function (delta) {
        calendarView.month += delta;
        if (calendarView.month > 11) {
            calendarView.month = 0;
            calendarView.year++;
        } else if (calendarView.month < 0) {
            calendarView.month = 11;
            calendarView.year--;
        }
        selectedDates = [];
        document.getElementById('rental-form').style.display = 'none';
        document.getElementById('rental-summary').style.display = 'none';
        generateCalendar();
        setHint('Première étape : cliquez sur le jour de départ. Deuxième clic : jour de retour.');
    };

    function generateCalendar() {
        const m = calendarView.month;
        const y = calendarView.year;
        var container = document.getElementById('calendar-container');
        if (!container) return;

        let html = '<div class="calendar-nav">';
        html += '<button type="button" class="btn btn-secondary" onclick="changeMonth(-1)">← Mois précédent</button>';
        html += '<span id="current-month-year">' + monthName(m) + ' ' + y + '</span>';
        html += '<button type="button" class="btn btn-secondary" onclick="changeMonth(1)">Mois suivant →</button>';
        html += '</div>';

        html += '<div class="calendar-header">';
        ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'].forEach(function (day) {
            html += '<div class="calendar-day">' + day + '</div>';
        });
        html += '</div>';

        var libres = countFreeDaysInMonth(m, y);
        html += '<div class="calendar-legend" role="group" aria-label="Légende du calendrier">';
        html += '<span class="calendar-legend-item"><span class="calendar-legend-swatch valid" aria-hidden="true"></span> Date libre (réservable)</span>';
        html += '<span class="calendar-legend-item"><span class="calendar-legend-swatch booked" aria-hidden="true"></span> Déjà réservé</span>';
        html += '<span class="calendar-legend-item"><span class="calendar-legend-swatch past" aria-hidden="true"></span> Passée / indisponible</span>';
        html += '<span class="calendar-legend-item"><span class="calendar-legend-swatch other" aria-hidden="true"></span> Autre mois</span>';
        html += '<div class="calendar-legend-count">' + libres + ' jour' + (libres > 1 ? 's' : '') + ' libre' + (libres > 1 ? 's' : '') + ' ce mois-ci</div>';
        html += '</div>';

        html += '<div class="calendar" id="calendar-grid">';
        html += generateMonthCells(m, y);
        html += '</div>';

        try {
            container.innerHTML = html;
        } catch (e) {
            container.innerHTML = '<p class="calendar-loading">Erreur d’affichage du calendrier. Rechargez la page.</p>';
        }
    }

    function generateMonthCells(month, year) {
        const first = new Date(year, month, 1);
        const last = new Date(year, month + 1, 0);
        let cur = new Date(first);
        cur.setDate(cur.getDate() - first.getDay());

        let html = '';
        const today = startOfDay(new Date());

        for (let week = 0; week < 6; week++) {
            for (let d = 0; d < 7; d++) {
                const dateStr = formatLocalYMD(cur);
                const inMonth = cur.getMonth() === month;
                const past = isPastDate(cur);
                const booked = bookedSet.has(dateStr);
                const isToday = isSameDay(cur, today);

                let cls = 'calendar-date';
                let aria = '';
                if (!inMonth) {
                    cls += ' muted';
                    aria = 'Hors mois affiché';
                } else if (past) {
                    cls += ' unavailable past-date';
                    aria = 'Date passée, non sélectionnable';
                } else if (booked) {
                    cls += ' unavailable booked-date';
                    aria = 'Déjà réservé, non disponible';
                } else {
                    cls += ' available';
                    aria = 'Date libre, cliquable pour réserver';
                }
                if (isToday && inMonth) cls += ' today';

                const canClick = inMonth && !past && !booked;
                const title = aria;

                html += '<div class="' + cls + '" data-date="' + dateStr + '"';
                if (title) html += ' title="' + title.replace(/"/g, '&quot;') + '"';
                if (canClick) html += ' aria-label="' + title.replace(/"/g, '&quot;') + ' — ' + dateStr + '"';
                if (canClick) {
                    html += ' onclick="selectDate(\'' + dateStr + '\')" role="button" tabindex="0"';
                    html += ' onkeydown="if(event.key===\'Enter\'||event.key===\' \'){event.preventDefault();selectDate(\'' + dateStr + '\');}"';
                }
                html += '>';
                html += '<span class="cell-num">' + cur.getDate() + '</span>';
                if (booked && inMonth && !past) {
                    html += '<span class="cell-label">Rés.</span>';
                }
                html += '</div>';

                cur.setDate(cur.getDate() + 1);
            }
            if (cur > last && cur.getDay() === 0) break;
        }
        return html;
    }

    window.selectDate = function (dateStr) {
        const cell = document.querySelector('.calendar-date[data-date="' + dateStr + '"]');
        if (!cell || cell.classList.contains('unavailable') || cell.classList.contains('muted')) return;

        if (selectedDates.length === 0) {
            selectedDates = [dateStr];
            cell.classList.add('selected');
            setHint('Deuxième clic : date de fin (incluse).');
            return;
        }

        if (selectedDates.length === 1) {
            let a = selectedDates[0];
            let b = dateStr;
            if (parseYMD(b) < parseYMD(a)) {
                var t = a; a = b; b = t;
            }

            if (rangeConflicts(a, b)) {
                setHint('La période choisie chevauche une date déjà réservée ou invalide. Choisissez d’autres dates.');
                clearSelectionDom();
                selectedDates = [];
                return;
            }

            selectedDates = [a, b];
            paintRange(a, b);
            showRentalForm();
            setHint('Vérifiez le récapitulatif puis confirmez.');
            return;
        }

        clearSelectionDom();
        selectedDates = [dateStr];
        cell.classList.add('selected');
        setHint('Deuxième clic : date de fin (incluse).');
    };

    function clearSelectionDom() {
        document.querySelectorAll('.calendar-date.selected').forEach(function (el) {
            el.classList.remove('selected');
        });
    }

    function paintRange(startStr, endStr) {
        clearSelectionDom();
        let d = parseYMD(startStr);
        const end = parseYMD(endStr);
        while (d <= end) {
            const ds = formatLocalYMD(d);
            const el = document.querySelector('.calendar-date[data-date="' + ds + '"]');
            if (el && !el.classList.contains('unavailable') && !el.classList.contains('muted')) {
                el.classList.add('selected');
            }
            d = new Date(d.getFullYear(), d.getMonth(), d.getDate() + 1);
        }
    }

    function formatMoney(n) {
        return n.toFixed(2).replace('.', ',') + ' €';
    }

    function showRentalForm() {
        if (selectedDates.length !== 2) return;
        var start = selectedDates[0];
        var end = selectedDates[1];
        document.getElementById('date-debut').value = start;
        document.getElementById('date-fin').value = end;

        var billableDays = daysBetweenInclusive(start, end);
        var total = billableDays * dailyPrice;

        var sum = document.getElementById('rental-summary');
        sum.style.display = 'block';
        sum.innerHTML =
            '<strong>Récapitulatif</strong><br>' +
            'Période : du <strong>' + start + '</strong> au <strong>' + end + '</strong><br>' +
            'Durée facturée : <strong>' + billableDays + '</strong> jour' + (billableDays > 1 ? 's' : '') +
            ' × ' + formatMoney(dailyPrice) + ' / jour<br>' +
            'Total estimé : <strong>' + formatMoney(total) + '</strong>';

        document.getElementById('rental-form').style.display = 'block';
    }

    document.addEventListener('click', function (event) {
        var modal = document.getElementById('calendar-modal');
        if (event.target === modal) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('calendar-modal');
            if (modal && modal.classList.contains('is-open')) closeModal();
        }
    });

    function openCalendarFromCard(btn) {
        if (!btn || !btn.dataset || !btn.dataset.carId) return;
        var id = parseInt(btn.dataset.carId, 10);
        if (!id) return;
        var name = btn.dataset.carLabel || '';
        var raw = String(btn.dataset.dailyPrice || '0').replace(',', '.');
        var price = parseFloat(raw, 10);
        if (isNaN(price)) price = 0;
        window.selectCar(id, name, price);
    }

    var rentGrid = document.getElementById('cars-rent-grid');
    if (rentGrid) {
        rentGrid.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest ? e.target.closest('.car-card') : null;
            if (!btn || !rentGrid.contains(btn)) return;
            e.preventDefault();
            openCalendarFromCard(btn);
        });
        rentGrid.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            var btn = e.target && e.target.closest ? e.target.closest('.car-card') : null;
            if (!btn || !rentGrid.contains(btn)) return;
            e.preventDefault();
            openCalendarFromCard(btn);
        });
    }
})();
    </script>
</body>
</html>
