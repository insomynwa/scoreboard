-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2019 at 11:17 AM
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
(1, 1, 1, 1, 2, 1, 1),
(2, 2, 1, 2, 1, 2, 1),
(3, 3, 1, 2, 3, 2, 1),
(4, 4, 1, 2, 1, 3, 1),
(5, 5, 2, 1, 2, 1, 1),
(6, 6, 2, 2, 1, 3, 1),
(7, 7, 2, 2, 1, 2, 1),
(8, 8, 2, 2, 2, 3, 1);

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
(1, 1, 1, 0, '', 1),
(2, 2, 1, 0, '', 1),
(3, 3, 1, 0, '', 1),
(4, 4, 1, 0, '', 1),
(5, 5, 1, 0, '', 1),
(6, 6, 1, 0, '', 1),
(7, 7, 1, 0, '', 1),
(8, 8, 1, 0, '', 1);

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
(1, 'Zidane', 2),
(2, 'Klopp', 1),
(3, 'Mugabe', 0);

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
(1, 1, 2, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(2, 1, 1, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(3, 2, 1, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(4, 2, 2, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(5, 3, 3, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(6, 3, 2, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(7, 4, 1, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(8, 4, 3, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(9, 5, 2, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(10, 5, 1, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(11, 6, 1, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(12, 6, 3, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(13, 7, 1, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(14, 7, 2, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(15, 8, 2, 120, 0, 0, 0, 0, 0, 0, 0, NULL),
(16, 8, 3, 120, 0, 0, 0, 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `scoreboard_style`
--

CREATE TABLE `scoreboard_style` (
  `id` int(11) NOT NULL,
  `bowstyle_id` int(11) NOT NULL,
  `style_name` varchar(45) NOT NULL DEFAULT 'style name',
  `style_config` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scoreboard_style`
--

INSERT INTO `scoreboard_style` (`id`, `bowstyle_id`, `style_name`, `style_config`) VALUES
(1, 1, 'Style 1', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(2, 1, 'Style 2', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(3, 1, 'Style 3', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":true,\"visibility_class\":\"\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(4, 2, 'Style 1', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(5, 2, 'Style 2', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(6, 2, 'Style 3', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score2\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score3\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"Total\",\"visibility\":true,\"visibility_class\":\"\"},\"gamepoint\":{\"label\":\"Set pts\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(7, 1, 'Beregu 1', '{\"logo\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(8, 1, 'Individu 1', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(9, 2, 'Beregu 1', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"player\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}'),
(10, 2, 'Individu 1', '{\"logo\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"team\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"player\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"timer\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score1\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score2\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score3\":{\"label\":\"\",\"visibility\":true,\"visibility_class\":\"\"},\"score4\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score5\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"score6\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setpoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamepoint\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"setscore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"gamescore\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"},\"description\":{\"label\":\"\",\"visibility\":false,\"visibility_class\":\"hide\"}}');

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
(1, '554313jerman.png', 'Germany', 'GER', 'Panzer'),
(2, '62979france.png', 'France', 'FRA', 'Oui');

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
  MODIFY `gamedraw_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gamemode`
--
ALTER TABLE `gamemode`
  MODIFY `gamemode_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gameset`
--
ALTER TABLE `gameset`
  MODIFY `gameset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `score_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `scoreboard_style`
--
ALTER TABLE `scoreboard_style`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
