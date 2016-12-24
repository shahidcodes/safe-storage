-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2016 at 03:30 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `safestorage`
--

-- --------------------------------------------------------

--
-- Table structure for table `file_meta`
--

CREATE TABLE IF NOT EXISTS `file_meta` (
`id` int(11) NOT NULL,
  `filename` varchar(20) NOT NULL,
  `checksum` varchar(64) NOT NULL,
  `date` datetime NOT NULL,
  `mime` varchar(10) NOT NULL,
  `stored_name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `file_meta`
--

INSERT INTO `file_meta` (`id`, `filename`, `checksum`, `date`, `mime`, `stored_name`) VALUES
(1, '9sHqlvg.jpg', '505f1563856e4a126003968889de3bfc', '2016-12-23 17:44:01', '', '27441a04270e6b4b8860a110b8dfc686'),
(2, 'cTDdGGQ.jpg', 'c913f07e9545b4beab376b10394795fc', '2016-12-23 18:28:49', '', '3450e61d6f04385d86ac42298129ccd2'),
(3, '2RQ8Phs.jpg', '51e0c21f3ce8adaf871a41b8136ee04f', '2016-12-23 18:44:05', '', '0f2d9f108ad0a1342d35a6bd3dadc46f'),
(4, 'ha3CMCf.jpg', '442974adad5a3da771950babb06b8962', '2016-12-23 19:11:08', '', '08e210f732dda8e62defcaa4978a11e2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `name` text NOT NULL,
  `salt` varchar(64) NOT NULL,
  `joined` datetime NOT NULL,
  `group` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `salt`, `joined`, `group`) VALUES
(2, 'admin', 'b8d03cb036627782a113189d2343ceb7937c42933a72ec7bb7350f82aa84bba3', 'Admin', 'ðk¹ib‘ì…!_á‡ßï~«jØˆÀ4Y­å žg', '2016-12-21 18:27:08', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE IF NOT EXISTS `users_session` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_session`
--

INSERT INTO `users_session` (`id`, `user_id`, `hash`) VALUES
(1, 1, '1ee0deace4cef79b8b41ab328f419c4f36e944e67c2594a88f1248f3db99f08f'),
(3, 2, '33d4002f7e3e37b411c1138e75ee4170545dcceae9b87bfcad08c9b1a3f2a4b0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file_meta`
--
ALTER TABLE `file_meta`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_meta`
--
ALTER TABLE `file_meta`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users_session`
--
ALTER TABLE `users_session`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
