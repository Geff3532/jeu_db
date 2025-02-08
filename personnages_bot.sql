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
-- Structure de la table `personnages_bot`
--

CREATE TABLE `personnages_bot` (
  `id` int(11) NOT NULL,
  `id_partie` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` enum('magicien','guerrier','brute','enchanteur') NOT NULL,
  `vie` int(11) NOT NULL,
  `vie_max` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `experience` int(11) NOT NULL DEFAULT 0,
  `atout` int(11) NOT NULL DEFAULT 0,
  `timeEndormi` int(11) NOT NULL DEFAULT 0,
  `timePower` int(11) NOT NULL DEFAULT 0,
  `tour_frapper` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `personnages_bot`
--

INSERT INTO `personnages_bot` (`id`, `id_partie`, `nom`, `type`, `vie`, `vie_max`, `level`, `experience`, `atout`, `timeEndormi`, `timePower`, `tour_frapper`) VALUES
(130, 54914484, 'Enchanteur #535', 'enchanteur', 2094, 2207, 87, 0, 0, 0, 0, 2),
(131, 54914484, 'Magicien #356', 'magicien', 2161, 2161, 87, 0, 0, 0, 0, 0),
(132, 54914484, 'Enchanteur #576', 'enchanteur', 2107, 2107, 83, 0, 0, 0, 4, 2),
(142, 10946855, 'Magicien #675', 'magicien', 811, 811, 33, 0, 0, 0, 0, 0),
(143, 10946855, 'Guerrier #254', 'guerrier', 782, 782, 33, 0, 0, 0, 0, 0),
(144, 10946855, 'Brute #330', 'brute', 869, 966, 35, 0, 0, 0, 0, 0),
(145, 95038460, 'Guerrier #372', 'guerrier', 1887, 2132, 87, 0, 0, 0, 0, 0),
(146, 95038460, 'Guerrier #73', 'guerrier', 2007, 2007, 82, 0, 0, 0, 0, 0),
(147, 95038460, 'Magicien #868', 'magicien', 2120, 2161, 87, 0, 0, 3, 0, 0),
(148, 5743145, 'Magicien #978', 'magicien', 133, 133, 4, 0, 0, 0, 0, 0),
(149, 5743145, 'Magicien #165', 'magicien', 121, 121, 3, 0, 0, 0, 0, 0),
(150, 5743145, 'Brute #802', 'brute', 165, 165, 2, 0, 0, 0, 0, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `personnages_bot`
--
ALTER TABLE `personnages_bot`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `personnages_bot`
--
ALTER TABLE `personnages_bot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
