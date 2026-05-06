<?php
/** Styles globaux Seabel — inclure après seabel_fonts_link.php. Body : layout-seabel-admin | layout-seabel-client | layout-seabel-auth */
?>
<style>
:root {
    --seabel-navy: #0f3460;
    --seabel-navy-dark: #16213e;
    --seabel-bg: #f5f5f5;
    --seabel-accent: #e94560;
    --seabel-card-radius: 12px;
    --seabel-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

/* ========== Admin (sidebar + zone principale) ========== */
body.layout-seabel-admin {
    font-family: 'Montserrat', sans-serif;
    background: var(--seabel-bg);
    color: #333;
    display: flex;
    min-height: 100vh;
}

body.layout-seabel-admin .main { flex: 1; margin-left: 250px; }

body.layout-seabel-admin .page-title {
    background: #fff;
    padding: 25px 35px;
    border-bottom: 1px solid #e0e0e0;
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: var(--seabel-navy);
}

body.layout-seabel-admin .content { padding: 35px; }

/* ========== Client (barre du haut + contenu) ========== */
body.layout-seabel-client {
    font-family: 'Montserrat', sans-serif;
    background: var(--seabel-bg);
    color: #333;
    min-height: 100vh;
}

body.layout-seabel-client .topbar,
body.layout-seabel-client header.topbar {
    background: var(--seabel-navy);
    color: #fff;
    padding: 14px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 100;
}

body.layout-seabel-client .topbar img,
body.layout-seabel-client header.topbar img {
    height: 35px;
    filter: brightness(0) invert(1);
}

body.layout-seabel-client .topbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 13px;
    flex-wrap: wrap;
}

body.layout-seabel-client .topbar-right a {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    transition: color 0.2s;
}

body.layout-seabel-client .topbar-right a:hover { color: #fff; }

body.layout-seabel-client .container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px 60px;
}

body.layout-seabel-client.layout-seabel-client-wide .container { max-width: 1200px; }

body.layout-seabel-client h2 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: var(--seabel-navy);
    margin-bottom: 25px;
}

/* ========== Auth (connexion / inscription) ========== */
body.layout-seabel-auth {
    font-family: 'Montserrat', sans-serif;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

body.layout-seabel-auth .login-card,
body.layout-seabel-auth > .card {
    background: #fff;
    border-radius: var(--seabel-card-radius);
    padding: 50px 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

body.layout-seabel-auth > .card { max-width: 460px; }

body.layout-seabel-auth .logo-area {
    text-align: center;
    margin-bottom: 35px;
}

body.layout-seabel-auth .logo-area img { height: 50px; margin-bottom: 10px; }

body.layout-seabel-auth .logo-area h1 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    color: #1a1a2e;
}

body.layout-seabel-auth .form-group { margin-bottom: 18px; }

body.layout-seabel-auth label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #666;
    margin-bottom: 8px;
}

body.layout-seabel-auth input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    outline: none;
    transition: border-color 0.3s;
}

body.layout-seabel-auth input:focus { border-color: var(--seabel-navy); }

body.layout-seabel-auth .btn {
    width: 100%;
    padding: 14px;
    margin-top: 10px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--seabel-navy) 0%, var(--seabel-navy-dark) 100%);
    color: #fff;
    border: none;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 10px 20px rgba(15, 52, 96, 0.2);
    transition: transform 0.2s, box-shadow 0.2s;
}

body.layout-seabel-auth .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(15, 52, 96, 0.25);
}

body.layout-seabel-auth .error,
body.layout-seabel-auth .alert-error {
    background: #fdf0f1;
    color: #7a1220;
    padding: 12px;
    border-radius: 8px;
    font-size: 13px;
    margin-bottom: 20px;
    text-align: center;
    border: 1px solid #f4c2c6;
}

body.layout-seabel-auth .success,
body.layout-seabel-auth .alert-success {
    background: #e6f7e7;
    color: #0b4d1d;
    padding: 12px;
    border-radius: 8px;
    font-size: 13px;
    margin-bottom: 20px;
    text-align: center;
    border: 1px solid #b7deb1;
}

body.layout-seabel-auth .login-link {
    text-align: center;
    margin-top: 25px;
    font-size: 13px;
    color: #666;
}

body.layout-seabel-auth .login-link a {
    color: var(--seabel-navy);
    font-weight: 600;
    text-decoration: none;
}

body.layout-seabel-auth .register-link {
    text-align: center;
    margin-top: 25px;
    font-size: 13px;
    color: #666;
}

body.layout-seabel-auth .register-link a {
    color: var(--seabel-navy);
    font-weight: 600;
    text-decoration: none;
}

body.layout-seabel-auth .back-link {
    text-align: center;
    margin-top: 15px;
}

body.layout-seabel-auth .back-link a {
    font-size: 12px;
    color: #999;
    text-decoration: none;
}

body.layout-seabel-auth .back-link a:hover { color: var(--seabel-navy); }

body.layout-seabel-auth .row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

/* ========== Sidebar (admin) ========== */
.sidebar {
    width: 250px;
    background: var(--seabel-navy);
    color: #fff;
    padding: 20px;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    z-index: 200;
}

.sidebar-logo { padding: 0 25px 30px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.sidebar-logo img { height: 35px; filter: brightness(0) invert(1); }
.sidebar-logo p {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.5);
    margin-top: 5px;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.sidebar nav { padding: 20px 0; flex: 1; }

.sidebar nav a {
    display: block;
    padding: 12px 25px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.sidebar nav a:hover,
.sidebar nav a.active {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border-left: 3px solid var(--seabel-accent);
    padding-left: 22px;
}

.sidebar-footer { padding: 20px 25px; border-top: 1px solid rgba(255, 255, 255, 0.1); }

.sidebar-footer a {
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
    text-decoration: none;
}

.sidebar-footer a:hover { color: #fff; }

/* ========== Composants communs ========== */
.card {
    background: #fff;
    border-radius: var(--seabel-card-radius);
    padding: 35px;
    box-shadow: var(--seabel-shadow);
    margin-bottom: 35px;
}

.card-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--seabel-navy);
    margin-bottom: 16px;
}

.btn {
    padding: 12px 22px;
    background: linear-gradient(135deg, var(--seabel-navy) 0%, var(--seabel-navy-dark) 100%);
    color: #fff;
    border: none;
    border-radius: 999px;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    box-shadow: 0 10px 20px rgba(15, 52, 96, 0.15);
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(15, 52, 96, 0.2);
}

.btn:focus-visible { outline: 2px solid var(--seabel-navy); outline-offset: 3px; }

.btn-secondary {
    background: #f4f6fb;
    color: var(--seabel-navy);
    box-shadow: inset 0 0 0 1px rgba(15, 52, 96, 0.08);
}

.btn-secondary:hover { background: #e9edf7; }

.btn-danger { background: #c82333; color: #fff; }
.btn-danger:hover { background: #a71d2a; }

.btn-success { background: #28a745; color: #fff; }
.btn-success:hover { background: #218838; }

.btn-warning { background: #ffc107; color: #212529; }
.btn-warning:hover { background: #e0a800; }

.btn-small { padding: 6px 14px; font-size: 11px; letter-spacing: 0.5px; }

.btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

.btn-primary {
    background: linear-gradient(135deg, var(--seabel-navy) 0%, var(--seabel-navy-dark) 100%);
    color: #fff;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group.full { grid-column: 1 / -1; }

label,
.field-label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #666;
}

input, select, textarea,
.field-input, .field-textarea {
    padding: 12px 16px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    outline: none;
    transition: border-color 0.3s;
}

.field-textarea { min-height: 120px; resize: vertical; }

input:focus, select:focus, textarea:focus,
.field-input:focus, .field-textarea:focus { border-color: var(--seabel-navy); }

input[type="file"] {
    padding: 8px;
    width: 100%;
    box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
}

.alert {
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-success { background: #e6f7e7; color: #0b4d1d; border: 1px solid #b7deb1; }
.alert-error { background: #fdf0f1; color: #7a1220; border: 1px solid #f4c2c6; }

.table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

table { width: 100%; border-collapse: collapse; font-size: 13px; }

th {
    background: var(--seabel-navy);
    color: #fff;
    padding: 12px 15px;
    text-align: left;
    font-weight: 500;
    letter-spacing: 0.5px;
}

td { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
tr:hover td { background: #f9f9f9; }

.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
}

.badge-disponible { background: #d1e7dd; color: #0a3622; }
.badge-louee { background: #fff3cd; color: #856404; }
.badge-entretien { background: #f8d7da; color: #842029; }
.badge-en_attente { background: #fff3cd; color: #856404; }
.badge-confirmee { background: #d1e7dd; color: #0a3622; }
.badge-annulee { background: #f8d7da; color: #842029; }
.badge-terminee { background: #e2e3e5; color: #383d41; }
.badge-attente { background: #fff3cd; color: #856404; }

.action-buttons { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

.empty-state { text-align: center; padding: 40px 24px; color: #999; font-size: 15px; }

.hint { font-size: 13px; color: #666; margin-top: 12px; line-height: 1.45; }

.info-text { font-size: 12px; color: #666; }

/* Dashboard */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 35px;
}

.kpi-card {
    background: #fff;
    border-radius: var(--seabel-card-radius);
    padding: 25px;
    box-shadow: var(--seabel-shadow);
    border-top: 4px solid var(--seabel-navy);
    text-align: center;
}

.kpi-card.green { border-top-color: #28a745; }
.kpi-card.orange { border-top-color: #fd7e14; }
.kpi-card.red { border-top-color: #dc3545; }
.kpi-card.gold { border-top-color: #ffc107; }

.kpi-label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 10px;
}

.kpi-value { font-size: 28px; font-weight: 700; color: var(--seabel-navy); }
.kpi-card.green .kpi-value { color: #28a745; }
.kpi-card.orange .kpi-value { color: #fd7e14; }
.kpi-card.red .kpi-value { color: #dc3545; }
.kpi-card.gold .kpi-value { color: #856404; }

.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
    margin-bottom: 35px;
}

.chart-card {
    background: #fff;
    border-radius: var(--seabel-card-radius);
    padding: 25px;
    box-shadow: var(--seabel-shadow);
}

.chart-title { font-size: 16px; font-weight: 600; color: var(--seabel-navy); margin-bottom: 20px; }

.search-input {
    padding: 9px 15px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    outline: none;
    width: 250px;
    max-width: 100%;
}

.search-input:focus { border-color: var(--seabel-navy); }

.statut-form select { padding: 5px 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 12px; cursor: pointer; }

.statut-form button {
    padding: 5px 10px;
    background: var(--seabel-navy);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 11px;
    cursor: pointer;
    margin-left: 5px;
}

.actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

.mini-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.mini-form { display: inline; }

.event-media {
    width: 54px;
    height: 54px;
    border-radius: 8px;
    object-fit: cover;
    display: block;
}

.event-media-fallback {
    width: 54px;
    height: 54px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    text-transform: uppercase;
    color: #666;
    background: #f1f5f9;
    border: 1px dashed #cbd5e1;
}

/* Réservation hôtel (client) */
.prix-preview {
    background: #f0f4ff;
    border: 1.5px solid var(--seabel-navy);
    border-radius: 8px;
    padding: 15px 20px;
    font-size: 15px;
    font-weight: 600;
    color: var(--seabel-navy);
    text-align: center;
    display: none;
}

.table-action-cell { white-space: nowrap; }

/* Taxi client — champs */
body.layout-seabel-client input[type="datetime-local"],
body.layout-seabel-client input[type="time"],
body.layout-seabel-client input[type="text"],
body.layout-seabel-client input[type="number"] {
    font-family: 'Montserrat', sans-serif;
}

.reservation-info {
    margin-bottom: 25px;
    padding: 18px 20px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    background: #fafafa;
}

.reservation-info p { margin-bottom: 10px; font-size: 14px; }

@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    body.layout-seabel-admin .main { margin-left: 0; }
    .charts-grid { grid-template-columns: 1fr; }
}

body.layout-seabel-client.layout-seabel-reservation .form-grid {
    grid-template-columns: 1fr 1fr;
}

body.layout-seabel-events .form-grid {
    grid-template-columns: 1fr 1fr;
}

@media (max-width: 950px) {
    body.layout-seabel-events .form-grid { grid-template-columns: 1fr; }
    body.layout-seabel-events .form-group.full { grid-column: 1; }
}

@media (max-width: 600px) {
    body.layout-seabel-client .container { margin-top: 24px; }
    body.layout-seabel-client h2 { font-size: 22px; }
    .form-grid { grid-template-columns: 1fr; }
    body.layout-seabel-auth .row { grid-template-columns: 1fr; }
    body.layout-seabel-client.layout-seabel-reservation .form-grid { grid-template-columns: 1fr; }
}
</style>
