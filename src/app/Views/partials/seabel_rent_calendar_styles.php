<?php /** Styles calendrier + cartes voiture (page location) */ ?>
<style>
.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.car-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 0;
    overflow: hidden;
    background: #fff;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    text-align: left;
    width: 100%;
    font: inherit;
    display: flex;
    flex-direction: column;
}

.car-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.car-card:focus-visible { outline: 2px solid #0f3460; outline-offset: 3px; }

.car-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
    background: linear-gradient(145deg, #e8ecf4 0%, #dce3f0 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8a94a8;
    font-size: 13px;
    font-weight: 600;
}

.car-image img { width: 100%; height: 100%; object-fit: cover; }

.car-card-inner { padding: 20px; flex: 1; display: flex; flex-direction: column; }

.car-info h4 {
    margin: 0 0 8px 0;
    color: #0f3460;
    font-size: 18px;
    font-weight: 600;
}

.car-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    gap: 8px;
    flex-wrap: wrap;
}

.car-type {
    background: #0f3460;
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.car-specs { font-size: 12px; color: #666; }

.car-price {
    font-weight: 600;
    color: #0f3460;
    font-size: 16px;
    margin-top: auto;
    padding-top: 12px;
}

.car-cta { font-size: 11px; color: #0f3460; margin-top: 10px; opacity: 0.85; }

.modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 100000;
    width: 100%;
    min-height: 100vh;
    min-height: 100dvh;
    box-sizing: border-box;
    background-color: rgba(0, 0, 0, 0.5);
    overflow-x: hidden;
    overflow-y: auto;
    padding: 24px 16px 48px;
    align-items: flex-start;
    justify-content: center;
}

.modal.is-open { display: flex; }

.modal-content {
    background: #fff;
    margin: 0;
    padding: 0;
    border-radius: 12px;
    width: 100%;
    max-width: 800px;
    flex-shrink: 0;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    position: relative;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
}

.modal-header h3 {
    margin: 0;
    color: #0f3460;
    font-size: 1.15rem;
    line-height: 1.35;
}

.close {
    color: #888;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
    padding: 0 4px;
    border: none;
    background: none;
}

.close:hover { color: #0f3460; }

.modal-body { padding: 24px; }

.calendar-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
}

.calendar-nav span {
    font-weight: 600;
    color: #0f3460;
    min-width: 180px;
    text-align: center;
}

.calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    margin-top: 12px;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 6px;
    margin-bottom: 6px;
}

.calendar-day, .calendar-date {
    padding: 10px 6px;
    text-align: center;
    border-radius: 6px;
    font-size: 13px;
}

.calendar-day {
    font-weight: 600;
    color: #0f3460;
    background: #f5f7fb;
}

.calendar-date {
    cursor: pointer;
    border: 1px solid #e0e0e0;
    transition: background-color 0.2s;
    min-height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-direction: column;
    line-height: 1.1;
}

.calendar-date .cell-num { font-size: 14px; }

.calendar-date:hover:not(.unavailable):not(.muted) {
    background-color: #c8e6c9;
    border-color: #2e7d32;
}

.calendar-date.muted {
    opacity: 0.4;
    cursor: default;
    border-color: #eee;
    background: #f5f5f5;
    color: #aaa;
}

.calendar-date.available {
    background: linear-gradient(180deg, #e8f5e9 0%, #c8e6c9 100%);
    color: #1b5e20;
    border: 2px solid #43a047;
    font-weight: 600;
}

.calendar-date.available::after {
    content: "✓";
    position: absolute;
    bottom: 2px;
    right: 3px;
    font-size: 9px;
    color: #2e7d32;
    opacity: 0.85;
    line-height: 1;
}

.calendar-date.unavailable { cursor: not-allowed; font-weight: 500; }

.calendar-date.past-date {
    background: #eceff1;
    color: #78909c;
    border: 1px dashed #b0bec5;
    text-decoration: line-through;
    text-decoration-color: #90a4ae;
}

.calendar-date.booked-date {
    background: linear-gradient(180deg, #ffebee 0%, #ffcdd2 100%);
    color: #b71c1c;
    border: 2px solid #e53935;
}

.calendar-date.booked-date .cell-label {
    display: block;
    font-size: 8px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    margin-top: 2px;
    color: #c62828;
}

.calendar-date.selected {
    background-color: #0f3460 !important;
    color: #fff !important;
    border-color: #0f3460;
}

.calendar-date.today.available {
    box-shadow: 0 0 0 3px #0f3460, inset 0 0 0 1px #fff;
}

.calendar-date.today:not(.available) { box-shadow: inset 0 0 0 2px #546e7a; }

.calendar-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 14px 22px;
    align-items: center;
    justify-content: center;
    margin: 18px 0 8px;
    padding: 14px 16px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e8ecf1;
    font-size: 12px;
    color: #455a64;
}

.calendar-legend-item { display: inline-flex; align-items: center; gap: 8px; }

.calendar-legend-swatch {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    flex-shrink: 0;
    border: 1px solid rgba(0, 0, 0, 0.08);
}

.calendar-legend-swatch.valid {
    background: linear-gradient(180deg, #e8f5e9 0%, #c8e6c9 100%);
    border: 2px solid #43a047;
}

.calendar-legend-swatch.booked {
    background: linear-gradient(180deg, #ffebee 0%, #ffcdd2 100%);
    border: 2px solid #e53935;
}

.calendar-legend-swatch.past {
    background: #eceff1;
    border: 1px dashed #b0bec5;
}

.calendar-legend-swatch.other {
    background: #f5f5f5;
    border: 1px solid #e0e0e0;
}

.calendar-legend-count {
    width: 100%;
    text-align: center;
    font-weight: 600;
    color: #0f3460;
    font-size: 13px;
    margin-top: 4px;
}

.rental-summary {
    margin-top: 20px;
    padding: 16px 18px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e8ecf1;
    font-size: 14px;
    color: #444;
    line-height: 1.5;
}

.rental-summary strong { color: #0f3460; }

.calendar-loading { text-align: center; padding: 48px 20px; color: #666; }

#calendar-container { min-height: 200px; }

#rental-form .btn-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
    align-items: center;
}

body.layout-seabel-client-wide .table-wrap table { min-width: 640px; }

@media (max-width: 600px) {
    .modal-body { padding: 18px 16px; }
}
</style>
