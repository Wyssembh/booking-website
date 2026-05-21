-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 21 mai 2026 à 08:52
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6
CREATE DATABASE IF NOT EXISTS testing;
USE testing;
  SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
  START TRANSACTION;
  SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `testing`
--

-- --------------------------------------------------------

--
-- Structure de la table `cars`
--

CREATE TABLE `cars` (
  `id` int NOT NULL,
  `marque` varchar(100) NOT NULL,
  `modele` varchar(100) NOT NULL,
  `type` enum('economique','compact','berline','suv','luxe') DEFAULT 'economique',
  `portes` int NOT NULL DEFAULT '4',
  `carburant` enum('essence','diesel','hybride','electrique') DEFAULT 'essence',
  `prix_par_jour` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `statut` enum('disponible','louee','entretien') DEFAULT 'disponible',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cars`
--

INSERT INTO `cars` (`id`, `marque`, `modele`, `type`, `portes`, `carburant`, `prix_par_jour`, `image`, `statut`, `created_at`) VALUES
(1, 'Hyundai', 'i20', 'compact', 5, 'essence', 38.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8jur1MzxoZpI82RpqgsJwzyOevXyzxvq_6g&s', 'disponible', '2026-05-10 13:13:33'),
(2, 'Volkswagen', 'Golf', 'compact', 5, 'diesel', 52.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSWqZfQaD85d9RGMZJ3Pxh7jwbVoHD4IJkb8w&s', 'disponible', '2026-05-10 13:13:33'),
(3, 'Kia', 'Sportage', 'suv', 5, 'hybride', 78.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4Lfbz8kRJ5aD2x1qviFXeyYW3p7-K-LeSZg&s', 'disponible', '2026-05-10 13:13:33'),
(4, 'bmw', 'x1 U11', 'luxe', 4, 'essence', 120.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQfgDWqRHHIgKKUSDWYBG6HW6zK1OcXsxDHwg&s', 'disponible', '2026-05-15 19:29:52'),
(5, 'bmw', 'x5', 'economique', 4, 'essence', 50.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSVPqbQaynNvl_d8sW1IImCTCCt0vc_24Yg9A&s', 'disponible', '2026-05-16 14:39:38');

-- --------------------------------------------------------

--
-- Structure de la table `car_rentals`
--

CREATE TABLE `car_rentals` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `car_id` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','confirmee','annulee','terminee') DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `car_rentals`
--

INSERT INTO `car_rentals` (`id`, `user_id`, `car_id`, `date_debut`, `date_fin`, `prix_total`, `statut`, `created_at`) VALUES
(1, 1, 1, '2026-06-16', '2026-06-18', 76.00, 'terminee', '2026-05-10 13:13:33'),
(2, 2, 2, '2026-07-06', '2026-07-10', 208.00, 'terminee', '2026-05-10 13:13:33'),
(3, 3, 3, '2026-08-11', '2026-08-14', 234.00, 'terminee', '2026-05-10 13:13:33'),
(4, 5, 1, '2026-05-18', '2026-05-21', 114.00, 'terminee', '2026-05-16 14:35:17');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `titre` varchar(180) NOT NULL,
  `hotel_id` int NOT NULL,
  `chanteur` varchar(120) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `titre`, `hotel_id`, `chanteur`, `date_debut`, `date_fin`, `description`, `image_url`, `created_at`) VALUES
(1, 'Soiree DJ Sunset', 1, 'DJ Oumaima', '2026-06-20', '2026-06-22', 'Soiree musicale en bord de mer avec animation live.', 'https://picsum.photos/seed/event1/1200/800', '2026-05-10 13:13:33'),
(2, 'Nuit Orientale', 2, 'Groupe Sama', '2026-07-12', '2026-07-13', 'Spectacle oriental et gastronomie locale.', 'https://picsum.photos/seed/event2/1200/800', '2026-05-10 13:13:33'),
(3, 'Festival Summer', 1, 'Live Band', '2026-08-05', '2026-08-07', 'Concerts et shows quotidiens pour toute la famille.', 'https://picsum.photos/seed/event3/1200/800', '2026-05-10 13:13:33');

-- --------------------------------------------------------

--
-- Structure de la table `hotels`
--

CREATE TABLE `hotels` (
  `id` int NOT NULL,
  `nom` varchar(150) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `etoiles` int DEFAULT '3',
  `prix_nuit` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `hotels`
--

INSERT INTO `hotels` (`id`, `nom`, `ville`, `adresse`, `description`, `image_url`, `etoiles`, `prix_nuit`, `created_at`) VALUES
(1, 'Seabel Rym Beach', 'Djerba', 'Zone Touristique, Djerba', 'Hotel club en bord de mer avec animations et activites.', 'https://picsum.photos/seed/rymbeach/1200/800', 4, 120.00, '2026-05-10 13:13:33'),
(2, 'Seabel Aladin', 'Djerba', 'Zone Touristique, Djerba', 'Hotel familial tout compris avec espaces piscine et loisirs.', 'https://picsum.photos/seed/aladin/1200/800', 3, 90.00, '2026-05-10 13:13:33'),
(3, 'Seabel Alhambra', 'Port El Kantaoui', 'Port El Kantaoui, Sousse', 'Resort spacieux avec golf, spa et services premium.', 'https://dynamic-media-cdn.tripadvisor.com/media/photo-o/08/1a/8a/1c/seabel-alhambra-beach.jpg?w=900&h=500&s=1', 4, 150.00, '2026-05-10 13:13:33'),
(4, 'ibreostar mehari', 'sousse', 'sousse kantaoui', 'le charme discret', 'https://iberostarselectionbayportelkantaoui.tn-hotel.com/data/Photos/1080x700w/17227/1722747/1722747237.JPEG', 5, 350.00, '2026-05-10 13:29:13'),
(5, 'Royal Garden Palace', 'Djerba', 'Zone touristique djerba', 'hotel 5 étoire au coueur du zone touristique a 2 klm de midoun', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQUgafq1uFVJeopcXbmFGWLfpZi0fFpLbb1cg&s', 5, 250.00, '2026-05-15 19:27:40');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `hotel_id` int NOT NULL,
  `chambre` varchar(100) NOT NULL,
  `date_arrivee` date NOT NULL,
  `date_depart` date NOT NULL,
  `nb_personnes` int NOT NULL DEFAULT '1',
  `statut` enum('en_attente','confirmee','annulee') DEFAULT 'en_attente',
  `prix_total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `hotel_id`, `chambre`, `date_arrivee`, `date_depart`, `nb_personnes`, `statut`, `prix_total`, `created_at`) VALUES
(1, 1, 1, 'Standard', '2026-06-15', '2026-06-18', 2, 'confirmee', 360.00, '2026-05-10 13:13:33'),
(2, 2, 2, 'Superieure', '2026-07-05', '2026-07-09', 2, 'confirmee', 468.00, '2026-05-10 13:13:33'),
(3, 3, 3, 'Suite', '2026-08-10', '2026-08-15', 3, 'annulee', 1350.00, '2026-05-10 13:13:33'),
(4, 5, 4, 'Standard', '2026-05-22', '2026-05-29', 2, 'confirmee', 2450.00, '2026-05-12 12:20:05'),
(5, 5, 4, 'Standard', '2026-05-22', '2026-05-31', 2, 'confirmee', 3150.00, '2026-05-20 16:03:02'),
(6, 5, 3, 'Superieure', '2026-05-22', '2026-05-29', 6, 'en_attente', 1365.00, '2026-05-20 17:13:06');

-- --------------------------------------------------------

--
-- Structure de la table `taxi_reservations`
--

CREATE TABLE `taxi_reservations` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `adresse_depart` varchar(255) NOT NULL,
  `adresse_arrivee` varchar(255) NOT NULL,
  `date_heure` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `nb_passagers` int NOT NULL DEFAULT '1',
  `statut` enum('en_attente','confirmee','annulee') DEFAULT 'en_attente',
  `prix_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taxi_reservations`
--

INSERT INTO `taxi_reservations` (`id`, `user_id`, `reservation_id`, `adresse_depart`, `adresse_arrivee`, `date_heure`, `type`, `nb_passagers`, `statut`, `prix_total`, `created_at`) VALUES
(1, 1, 1, 'Aeroport Tunis-Carthage', 'Seabel Rym Beach', '2026-06-15 10:00:00', 'standard', 2, 'confirmee', 45.00, '2026-05-10 13:13:33'),
(2, 2, 2, 'Gare Tunis', 'Seabel Aladin', '2026-07-05 09:30:00', 'vito', 3, 'confirmee', 60.00, '2026-05-10 13:13:33'),
(3, 3, 3, 'Aeroport Tunis-Carthage', 'Seabel Alhambra', '2026-08-10 11:00:00', 'van', 4, 'en_attente', 80.00, '2026-05-10 13:13:33'),
(4, 5, 4, 'Aeroport Tunis-Carthage', 'ibreostar mehari', '2026-05-22 00:00:00', 'standard', 1, 'en_attente', 35.00, '2026-05-16 14:33:52');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Mezni', 'Sara', 'sara.mezni@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', '2026-05-10 13:13:33'),
(2, 'Benali', 'Youssef', 'youssef.benali@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', '2026-05-10 13:13:33'),
(3, 'Trabelsi', 'Amine', 'amine.trabelsi@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', '2026-05-10 13:13:33'),
(4, 'admin', 'admin', 'admin@seabel.com', '$2y$10$u3U4eqFvHNjRjlcFuhGw2.fxbBp/YO5jEDAADY88BmOYr7OfDFLz2', 'admin', '2026-05-10 13:26:59'),
(5, 'wissem', 'bouhamda', 'wyssem.bouhamda@gmail.com', '$2y$10$19F.Ozcp5zPqO08CCQU6Xu2QKiL0bxoSIs1vIg/t6kHe0j7vv0TcS', 'client', '2026-05-10 13:31:08');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `car_rentals`
--
ALTER TABLE `car_rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_events_hotel_id` (`hotel_id`),
  ADD KEY `idx_events_date_debut` (`date_debut`),
  ADD KEY `idx_events_date_fin` (`date_fin`);

--
-- Index pour la table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_hotels_ville` (`ville`),
  ADD KEY `idx_hotels_nom` (`nom`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservations_hotel_id` (`hotel_id`),
  ADD KEY `idx_reservations_user_status` (`user_id`,`statut`);

--
-- Index pour la table `taxi_reservations`
--
ALTER TABLE `taxi_reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_reservation_id` (`reservation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `car_rentals`
--
ALTER TABLE `car_rentals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `taxi_reservations`
--
ALTER TABLE `taxi_reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `car_rentals`
--
ALTER TABLE `car_rentals`
  ADD CONSTRAINT `car_rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_rentals_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `taxi_reservations`
--
ALTER TABLE `taxi_reservations`
  ADD CONSTRAINT `taxi_reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `taxi_reservations_ibfk_2` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
