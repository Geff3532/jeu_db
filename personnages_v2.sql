-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 09 fév. 2025 à 00:52
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `personnages_v2`
--

CREATE TABLE `personnages_v2` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom_team` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `vie` int(10) UNSIGNED NOT NULL,
  `vie_max` int(11) NOT NULL,
  `type` enum('magicien','guerrier','brute','enchanteur') NOT NULL,
  `atout` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `experience` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 1,
  `timeEndormi` int(11) NOT NULL DEFAULT 0,
  `timePower` int(11) NOT NULL DEFAULT 0,
  `tour_frapper` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `personnages_v2`
--

INSERT INTO `personnages_v2` (`id`, `nom_team`, `nom`, `vie`, `vie_max`, `type`, `atout`, `experience`, `level`, `timeEndormi`, `timePower`, `tour_frapper`) VALUES
(1, 'geff', 'Guerrier', 78, 100000, 'guerrier', 3, 20, 24, 0, 0, 5),
(2, 'geff', 'Magicien_Best', 0, 711, 'magicien', 2, 30, 29, 0, 5, 4),
(3, 'geff', 'Enchanteur', 590, 782, 'enchanteur', 0, 40, 30, 0, 5, 5),
(53, 'test', 'eeee', 150, 150, 'brute', 0, 0, 1, 0, 0, 0),
(54, 'avangers', 'black widow', 120, 120, 'guerrier', 0, 10, 4, 0, 0, 0),
(55, 'avangers', 'Hulk', 200, 200, 'brute', 0, 20, 4, 0, 0, 0),
(51, 'test', 'e', 150, 150, 'brute', 0, 0, 1, 0, 0, 0),
(52, 'test', 'ee', 150, 150, 'brute', 0, 0, 1, 0, 0, 0),
(56, 'avangers', 'Docteur Strange', 194, 194, 'enchanteur', 0, 0, 6, 0, 0, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `personnages_v2`
--
ALTER TABLE `personnages_v2`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `personnages_v2`
--
ALTER TABLE `personnages_v2`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
