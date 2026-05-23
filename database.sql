SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

DROP TABLE IF EXISTS `plannings`;
CREATE TABLE IF NOT EXISTS `plannings` (
  `id_planning` int NOT NULL AUTO_INCREMENT,
  `id_release` int NOT NULL,
  `planning_json` longtext NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_planning`),
  UNIQUE KEY `id_release` (`id_release`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `releases`;
CREATE TABLE IF NOT EXISTS `releases` (
  `id_release` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_type` int NOT NULL,
  `number_songs` int NOT NULL,
  `release_date` date NOT NULL,
  `budget` int NOT NULL,
  `details` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id_release`),
  KEY `id_user` (`id_user`),
  KEY `id_type` (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `styles`;
CREATE TABLE IF NOT EXISTS `styles` (
  `id_style` int NOT NULL AUTO_INCREMENT,
  `style` varchar(255) NOT NULL,
  PRIMARY KEY (`id_style`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `styles` (`id_style`, `style`) VALUES
(1, 'Hip-Hop/Rap');

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id_type` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `types` (`id_type`, `type`) VALUES
(1, 'Single'),
(2, 'EP'),
(3, 'Mixtape'),
(4, 'Album');

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `plannings`
  ADD CONSTRAINT `plannings_ibfk_1` FOREIGN KEY (`id_release`) REFERENCES `releases` (`id_release`) ON DELETE CASCADE;

ALTER TABLE `releases`
  ADD CONSTRAINT `releases_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `releases_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `types` (`id_type`) ON DELETE RESTRICT ON UPDATE RESTRICT;

COMMIT;