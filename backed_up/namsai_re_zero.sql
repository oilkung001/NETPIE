-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2016 at 02:11 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `namsai`
--

-- --------------------------------------------------------

--
-- Table structure for table `contributor`
--

CREATE TABLE `contributor` (
  `contributor_id` int(11) NOT NULL,
  `contributor_repoid` int(11) NOT NULL,
  `contributor_userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contributor`
--

INSERT INTO `contributor` (`contributor_id`, `contributor_repoid`, `contributor_userid`) VALUES
(3, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `edge`
--

CREATE TABLE `edge` (
  `edge_id` int(11) NOT NULL,
  `edge_repoid` int(11) NOT NULL,
  `edge_nodeid` int(11) NOT NULL,
  `edge_order` int(11) NOT NULL,
  `edge_nodenext` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `edge`
--

INSERT INTO `edge` (`edge_id`, `edge_repoid`, `edge_nodeid`, `edge_order`, `edge_nodenext`) VALUES
(222, 0, 0, 1, 232),
(223, 0, 232, 1, 233);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE `node` (
  `node_id` int(11) NOT NULL,
  `node_repoid` int(11) NOT NULL,
  `node_storyid` int(11) NOT NULL,
  `node_type` varchar(64) NOT NULL,
  `node_value` text NOT NULL,
  `node_pattern` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `repo`
--

CREATE TABLE `repo` (
  `repo_id` int(11) NOT NULL,
  `repo_ownerid` int(11) NOT NULL,
  `repo_name` varchar(256) NOT NULL,
  `repo_description` text NOT NULL,
  `repo_private` tinyint(4) NOT NULL,
  `repo_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `repo`
--

INSERT INTO `repo` (`repo_id`, `repo_ownerid`, `repo_name`, `repo_description`, `repo_private`, `repo_update`) VALUES
(1, 1, 'irin', 'my daugther', 1, '2016-08-16 07:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `session_id` int(11) NOT NULL,
  `session_repoid` int(11) NOT NULL,
  `session_interactorid` text NOT NULL,
  `session_node` int(11) NOT NULL,
  `session_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`session_id`, `session_repoid`, `session_interactorid`, `session_node`, `session_update`) VALUES
(20, 1, 'IP_::1', 231, '2016-10-16 13:33:20'),
(21, 1, 'IP_::1', 0, '2016-10-16 14:08:19'),
(22, 1, 'IP_::1', 231, '2016-10-16 16:43:34'),
(23, 1, 'IP_::1', 231, '2016-10-16 16:48:47'),
(24, 1, 'IP_::1', 231, '2016-11-12 21:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `story`
--

CREATE TABLE `story` (
  `story_id` int(11) NOT NULL,
  `story_repoid` int(11) NOT NULL,
  `story_name` varchar(256) NOT NULL,
  `story_order` int(11) NOT NULL,
  `story_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `story`
--

INSERT INTO `story` (`story_id`, `story_repoid`, `story_name`, `story_order`, `story_update`) VALUES
(10, 1, 'WORLD', 1, '2016-11-21 21:00:18');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `user_name` varchar(64) NOT NULL,
  `user_fullname` text NOT NULL,
  `user_bio` text NOT NULL,
  `user_hash` varchar(2048) NOT NULL,
  `user_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `user_name`, `user_fullname`, `user_bio`, `user_hash`, `user_update`) VALUES
(1, 'pure.gif@gmail.com', 'pureexe', 'Pakkapon Phongthawee', 'Nakhon Pathom,Thailand', '$2a$08$rHAyhoSVO4lg87nx9U9ZiOmeitHgBZa/Opb46FUegGlEAdRyqOYUe', '2016-08-11 23:01:45'),
(2, 'dev@irin.in.th', 'dev', 'Irin Phongthawee', 'BOT DEVELOPER', '$2a$08$rHAyhoSVO4lg87nx9U9ZiOmeitHgBZa/Opb46FUegGlEAdRyqOYUe', '2016-09-08 07:22:39');

-- --------------------------------------------------------

--
-- Table structure for table `variable`
--

CREATE TABLE `variable` (
  `variable_id` int(11) NOT NULL,
  `variable_repoid` int(11) NOT NULL,
  `variable_type` varchar(64) NOT NULL,
  `variable_interactorid` text NOT NULL,
  `variable_sessionid` int(11) NOT NULL,
  `variable_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contributor`
--
ALTER TABLE `contributor`
  ADD PRIMARY KEY (`contributor_id`);

--
-- Indexes for table `edge`
--
ALTER TABLE `edge`
  ADD PRIMARY KEY (`edge_id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`node_id`);

--
-- Indexes for table `repo`
--
ALTER TABLE `repo`
  ADD PRIMARY KEY (`repo_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`story_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `variable`
--
ALTER TABLE `variable`
  ADD PRIMARY KEY (`variable_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contributor`
--
ALTER TABLE `contributor`
  MODIFY `contributor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `edge`
--
ALTER TABLE `edge`
  MODIFY `edge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `node_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `repo`
--
ALTER TABLE `repo`
  MODIFY `repo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `story`
--
ALTER TABLE `story`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `variable`
--
ALTER TABLE `variable`
  MODIFY `variable_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
