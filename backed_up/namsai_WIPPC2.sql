-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2017 at 06:56 AM
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
  `id` int(11) NOT NULL,
  `repoid` int(11) NOT NULL,
  `storyid` int(11) NOT NULL,
  `nodeid` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `nodenext` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `edge`
--

INSERT INTO `edge` (`id`, `repoid`, `storyid`, `nodeid`, `priority`, `nodenext`) VALUES
(22, 1, 1, 1, 1, 24),
(23, 1, 1, 1, 2, 25),
(28, 1, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `node`
--

CREATE TABLE `node` (
  `id` int(11) NOT NULL,
  `repoid` int(11) NOT NULL,
  `storyid` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `pattern` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `node`
--

INSERT INTO `node` (`id`, `repoid`, `storyid`, `type`, `value`, `pattern`) VALUES
(1, 1, 1, 'pattern', 'สวัสดี', '^สวัสดี$'),
(6, 1, 1, 'pattern', '', '^$'),
(7, 1, 1, 'pattern', 'kkk', '^kkk$'),
(8, 1, 1, 'pattern', '', '^$'),
(24, 1, 1, 'response', 'สวัสดีจ๊ะ', ''),
(25, 1, 1, 'response', 'มีอะไรให้ช่วยคะ{1}', '');

-- --------------------------------------------------------

--
-- Table structure for table `repo`
--

CREATE TABLE `repo` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `private` tinyint(4) NOT NULL,
  `repo_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `repo`
--

INSERT INTO `repo` (`id`, `owner`, `name`, `description`, `private`, `repo_update`) VALUES
(1, 1, 'irin', 'my dauther', 0, '2016-11-25 15:53:00'),
(2, 1, 'Misume', 'hello', 0, '2016-12-23 12:43:16'),
(3, 1, 'Misune', 'HH', 0, '2016-12-23 12:43:49');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `repoid` int(11) NOT NULL,
  `interactorid` text NOT NULL,
  `nodeid` int(11) NOT NULL,
  `session_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `repoid`, `interactorid`, `nodeid`, `session_update`) VALUES
(20, 1, 'IP_::1', 231, '2016-10-16 13:33:20'),
(21, 1, 'IP_::1', 0, '2016-10-16 14:08:19'),
(22, 1, 'IP_::1', 231, '2016-10-16 16:43:34'),
(23, 1, 'IP_::1', 231, '2016-10-16 16:48:47'),
(24, 1, 'IP_::1', 231, '2016-11-12 21:30:04'),
(25, 0, 'test', 1, '2017-01-01 11:59:44'),
(26, 1, 'IP_::1', 0, '2017-01-04 09:43:01'),
(27, 1, 'IP_::1', 0, '2017-01-04 09:54:18');

-- --------------------------------------------------------

--
-- Table structure for table `story`
--

CREATE TABLE `story` (
  `id` int(11) NOT NULL,
  `repoid` int(11) NOT NULL,
  `name` text NOT NULL,
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `story`
--

INSERT INTO `story` (`id`, `repoid`, `name`, `priority`) VALUES
(1, 1, 'สวัสดี', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `username` varchar(64) NOT NULL,
  `name` text NOT NULL,
  `bio` text NOT NULL,
  `hash` varchar(2048) NOT NULL,
  `user_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `name`, `bio`, `hash`, `user_update`) VALUES
(1, 'pure.gif@gmail.com', 'pureexe', 'Pakkapon Phongthawee', 'Nakhon Pathom,Thailand', '$2a$08$rHAyhoSVO4lg87nx9U9ZiOmeitHgBZa/Opb46FUegGlEAdRyqOYUe', '2016-12-19 11:58:31'),
(2, 'dev@irin.in.th', 'dev', 'Irin Phongthawee', 'BOT DEVELOPER', '$2a$08$rHAyhoSVO4lg87nx9U9ZiOmeitHgBZa/Opb46FUegGlEAdRyqOYUe', '2016-09-08 07:22:39');

-- --------------------------------------------------------

--
-- Table structure for table `variable`
--

CREATE TABLE `variable` (
  `id` int(11) NOT NULL,
  `repoid` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `interactorid` text NOT NULL,
  `sessionid` int(11) NOT NULL,
  `value` text NOT NULL
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `node`
--
ALTER TABLE `node`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `repo`
--
ALTER TABLE `repo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variable`
--
ALTER TABLE `variable`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `node`
--
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `repo`
--
ALTER TABLE `repo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `story`
--
ALTER TABLE `story`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `variable`
--
ALTER TABLE `variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
