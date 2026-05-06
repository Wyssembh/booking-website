<?php $taxi_types_json = json_encode($taxi_types); ?>
<?php $confirmed_reservations_json = json_encode($confirmed_reservations ?? []); ?>
<?php $available_reservations_json = json_encode($available_reservations ?? []); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation de Taxi</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-client layout-seabel-reservation">
    <div class="topbar">
        <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel">
        <div class="topbar-right">
            <span>Bonjour, <?= htmlspecialchars((string) ($_SESSION['prenom'] ?? 'Client')) ?></span>
            <a href="<?= htmlspecialchars(app_url('reservation')) ?>">Hotel</a>
            <a href="<?= htmlspecialchars(app_url('logout')) ?>">Deconnexion</a>
        </div>
    </div>

    <div class="container">
        <h2>Reservation de Taxi</h2>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>
            <?php if (empty($can_book_taxi)): ?><div class="alert alert-error">Vous devez avoir au moins une reservation d'hotel confirmee pour reserver un taxi.</div><?php endif; ?>
            <?php if (!empty($can_book_taxi) && empty($available_reservations)): ?><div class="alert alert-error">Vous avez deja une reservation de taxi pour toutes vos reservations d'hotel confirmees.</div><?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(app_url('taxi')) ?>" id="taxiForm" <?= (empty($can_book_taxi) || empty($available_reservations)) ? 'style="opacity:0.6;pointer-events:none;"' : '' ?>>
                <div class="form-grid">
                    <div class="form-group full">
                        <label>Reservation hotel associe</label>
                        <select name="reservation_id" id="reservation_id" required>
                            <?php if (empty($available_reservations)): ?>
                                <option value="">Aucune reservation disponible</option>
                            <?php else: ?>
                                <?php foreach ($available_reservations as $reservation): ?>
                                    <option value="<?= (int) $reservation['id'] ?>" <?= ((int) $reservation['id'] === (int) ($last_confirmed_reservation['id'] ?? 0)) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($reservation['hotel'] . ' - ' . date('d/m/Y', strtotime($reservation['date_arrivee']))) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Adresse de depart</label>
                        <select name="adresse_depart" id="adresse_depart" required>
                            <option value="">-- Choisir le point de depart --</option>
                            <option value="Aeroport Tunis-Carthage">Aeroport</option>
                            <option value="Gare Tunis">Gare</option>
                        </select>
                    </div>
                    <div class="form-group full">
                        <label>Adresse d'arrivee</label>
                        <input type="text" name="adresse_arrivee" id="adresse_arrivee" required placeholder="Ex: Aeroport Tunis-Carthage" value="<?= htmlspecialchars((string) ($last_confirmed_reservation['hotel'] ?? '')) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Date de prise en charge</label>
                        <input type="date" name="date_arrivee" id="date_arrivee" required value="<?= htmlspecialchars((string) ($last_confirmed_reservation ? date('Y-m-d', strtotime($last_confirmed_reservation['date_arrivee'])) : '')) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Heure de prise en charge</label>
                        <input type="time" name="heure_arrivee" id="heure_arrivee" required value="<?= htmlspecialchars((string) ($last_confirmed_reservation ? date('H:i', strtotime($last_confirmed_reservation['date_arrivee'])) : '')) ?>">
                    </div>
                    <div class="form-group">
                        <label>Type de taxi</label>
                        <select name="type" id="type" required>
                            <?php foreach ($taxi_types as $type => $prix): ?>
                                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?> - <?= htmlspecialchars((string) $prix) ?> EUR</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre de passagers</label>
                        <input type="number" name="nb_passagers" id="nb_passagers" min="1" max="6" value="1">
                    </div>
                    <div class="form-group" style="justify-content: flex-end;">
                        <div class="prix-preview" id="prixPreview">Estimation : --</div>
                    </div>
                    <div class="form-group full" style="align-items: flex-start;">
                        <button type="submit" class="btn" <?= (empty($can_book_taxi) || empty($available_reservations)) ? 'disabled' : '' ?>>Confirmer la reservation</button>
                    </div>
                </div>
            </form>
        </div>

        <h2>Mes reservations de taxi</h2>
        <div class="card">
            <?php if (empty($mes_reservations)): ?>
                <p style="color:#999; text-align:center; padding: 20px;">Aucune reservation de taxi pour le moment.</p>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Depart</th>
                        <th>Arrivee</th>
                        <th>Date / Heure</th>
                        <th>Type</th>
                        <th>Passagers</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mes_reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $r['adresse_depart']) ?></td>
                        <td><?= htmlspecialchars((string) $r['adresse_arrivee']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime((string) $r['date_heure'])) ?></td>
                        <td><?= htmlspecialchars(ucfirst((string) $r['type'])) ?></td>
                        <td><?= (int) $r['nb_passagers'] ?></td>
                        <td><?= number_format((float) $r['prix_total'], 2) ?> EUR</td>
                        <td>
                            <a href="<?= htmlspecialchars(app_url('taxi/edit') . '&id=' . (int) $r['id']) ?>" class="btn btn-secondary" style="margin-right: 8px; padding: 8px 14px; font-size: 12px;">Modifier</a>
                            <form method="POST" action="<?= htmlspecialchars(app_url('taxi/delete')) ?>" style="display:inline;">
                                <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 8px 14px; font-size: 12px;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const taxiTypes = <?= $taxi_types_json ?: '{}' ?>;
        const confirmedReservations = <?= $confirmed_reservations_json ?: '[]' ?>;
        const availableReservations = <?= $available_reservations_json ?: '[]' ?>;
        const typeSel = document.getElementById('type');
        const passagersIn = document.getElementById('nb_passagers');
        const preview = document.getElementById('prixPreview');
        const reservationSelect = document.getElementById('reservation_id');
        const arriveeInput = document.getElementById('adresse_arrivee');
        const dateInput = document.getElementById('date_arrivee');

        function updatePreview() {
            const type = typeSel.value;
            const passagers = Number(passagersIn.value) || 1;
            const base = taxiTypes[type] || 35;
            const total = base + Math.max(0, passagers - 1) * 10;
            preview.textContent = `Estimation : ${total.toFixed(2)} EUR`;
            preview.style.display = 'block';
        }

        function updateReservationDetails() {
            if (!reservationSelect) return;
            const selectedId = Number(reservationSelect.value);
            const reservation = availableReservations.find((r) => Number(r.id) === selectedId);
            if (reservation) {
                arriveeInput.value = reservation.hotel || '';
                if (reservation.date_arrivee) {
                    dateInput.value = reservation.date_arrivee;
                }
            }
        }

        [typeSel, passagersIn].forEach((el) => el.addEventListener('change', updatePreview));
        if (reservationSelect) {
            reservationSelect.addEventListener('change', updateReservationDetails);
        }
        updatePreview();
        updateReservationDetails();
    </script>
</body>
</html>
