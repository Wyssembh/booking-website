CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    etoiles INT DEFAULT 3,
    prix_nuit DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_hotels_ville (ville),
    INDEX idx_hotels_nom (nom)
);

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hotel_id INT NOT NULL,
    chambre VARCHAR(100) NOT NULL,
    date_arrivee DATE NOT NULL,
    date_depart DATE NOT NULL,
    nb_personnes INT NOT NULL DEFAULT 1,
    statut ENUM('en_attente', 'confirmee', 'annulee') DEFAULT 'en_attente',
    prix_total DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_reservations_hotel_id (hotel_id),
    INDEX idx_reservations_user_status (user_id, statut),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(180) NOT NULL,
    hotel_id INT NOT NULL,
    chanteur VARCHAR(120) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_events_hotel_id (hotel_id),
    INDEX idx_events_date_debut (date_debut),
    INDEX idx_events_date_fin (date_fin),
    FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS taxi_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reservation_id INT NOT NULL,
    adresse_depart VARCHAR(255) NOT NULL,
    adresse_arrivee VARCHAR(255) NOT NULL,
    date_heure DATETIME NOT NULL,
    type VARCHAR(50) NOT NULL,
    nb_passagers INT NOT NULL DEFAULT 1,
    statut ENUM('en_attente', 'confirmee', 'annulee') DEFAULT 'en_attente',
    prix_total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_reservation_id (reservation_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(100) NOT NULL,
    modele VARCHAR(100) NOT NULL,
    type ENUM('economique', 'compact', 'berline', 'suv', 'luxe') DEFAULT 'economique',
    portes INT NOT NULL DEFAULT 4,
    carburant ENUM('essence', 'diesel', 'hybride', 'electrique') DEFAULT 'essence',
    prix_par_jour DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    statut ENUM('disponible', 'louee', 'entretien') DEFAULT 'disponible',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS car_rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'annulee', 'terminee') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
);

INSERT IGNORE INTO hotels (nom, ville, adresse, description, image_url, etoiles, prix_nuit)
VALUES
    ('Seabel Rym Beach', 'Djerba', 'Zone Touristique, Djerba', 'Hotel club en bord de mer avec animations et activites.', 'https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/vignette_seabel_rym-20.jpg', 4, 120.00),
    ('Seabel Aladin', 'Djerba', 'Zone Touristique, Djerba', 'Hotel familial tout compris avec espaces piscine et loisirs.', 'https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/vignette_seabel_aladin-21.jpg', 3, 90.00),
    ('Seabel Alhambra', 'Port El Kantaoui', 'Port El Kantaoui, Sousse', 'Resort spacieux avec golf, spa et services premium.', 'https://slelguoygbfzlpylpxfs.supabase.co/storage/v1/object/public/test-clones/bacaa8ed-efd0-432f-a0ac-5a712ea986ef-seabelhotels-com/assets/images/vignette_seabel_alhambra-22.jpg', 4, 150.00);

INSERT IGNORE INTO cars (marque, modele, type, portes, carburant, prix_par_jour, statut)
VALUES
    ('Renault', 'Clio', 'economique', 5, 'essence', 35.00, 'disponible'),
    ('Peugeot', '308', 'compact', 5, 'diesel', 45.00, 'disponible'),
    ('Toyota', 'RAV4', 'suv', 5, 'hybride', 70.00, 'disponible');

-- Admin par défaut (mot de passe: admin123)
INSERT IGNORE INTO users (nom, prenom, email, password, role)
VALUES ('Admin', 'Seabel', 'admin@seabel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');


