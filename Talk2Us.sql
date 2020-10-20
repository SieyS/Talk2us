-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 20 oct. 2020 à 16:49
-- Version du serveur :  5.7.17
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `chat`
--

-- --------------------------------------------------------

--
-- Structure de la table `channel`
--

CREATE TABLE `channel` (
  `channel_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_bin NOT NULL,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `channel`
--

INSERT INTO `channel` (`channel_id`, `name`, `creation`) VALUES
(1, 'Camping-Car', '2020-10-20 14:35:15'),
(5, 'Famille', '2020-10-20 14:40:00'),
(4, 'Home', '2020-10-20 14:39:17'),
(6, 'Los Pollos hermanos', '2020-10-20 14:42:44');

-- --------------------------------------------------------

--
-- Structure de la table `channel_message`
--

CREATE TABLE `channel_message` (
  `channel_message_id` int(11) NOT NULL,
  `user_from` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `chat_message` text COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `channel_message`
--

INSERT INTO `channel_message` (`channel_message_id`, `user_from`, `channel_id`, `chat_message`, `timestamp`, `status`) VALUES
(1, 10, 1, 'Il y a des trous dans le camping car :/', '2020-10-20 14:36:00', 1),
(2, 8, 6, 'Qui veux du poulet ?', '2020-10-20 14:43:52', 1),
(3, 4, 5, 'Sa vous dis quelque chose W.W. ?', '2020-10-20 14:44:42', 1);

-- --------------------------------------------------------

--
-- Structure de la table `channel_user`
--

CREATE TABLE `channel_user` (
  `channel_user_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_from` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Déchargement des données de la table `channel_user`
--

INSERT INTO `channel_user` (`channel_user_id`, `user_id`, `channel_id`, `user_from`) VALUES
(1, 10, 1, 10),
(2, 2, 1, 10),
(6, 1, 4, 1),
(5, 1, 1, 10),
(7, 3, 4, 1),
(8, 5, 4, 1),
(9, 1, 5, 1),
(11, 3, 5, 1),
(12, 5, 5, 1),
(13, 4, 5, 1),
(14, 6, 5, 1),
(15, 8, 6, 8),
(16, 1, 6, 8),
(17, 2, 6, 8),
(18, 9, 6, 8),
(19, 10, 6, 8);

-- --------------------------------------------------------

--
-- Structure de la table `chat_message`
--

CREATE TABLE `chat_message` (
  `chat_message_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `chat_message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `login`
--

CREATE TABLE `login` (
  `user_id` int(11) NOT NULL,
  `mail` text NOT NULL,
  `mail_valide` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `login`
--

INSERT INTO `login` (`user_id`, `mail`, `mail_valide`, `username`, `password`) VALUES
(1, 'Walter@white.fr', 0, 'Walter White', '$2y$10$oeSRoZZN4LrzeK0TXNOyvu25WpcxuvVeR9u.2GVk0wIVdv8cgUF6S'),
(2, 'Jesse@pinkman.fr', 0, 'Jesse Pinkman', '$2y$10$Q0nG6tOGqY2MkJEdfGbyquYBOLAdRLVP72LhRnxlaxiJy16YogxFK'),
(3, 'Skyler@white.fr', 0, 'Skyler White', '$2y$10$orGu9OWckgNf8Xc1ZzZuJ.iAYJLPOxh9Wg3sOpixmSah/c1.Jm4YG'),
(4, 'Hank@Schrader.fr', 0, 'Hank Schrader', '$2y$10$3mmYG9b9oUiKW5svCdn.NeUJ2xwrEVJknjr0dBtJr1wM7MTyf.KWm'),
(5, 'Walter-jr@white.fr', 0, 'Walter Jr.', '$2y$10$HAYdQC/.ZkxMdqV3tVD0nOpcGCf8BbV01ejLL13sb.sDz9JWnuosO'),
(6, 'Marie@Schrader.fr', 0, 'Marie Schrader', '$2y$10$Qh8Yf023KY7JsadFo3tW/eSYUxt.2/VGa2Zsl43totjt6lVKMMi0e'),
(7, 'Saul@goodman.fr', 0, 'Saul Goodman', '$2y$10$0l28ltrqcfk9EAHSzJfAaODYI1C4aN5OObnmZLAyovnxvx4VkVjj2'),
(8, 'Gustavo@fring.fr', 0, 'Gustavo Fring', '$2y$10$IyhZXLc9cOzIxY0GTeI2euGYsUvlh4Jo9Wsu.1ueSojdJR1xYTy0a'),
(9, 'Mike@Ehrmantraut.fr', 0, 'Mike Ehrmantraut', '$2y$10$jfdG3UWHoPvfBBNa2frpne0Gv27.VucyvShor3KlSh6kp0IbuMPYm'),
(10, 'Heisenberg@meth.fr', 0, 'Heisenberg', '$2y$10$c4Zhej/OMeSt4AqjNac6pen5.Re3yZKDiySZkV52JK4bJjT1/5WfC');

-- --------------------------------------------------------

--
-- Structure de la table `login_details`
--

CREATE TABLE `login_details` (
  `login_details_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_type` enum('no','yes') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `login_details`
--

INSERT INTO `login_details` (`login_details_id`, `user_id`, `last_activity`, `is_type`) VALUES
(1, 10, '2020-10-20 14:49:54', 'no'),
(2, 1, '2020-10-20 14:49:58', 'no'),
(3, 8, '2020-10-20 14:44:02', 'no'),
(4, 4, '2020-10-20 14:44:49', 'no');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`channel_id`);

--
-- Index pour la table `channel_message`
--
ALTER TABLE `channel_message`
  ADD PRIMARY KEY (`channel_message_id`);

--
-- Index pour la table `channel_user`
--
ALTER TABLE `channel_user`
  ADD PRIMARY KEY (`channel_user_id`);

--
-- Index pour la table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`chat_message_id`);

--
-- Index pour la table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `login_details`
--
ALTER TABLE `login_details`
  ADD PRIMARY KEY (`login_details_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `channel`
--
ALTER TABLE `channel`
  MODIFY `channel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `channel_message`
--
ALTER TABLE `channel_message`
  MODIFY `channel_message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `channel_user`
--
ALTER TABLE `channel_user`
  MODIFY `channel_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `chat_message_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `login`
--
ALTER TABLE `login`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `login_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
