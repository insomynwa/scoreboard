-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2019 at 09:51 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scoreboard_panahan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bowstyles`
--

CREATE TABLE `bowstyles` (
  `bowstyle_id` int(11) NOT NULL,
  `bowstyle_name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bowstyles`
--

INSERT INTO `bowstyles` (`bowstyle_id`, `bowstyle_name`) VALUES
(1, 'Recurve'),
(2, 'Compound');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `config_id` int(11) NOT NULL,
  `config_name` varchar(50) NOT NULL,
  `config_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`config_id`, `config_name`, `config_value`) VALUES
(1, 'form_scoreboard', '[{\"logo\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"team\":{\"label\":\"Team\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"Player\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score1\":{\"label\":\"1\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"2\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"3\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"4\",\"visibility\":true,\"visibility_class\":\"\"},\"score5\":{\"label\":\"5\",\"visibility\":true,\"visibility_class\":\"\"},\"score6\":{\"label\":\"6\",\"visibility\":true,\"visibility_class\":\"\"},\"setpoint\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"Description\",\"visibility\":true,\"visibility_class\":\"\"},\"setscore\":{\"visibility\":true,\"visibility_class\":\"\",\"label\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"}},{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"Team\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"Player\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"1\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"2\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"3\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"4\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"5\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"6\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"Description\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"visibility\":true,\"visibility_class\":\"\",\"label\":\"Total\"},\"gamescore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}},{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"Team\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"Player\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"1\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"2\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"3\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"4\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"5\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"6\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"Description\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"visibility\":true,\"visibility_class\":\"\",\"label\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"}}]'),
(2, 'live_scoreboard_time_interval', '500');

-- --------------------------------------------------------

--
-- Table structure for table `gamedraw`
--

CREATE TABLE `gamedraw` (
  `gamedraw_id` int(11) NOT NULL,
  `gamedraw_num` int(11) NOT NULL,
  `bowstyle_id` int(11) NOT NULL,
  `gamemode_id` int(11) NOT NULL,
  `contestant_a_id` int(11) NOT NULL,
  `contestant_b_id` int(11) NOT NULL,
  `gamestatus_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gamedraw`
--

INSERT INTO `gamedraw` (`gamedraw_id`, `gamedraw_num`, `bowstyle_id`, `gamemode_id`, `contestant_a_id`, `contestant_b_id`, `gamestatus_id`) VALUES
(35, 1, 1, 2, 23, 21, 1),
(36, 2, 2, 1, 20, 19, 1),
(37, 3, 1, 1, 19, 17, 1),
(38, 4, 2, 2, 17, 21, 1),
(39, 5, 1, 1, 21, 18, 1),
(40, 6, 1, 2, 19, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gamemode`
--

CREATE TABLE `gamemode` (
  `gamemode_id` int(11) NOT NULL,
  `gamemode_name` varchar(10) NOT NULL,
  `gamemode_desc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gamemode`
--

INSERT INTO `gamemode` (`gamemode_id`, `gamemode_name`, `gamemode_desc`) VALUES
(1, 'Beregu', 'team vs team'),
(2, 'Individu', 'individu vs individu');

-- --------------------------------------------------------

--
-- Table structure for table `gameset`
--

CREATE TABLE `gameset` (
  `gameset_id` int(11) NOT NULL,
  `gamedraw_id` int(11) NOT NULL,
  `gameset_num` tinyint(11) NOT NULL,
  `gameset_point` tinyint(4) NOT NULL DEFAULT '0',
  `gameset_desc` varchar(30) NOT NULL DEFAULT '',
  `gameset_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'temp: get from gamestatus'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gameset`
--

INSERT INTO `gameset` (`gameset_id`, `gamedraw_id`, `gameset_num`, `gameset_point`, `gameset_desc`, `gameset_status`) VALUES
(49, 35, 1, 0, '', 3),
(50, 36, 1, 0, '', 1),
(51, 37, 1, 0, '', 1),
(52, 38, 1, 0, '', 1),
(53, 35, 2, 0, '', 1),
(54, 36, 2, 0, '', 1),
(55, 37, 2, 0, '', 1),
(56, 35, 3, 0, '', 1),
(57, 40, 1, 0, '', 1),
(58, 40, 2, 0, '', 1),
(59, 40, 3, 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gamestatus`
--

CREATE TABLE `gamestatus` (
  `gamestatus_id` int(11) NOT NULL,
  `gamestatus_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gamestatus`
--

INSERT INTO `gamestatus` (`gamestatus_id`, `gamestatus_name`) VALUES
(1, 'Stand by'),
(2, 'Live'),
(3, 'Finished');

-- --------------------------------------------------------

--
-- Table structure for table `livegame`
--

CREATE TABLE `livegame` (
  `livegame_id` int(11) NOT NULL,
  `gameset_id` int(11) NOT NULL,
  `scoreboard_style_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `livegame`
--

INSERT INTO `livegame` (`livegame_id`, `gameset_id`, `scoreboard_style_id`) VALUES
(1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE `player` (
  `player_id` int(11) NOT NULL,
  `player_name` varchar(25) NOT NULL,
  `team_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`player_id`, `player_name`, `team_id`) VALUES
(17, 'Solomia', 18),
(18, 'Anastasia', 18),
(19, 'Benzema', 19),
(20, 'Kurniawan', 17),
(21, 'Kahn', 20),
(22, 'Klose', 20),
(23, 'Katniss', 0);

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `score_id` int(11) NOT NULL,
  `gameset_id` int(11) NOT NULL,
  `contestant_id` int(11) NOT NULL DEFAULT '0',
  `score_timer` tinyint(4) NOT NULL DEFAULT '120',
  `score_1` tinyint(4) NOT NULL DEFAULT '0',
  `score_2` tinyint(4) NOT NULL DEFAULT '0',
  `score_3` tinyint(4) NOT NULL DEFAULT '0',
  `score_4` tinyint(4) NOT NULL DEFAULT '0',
  `score_5` tinyint(4) NOT NULL DEFAULT '0',
  `score_6` tinyint(4) NOT NULL DEFAULT '0',
  `set_points` tinyint(4) NOT NULL DEFAULT '0',
  `score_desc` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`score_id`, `gameset_id`, `contestant_id`, `score_timer`, `score_1`, `score_2`, `score_3`, `score_4`, `score_5`, `score_6`, `set_points`, `score_desc`) VALUES
(97, 49, 23, 120, 1, 4, 5, 0, 0, 0, 0, ''),
(98, 49, 21, 120, 2, 3, 6, 0, 0, 0, 2, ''),
(99, 50, 20, 120, 1, 2, 3, 0, 0, 0, 0, ''),
(100, 50, 19, 120, 6, 5, 4, 0, 0, 0, 0, ''),
(101, 51, 19, 120, 1, 1, 1, 0, 0, 0, 0, ''),
(102, 51, 17, 120, 2, 2, 2, 0, 0, 0, 2, ''),
(103, 52, 17, 120, 1, 4, 5, 0, 0, 0, 0, ''),
(104, 52, 21, 120, 2, 3, 6, 0, 0, 0, 0, ''),
(105, 53, 23, 120, 1, 2, 3, 0, 0, 0, 0, ''),
(106, 53, 21, 120, 4, 5, 6, 0, 0, 0, 2, ''),
(107, 54, 20, 120, 1, 4, 5, 0, 0, 0, 0, ''),
(108, 54, 19, 120, 2, 3, 6, 0, 0, 0, 0, ''),
(109, 55, 19, 120, 2, 2, 3, 0, 0, 0, 0, ''),
(110, 55, 17, 120, 1, 5, 4, 0, 0, 0, 2, ''),
(111, 56, 23, 120, 6, 5, 4, 0, 0, 0, 2, ''),
(112, 56, 21, 120, 1, 2, 3, 0, 0, 0, 0, ''),
(113, 57, 19, 120, 10, 10, 10, 0, 0, 0, 2, ''),
(114, 57, 20, 120, 5, 5, 5, 0, 0, 0, 0, ''),
(115, 58, 19, 120, 6, 6, 6, 0, 0, 0, 0, ''),
(116, 58, 20, 120, 8, 7, 8, 0, 0, 0, 2, ''),
(117, 59, 19, 120, 8, 8, 8, 0, 0, 0, 0, ''),
(118, 59, 20, 120, 9, 8, 9, 0, 0, 0, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `scoreboard_style`
--

CREATE TABLE `scoreboard_style` (
  `id` int(11) NOT NULL,
  `bowstyle_id` int(11) NOT NULL,
  `style` tinyint(3) UNSIGNED NOT NULL,
  `style_config` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scoreboard_style`
--

INSERT INTO `scoreboard_style` (`id`, `bowstyle_id`, `style`, `style_config`) VALUES
(1, 1, 1, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(2, 1, 2, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(3, 1, 3, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(4, 2, 1, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(5, 2, 2, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(6, 2, 3, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(19, 1, 4, '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(27, 2, 4, '{\"logo\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score5\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score6\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"setpoint\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"}}');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `team_id` int(11) NOT NULL,
  `team_logo` text NOT NULL,
  `team_name` varchar(25) NOT NULL,
  `team_initial` varchar(3) NOT NULL,
  `team_desc` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`team_id`, `team_logo`, `team_name`, `team_initial`, `team_desc`) VALUES
(17, '5608indonesia.png', 'Indon', 'IND', 'description'),
(18, '530406ukraine.png', 'Ukraine', 'UKR', 'description'),
(19, '721980france.png', 'France', 'FRA', 'description'),
(20, '637766jerman.png', 'Germany', 'GER', 'description'),
(21, 'no-image.png', 'Marvel', 'MAR', 'description');

-- --------------------------------------------------------

--
-- Table structure for table `web_config`
--

CREATE TABLE `web_config` (
  `web_config_id` int(11) NOT NULL,
  `time_interval` int(11) NOT NULL DEFAULT '1000',
  `active_mode` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `web_config`
--

INSERT INTO `web_config` (`web_config_id`, `time_interval`, `active_mode`) VALUES
(1, 500, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bowstyles`
--
ALTER TABLE `bowstyles`
  ADD PRIMARY KEY (`bowstyle_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `gamedraw`
--
ALTER TABLE `gamedraw`
  ADD PRIMARY KEY (`gamedraw_id`);

--
-- Indexes for table `gamemode`
--
ALTER TABLE `gamemode`
  ADD PRIMARY KEY (`gamemode_id`);

--
-- Indexes for table `gameset`
--
ALTER TABLE `gameset`
  ADD PRIMARY KEY (`gameset_id`);

--
-- Indexes for table `gamestatus`
--
ALTER TABLE `gamestatus`
  ADD PRIMARY KEY (`gamestatus_id`);

--
-- Indexes for table `livegame`
--
ALTER TABLE `livegame`
  ADD PRIMARY KEY (`livegame_id`);

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`player_id`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`score_id`);

--
-- Indexes for table `scoreboard_style`
--
ALTER TABLE `scoreboard_style`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `web_config`
--
ALTER TABLE `web_config`
  ADD PRIMARY KEY (`web_config_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bowstyles`
--
ALTER TABLE `bowstyles`
  MODIFY `bowstyle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gamedraw`
--
ALTER TABLE `gamedraw`
  MODIFY `gamedraw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `gamemode`
--
ALTER TABLE `gamemode`
  MODIFY `gamemode_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gameset`
--
ALTER TABLE `gameset`
  MODIFY `gameset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `gamestatus`
--
ALTER TABLE `gamestatus`
  MODIFY `gamestatus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `livegame`
--
ALTER TABLE `livegame`
  MODIFY `livegame_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `player`
--
ALTER TABLE `player`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `scoreboard_style`
--
ALTER TABLE `scoreboard_style`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `web_config`
--
ALTER TABLE `web_config`
  MODIFY `web_config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
