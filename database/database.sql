-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 02 juin 2026 à 12:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `online_library`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Aventure'),
(2, 'informatique'),
(3, 'd\'Affaires'),
(4, 'Histoire'),
(5, 'Architecture'),
(6, 'Mode');

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `fichier_pdf` varchar(150) NOT NULL,
  `image` varchar(150) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `auteur`, `description`, `fichier_pdf`, `image`, `id_categorie`, `date_ajout`) VALUES
(11, 'Introduction aux études historiques', ' Charles-Victor Langlois et Charles Seignobos', ' Ouvrage fondateur de la méthodologie historique française (1898). Langlois et Seignobos définissent les principes de la critique des sources, de l\'analyse documentaire et de la synthèse historique.', '1779977236_introduction-aux-etudes-historiques-charles-victor-langlois-et-charles-seignobos-4921.pdf', '1779977236_download.png', 4, '2026-05-28 14:07:16'),
(12, ' La mode & le design à Paris', 'Emmanuelle Pierre-Marie et Nelly Rod', 'La mode & le design à Paris est un livre qui présente l’univers de la mode et du design dans la ville de Paris. Il met en valeur l’histoire, les tendances et la créativité de la capitale française, considérée comme l’une des villes les plus importantes au monde dans le domaine de la mode. Le livre explore aussi les grands créateurs, les maisons de couture et l’évolution du style parisien.', '1779979746_la-mode-le-design-a-paris-emmanuelle-pierre-marie-et-nelly-rod.pdf', '1779979746_download (1).png', 6, '2026-05-28 14:49:06'),
(13, 'La mode des années 70 en images', 'Charlotte Fiell et Emmanuelle Dirix', 'La mode des années 70 en images est un livre illustré qui présente les tendances de la mode des années 1970. Il met en valeur les styles vestimentaires emblématiques de cette époque, comme les pantalons pattes d’eph, les motifs colorés et les looks disco. Le livre montre aussi l’évolution de la mode à travers des images, des créations de designers et des inspirations culturelles de cette décennie.', '1779979933_la-mode-des-annees-70-en-images-charlotte-fiell-et-emmanuelle-dirix.pdf', '1779979933_download (2).png', 6, '2026-05-28 14:52:13'),
(14, 'Système de la Mode', 'Roland Barthes', 'Système de la Mode est un ouvrage de Roland Barthes qui analyse la mode comme un langage et un système de signes. L’auteur étudie comment les vêtements et les descriptions dans les magazines de mode créent du sens et influencent la société. Le livre explore la mode non seulement comme esthétique, mais aussi comme un phénomène culturel et social basé sur la communication et les symboles.', '1779980537_systeme-de-la-mode-roland-barthes.pdf', '1779980537_download (4).png', 6, '2026-05-28 15:02:17'),
(15, 'Cours de Python', ' Patrick Fuchs et Pierre Poulain', 'Cours de Python est un ouvrage pédagogique qui introduit les bases de la programmation avec le langage Python. Il explique les notions fondamentales comme les variables, les conditions, les boucles, les fonctions et les structures de données. Le livre propose également des exercices pratiques pour aider les débutants à apprendre à programmer de manière progressive et efficace.', '1779981599_cours-de-python-patrick-fuchs-et-pierre-poulain-4765 (1).pdf', '1779981599_download (5).png', 2, '2026-05-28 15:19:59'),
(16, 'Les réseaux de zéro', 'Vince', 'Des bases jusqu\'aux protocoles avancés, ce guide de 449 pages explique les réseaux informatiques en partant de zéro. Matériel, topologies, modèle OSI, TCP/IP : tout y est.', '1779981725_les-reseaux-de-zero-vince-4767.pdf', '1779981725_download (6).png', 2, '2026-05-28 15:22:05'),
(17, ' Introduction à la Cybersécurité', 'cryptosec.org', 'Introduction complète à la cybersécurité couvrant les attaques réelles, la défense périmétrique, le chiffrement, les VPN et les politiques de sécurité. Niveau Master.', '1779981847_introduction-a-la-cybersecurite-cryptosec-org-4768.pdf', '1779981847_download (7).png', 2, '2026-05-28 15:24:07'),
(18, ' Introduction à la Cybersécurité', 'cryptosec.org', 'Introduction complète à la cybersécurité couvrant les attaques réelles, la défense périmétrique, le chiffrement, les VPN et les politiques de sécurité. Niveau Master.', '1779982304_introduction-a-la-cybersecurite-cryptosec-org-4768.pdf', '1779982304_download (7).png', 2, '2026-05-28 15:31:44'),
(19, ' La création d\'entreprise', 'Robert Papin', 'Guide complet pour créer, gérer, développer et reprendre une entreprise. Couvre le diagnostic financier, le business plan, la stratégie et le management. Référence HEC.', '1779982391_la-creation-d-entreprise-robert-papin-4608.pdf', '1779982391_download (8).png', 3, '2026-05-28 15:33:11'),
(20, 'Marketing stratégique et opérationnel', 'ean-Jacques Lambin', 'Manuel de référence couvrant le marketing stratégique et opérationnel : analyse des besoins, segmentation, positionnement, mix marketing et nouveaux défis de la mondialisation.', '1779982539_marketing-strategique-et-operationnel-jean-jacques-lambin-4610.pdf', '1779982539_download (9).png', 3, '2026-05-28 15:35:39'),
(21, 'Leadership et Management', 'Harvard Business Review', 'Sélection d\'articles de la Harvard Business Review sur le leadership : Henry Mintzberg, John Kotter, Abraham Zaleznik. Explore les différentes facettes du leadership en entreprise.', '1779982612_leadership-et-management-harvard-business-review-4611.pdf', '1779982612_download (10).png', 3, '2026-05-28 15:36:52'),
(22, 'Réfléchissez et Devenez Diche', 'Napoleon Hill', 'Réfléchissez et devenez riche est un livre de développement personnel écrit par Napoleon Hill. Il explique les principes du succès et de la richesse', '1779982976_reflechissez-et-devenez-riche-livres-pour-napoleon-hill.pdf', '1779982976_portada-reflechissez-et-devenez-diche-napoleon-hill.webp', 3, '2026-05-28 15:42:56'),
(23, 'L’architecture de l’Antiquité à nos jours', ' Enseigner Autrement', 'Panorama illustre de l\'architecture occidentale, de l\'Antiquite grecque aux constructions contemporaines. Couvre les ordres classiques, le roman, le gothique, la Renaissance et le néoclassicisme', '1779983170_larchitecture-de-lantiquite-a-nos-jours-enseigner-autrement.pdf', '1779983170_download (11).png', 5, '2026-05-28 15:46:10'),
(24, 'Histoire de l\'art, histoire de l\'architecture et histoire des techniques', 'Valérie Nègre', 'Article sur les orientations communes entre histoire de l\'art, de l\'architecture et des techniques (XVe-XVIIIe siècle). Explore les interactions entre théories et pratiques, la matérialité de l\'invention et le rôle de la matière.', '1779983284_histoire-de-l-art-histoire-de-l-architecture-et-histoire-des-techniques-valerie-negre-6346.pdf', '1779983284_download (12).png', 5, '2026-05-28 15:48:04'),
(25, 'Les dix livres d\'architecture de Vitruve', 'Vitruve (trad. Claude Perrault)', 'Traduction française classique (1685) par Claude Perrault du traite fondateur de l\'architecture occidentale, avec notes et figures.', '1779983575_les-dix-livres-d-architecture-de-vitruve-vitruve-trad-claude-perrault-6354.pdf', '1779983575_download (13).png', 5, '2026-05-28 15:52:55'),
(26, ' La dimension paysagere dans l\'architecture nordique', 'Yan Roche', ' Mémoire de recherche (ENSAL) sur l\'intégration du paysage dans l\'architecture scandinave. Analyse des œuvres d\'Asplund, Aalto et des architectes nordiques contemporains.', '1779983890_la-dimension-paysagere-dans-larchitecture-nordique-yan-roche.pdf', '1779983890_download (14).png', 5, '2026-05-28 15:58:10'),
(27, 'Histoire de l\'architecture islamique', 'Jendoubi Khenissi Sihem', 'Cours universitaire (ENAU Tunis) couvrant l\'architecture islamique de la maison du Prophete aux réalisations omeyyades, abbassides, fatimides et ottomanes.', '1779984196_histoire-de-l-architecture-islamique-jendoubi-khenissi-sihem-6350.pdf.crdownload', '1779984196_download (15).png', 5, '2026-05-28 16:03:16'),
(28, 'Patrimoine mondial UNESCO : Concepts, methodes, outils et perspectives', 'Lorenzo Diez et Pierre Maurer', 'Ouvrage collectif de l\'ENSA Nancy sur les outils de protection règlementaire, les plans de gestion et les enjeux du patrimoine mondial UNESCO.', '1779984402_patrimoine-mondial-unesco-concepts-methodes-outils-et-perspectives-lorenzo-diez-et-pierre-maurer-6349.pdf', '1779984402_download (16).png', 5, '2026-05-28 16:06:42'),
(29, ' Mode et société', 'Auteurs divers', 'Cet ouvrage collectif analyse la relation entre la mode et la société. Il montre comment les vêtements, les styles et les tendances ne sont pas seulement esthétiques, mais aussi des phénomènes sociaux liés à la culture, à l’identité et aux changements historiques.\r\n\r\nIl étudie aussi le rôle de la mode dans la construction des classes sociales, des comportements et des représentations dans différentes époques.', '1779985088_mode-et-societe-auteurs-divers.pdf', '1779985088_download (3).png', 6, '2026-05-28 16:18:08'),
(30, 'Robinson Crusoé', 'Daniel de Foë', 'Histoire d’un homme naufragé qui survit seul sur une île déserte pendant plusieurs années.', '1779991883_robinson-crusoe-daniel-de-foe.pdf', '1779991883_ROBINSON CRUSOE.png', 1, '2026-05-28 18:11:23'),
(31, 'Aventures d’Arthur Gordon Pym de Nantucket', ' Edgar Allan Poe', 'Histoire d’un jeune homme qui vit des aventures dangereuses en mer, entre naufrages, mutineries et mystères.', '1779992020_aventures-darthur-gordon-pym-de-nantucket-edgar-allan-poe.pdf', '1779992020_aventures d\'arthur goedon pyn de nantucket.png', 1, '2026-05-28 18:13:40'),
(32, 'Les Aventures de Tom Sawyer', 'Mark Twain', 'Histoire d’un garçon espiègle vivant des aventures et des jeux avec ses amis sur les bords du Mississippi.', '1779992210_les-aventures-de-tom-sawyer-mark-twain.pdf', '1779992210_Les-Aventures-de-Tom-Sawyer-par-Mark-Twain.webp', 1, '2026-05-28 18:16:50'),
(33, 'L\'Ile Mystérieuse', 'Jules Verne.', 'Histoire de naufragés qui survivent sur une île mystérieuse en utilisant la science et leur intelligence.', '1779992474_lile-mysterieuse-jules-verne.pdf', '1779992474_LIle-Mysterieuse-par-Jules-Verne.webp', 1, '2026-05-28 18:21:14'),
(34, 'Histoire de France', ' Jacques Bainville', 'L\'ouvrage de référence de Bainville, publié en 1924. Une synthèse complète qui relie causes et effets, des Gaulois au XXe siècle. Le livre qui a formé des générations de lecteurs d\'histoire.', '1779992690_histoire-de-france-jacques-bainville-4913.pdf', '1779992690_histoire de france.png', 4, '2026-05-28 18:24:50'),
(35, 'Histoire générale de la civilisation en Europe', 'François Guizot', 'Cours magistral de Guizot à la Sorbonne sur l\'évolution de la civilisation européenne, de la chute de Rome à la Révolution française. Analyse les forces politiques, religieuses et sociales qui ont façonné l\'Europe.', '1779992917_histoire-generale-de-la-civilisation-en-europe-francois-guizot-4917.pdf', '1779992917_download.png', 4, '2026-05-28 18:28:37'),
(36, 'L\'Étrange Défaite', 'Marc Bloch', 'Témoignage et analyse de la défaite française de 1940 par l\'historien Marc Bloch. Inclut son testament et ses écrits clandestins. Un document essentiel pour comprendre les causes militaires et sociales de la débâcle.', '1779993030_l-etrange-defaite-marc-bloch-4915.pdf', '1779993030_marc bloch.png', 4, '2026-05-28 18:30:30');

-- --------------------------------------------------------

--
-- Structure de la table `telechargements`
--

CREATE TABLE `telechargements` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_livre` int(11) DEFAULT NULL,
  `date_telechargement` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_creation`) VALUES
(1, 'Admin', 'System', 'admin@gmail.com', '123456', 'admin', '2026-05-23 16:05:01'),
(2, 'boutlane', 'zakaria', 'zakaria@gmail.com', '123456', 'admin', '2026-05-23 20:05:04'),
(3, 'CHABLAOUI', 'soukaina', 'chablaoui.soukaina.solicode@gmail.com', '1234567', 'user', '2026-05-23 20:07:19'),
(4, 'chablaoui', 'chaimae', 'chaimae99@gmail.com', 'CHAIMAE99', 'user', '2026-05-24 19:34:20'),
(5, 'chablaoui', 'chaimae', 'chaimae@gmail.com', 'chaimae88', 'user', '2026-05-24 19:35:47');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `telechargements`
--
ALTER TABLE `telechargements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_livre` (`id_livre`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `telechargements`
--
ALTER TABLE `telechargements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `livres`
--
ALTER TABLE `livres`
  ADD CONSTRAINT `livres_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `telechargements`
--
ALTER TABLE `telechargements`
  ADD CONSTRAINT `telechargements_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `telechargements_ibfk_2` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
