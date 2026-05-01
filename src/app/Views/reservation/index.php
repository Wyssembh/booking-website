<?php $tarifs_json = json_encode($tarifs); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-client layout-seabel-reservation">
    <div class="topbar">
        <img src="https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/seabel_hotels_logo-11.svg" alt="Seabel">
        <div class="topbar-right">
            <span>Bonjour, <?= htmlspecialchars((string) ($_SESSION['prenom'] ?? 'Client')) ?></span>
            <a href="../index.php"><- Site</a>
            <a href="<?= htmlspecialchars(app_url('logout')) ?>">Deconnexion</a>
        </div>
    </div>

    <div class="container">
        <h2>Nouvelle reservation</h2>

        <div class="card">
            <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if (!empty($error)): ?><div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div><?php endif; ?>
            <?php if (!empty($has_pending_reservation)): ?><div class="alert alert-error">Vous avez deja une reservation d'hotel en attente. Attendez la confirmation avant d'en creer une nouvelle.</div><?php endif; ?>

            <form method="POST" action="<?= htmlspecialchars(app_url('reservation')) ?>" id="reservForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Hotel</label>
                        <select name="hotel" id="hotel" required>
                            <option value="">-- Choisir un hotel --</option>
                            <?php foreach (array_keys($tarifs) as $hotelName): ?>
                                <option value="<?= htmlspecialchars($hotelName) ?>"><?= htmlspecialchars($hotelName) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type de chambre</label>
                        <select name="chambre" id="chambre" required>
                            <option value="">-- Choisir d'abord un hotel --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date d'arrivee</label>
                        <input type="date" name="date_arrivee" id="date_arrivee" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label>Date de depart</label>
                        <input type="date" name="date_depart" id="date_depart" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                    </div>
                    <div class="form-group">
                        <label>Nombre de personnes</label>
                        <input type="number" name="nb_personnes" id="nb_personnes" min="1" max="6" value="1">
                    </div>
                    <div class="form-group" style="justify-content: flex-end;">
                        <div class="prix-preview" id="prixPreview">Estimation : --</div>
                    </div>
                    <div class="form-group full" style="align-items: flex-start;">
                        <button type="submit" class="btn" <?= !empty($has_pending_reservation) ? 'disabled' : '' ?>>Confirmer la reservation</button>
                    </div>
                </div>
            </form>
        </div>

        <h2>Mes reservations</h2>
        <div class="card">
            <?php if (empty($mes_reservations)): ?>
                <p style="color:#999; text-align:center; padding: 20px;">Aucune reservation pour le moment.</p>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Hotel</th>
                        <th>Chambre</th>
                        <th>Arrivee</th>
                        <th>Depart</th>
                        <th>Pers.</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Taxi</th>
                        <th>Location voiture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mes_reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $r['hotel']) ?></td>
                        <td><?= htmlspecialchars((string) $r['chambre']) ?></td>
                        <td><?= date('d/m/Y', strtotime((string) $r['date_arrivee'])) ?></td>
                        <td><?= date('d/m/Y', strtotime((string) $r['date_depart'])) ?></td>
                        <td><?= (int) $r['nb_personnes'] ?></td>
                        <td><?= number_format((float) $r['prix_total'], 0) ?> EUR</td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars((string) $r['statut']) ?>">
                                <?= htmlspecialchars(str_replace('_', ' ', (string) $r['statut'])) ?>
                            </span>
                        </td>
                        <td class="table-action-cell">
                            <?php if ($r['statut'] === 'confirmee'): ?>
                                <a href="<?= htmlspecialchars(app_url('taxi')) ?>" class="btn btn-small">Taxi</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="table-action-cell">
                            <?php if ($r['statut'] === 'confirmee'): ?>
                                <a href="<?= htmlspecialchars(app_url('cars/rent')) ?>" class="btn btn-secondary btn-small">Location</a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>

        </div>
    </div>

    <script>
        const tarifs = <?= $tarifs_json ?: '{}' ?>;

        const hotelSel = document.getElementById('hotel');
        const chambreSel = document.getElementById('chambre');
        const arriveeIn = document.getElementById('date_arrivee');
        const departIn = document.getElementById('date_depart');
        const preview = document.getElementById('prixPreview');

        hotelSel.addEventListener('change', () => {
            const hotel = hotelSel.value;
            chambreSel.innerHTML = '<option value="">-- Choisir une chambre --</option>';
            if (tarifs[hotel]) {
                Object.keys(tarifs[hotel]).forEach((ch) => {
                    chambreSel.innerHTML += `<option value="${ch}">${ch} - ${tarifs[hotel][ch]} EUR/nuit</option>`;
                });
            }
            updatePreview();
        });

        [chambreSel, arriveeIn, departIn].forEach((el) => el.addEventListener('change', updatePreview));

        arriveeIn.addEventListener('change', () => {
            const next = new Date(arriveeIn.value);
            next.setDate(next.getDate() + 1);
            departIn.min = next.toISOString().split('T')[0];
            if (departIn.value && departIn.value <= arriveeIn.value) {
                departIn.value = next.toISOString().split('T')[0];
            }
            updatePreview();
        });

        function updatePreview() {
            const hotel = hotelSel.value;
            const chambre = chambreSel.value;
            const arr = arriveeIn.value;
            const dep = departIn.value;

            if (hotel && chambre && arr && dep && dep > arr) {
                const nuits = Math.round((new Date(dep) - new Date(arr)) / 86400000);
                const total = tarifs[hotel][chambre] * nuits;
                preview.style.display = 'block';
                preview.textContent = `Estimation : ${total} EUR (${nuits} nuit${nuits > 1 ? 's' : ''})`;
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
