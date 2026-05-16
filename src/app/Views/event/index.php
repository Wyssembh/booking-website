<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Events - Seabel Hotels</title>
    <?php include __DIR__ . '/../partials/seabel_fonts_link.php'; ?>
    <?php include __DIR__ . '/../partials/seabel_theme_styles.php'; ?>
</head>
<body class="layout-seabel-admin layout-seabel-events">
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <main class="main">
        <div class="page-title">Gestion des events</div>
        <div class="content">
        <div class="card">
            <div class="card-title"><?= $editing_event ? 'Modifier un event' : 'Ajouter un event' ?></div>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars((string) $success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?= htmlspecialchars((string) $error) ?></div>
            <?php endif; ?>

            <?php
                $formSource = $editing_event ?: $old;
                $isEditing = $editing_event !== null;
            ?>

            <form method="POST" action="<?= htmlspecialchars(app_url('events')) ?>">
                <input type="hidden" name="event_action" value="<?= $isEditing ? 'update' : 'create' ?>">
                <?php if ($isEditing): ?>
                    <input type="hidden" name="id" value="<?= (int) $editing_event['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group full">
                        <label class="field-label">Titre de l'event</label>
                        <input class="field-input" type="text" name="titre" required value="<?= htmlspecialchars((string) ($formSource['titre'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label class="field-label">Hotel</label>
                        <?php $selectedHotel = (string) ($formSource['hotel'] ?? ''); ?>
                        <select class="field-input" name="hotel" required>
                            <option value="">-- Choisir un hotel --</option>
                            <?php foreach (($hotels ?? []) as $hotelName): ?>
                                <option value="<?= htmlspecialchars((string) $hotelName) ?>" <?= $selectedHotel === $hotelName ? 'selected' : '' ?>>
                                    <?= htmlspecialchars((string) $hotelName) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="field-label">Chanteur / artiste</label>
                        <input class="field-input" type="text" name="chanteur" required value="<?= htmlspecialchars((string) ($formSource['chanteur'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label class="field-label">Date debut</label>
                        <input class="field-input" type="date" name="date_debut" required value="<?= htmlspecialchars((string) ($formSource['date_debut'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label class="field-label">Date fin</label>
                        <input class="field-input" type="date" name="date_fin" required value="<?= htmlspecialchars((string) ($formSource['date_fin'] ?? '')) ?>">
                    </div>
                    <div class="form-group full">
                        <label class="field-label">Description</label>
                        <textarea class="field-textarea" name="description" required><?= htmlspecialchars((string) ($formSource['description'] ?? '')) ?></textarea>
                    </div>
                    <div class="form-group full">
                        <label class="field-label">Image (URL)</label>
                        <input class="field-input" type="url" name="image_url" value="<?= htmlspecialchars((string) ($formSource['image_url'] ?? '')) ?>" placeholder="https://...">
                    </div>
                    <div class="form-group full">
                        <div class="actions">
                            <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Mettre a jour' : 'Publier' ?></button>
                            <?php if ($isEditing): ?>
                                <a class="btn btn-secondary" href="<?= htmlspecialchars(app_url('events')) ?>">Annuler</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-title">Liste des events</div>

            <?php if (empty($events)): ?>
                <p style="color:#8a8a8a; font-size:13px;">Aucun event ajoute pour le moment.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Event</th>
                            <th>Dates</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($event['image_url'])): ?>
                                        <img class="event-media" src="<?= htmlspecialchars((string) $event['image_url']) ?>" alt="Image event">
                                    <?php else: ?>
                                        <div class="event-media-fallback">Sans image</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars((string) $event['titre']) ?></strong><br>
                                    <small style="color:#666;"><?= htmlspecialchars((string) $event['hotel']) ?> - <?= htmlspecialchars((string) $event['chanteur']) ?></small><br>
                                    <?php $desc = (string) $event['description']; ?>
                                    <small style="color:#9b9b9b;"><?= htmlspecialchars(strlen($desc) > 110 ? substr($desc, 0, 110) . '...' : $desc) ?></small>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime((string) $event['date_debut'])) ?><br>
                                    <small style="color:#666;">au <?= date('d/m/Y', strtotime((string) $event['date_fin'])) ?></small>
                                </td>
                                <td>
                                    <div class="mini-actions">
                                        <a class="btn btn-secondary" href="<?= htmlspecialchars(app_url('events') . '&edit=' . (int) $event['id']) ?>">Modifier</a>
                                        <form class="mini-form" method="POST" action="<?= htmlspecialchars(app_url('events')) ?>" onsubmit="return confirm('Supprimer cet event ?');">
                                            <input type="hidden" name="event_action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) $event['id'] ?>">
                                            <button class="btn btn-danger" type="submit">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        </div>
    </main>
</body>
</html>
