-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2018 at 10:09 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `answer_machine`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `text` text NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `qid`, `text`, `create_date`) VALUES
(36, 64, 'The below given problems find their solution using divide and conquer algorithm approach âˆ’\r\n\r\nFibonacci number series\r\nKnapsack problem\r\nTower of Hanoi\r\nAll pair shortest path by Floyd-Warshall\r\nShortest path by Dijkstra', '2018-07-14 16:01:41'),
(41, 68, 'Linear search tries to find an item in a sequentially arranged data type. These sequentially arranged data items known as array or list, are accessible in incrementing memory location. Linear search compares expected data item with each of data items in list or array. The average case time complexity of linear search is ÎŸ(n) and worst case complexity is ÎŸ(n2). Data in target arrays/lists need not to be sorted.', '2018-07-14 16:18:28'),
(42, 70, 'Depth First Search algorithm(DFS) traverses a graph in a depthward motion and uses a stack to remember to get the next vertex to start a search when a dead end occurs in any iteration.', '2018-07-14 16:20:02'),
(43, 69, 'A graph is a pictorial representation of a set of objects where some pairs of objects are connected by links. The interconnected objects are represented by points termed as vertices, and the links that connect the vertices are called edges.', '2018-07-14 16:20:15'),
(44, 72, 'Interpolation search is an improved variant of binary search. This search algorithm works on the probing position of required value.', '2018-07-14 16:21:28'),
(45, 73, 'In a weighted graph, a minimum spanning tree is a spanning tree that has minimum weight that all other spanning trees of the same graph.', '2018-07-14 16:21:58'),
(46, 74, 'A tree is a minimally connected graph having no loops and circuits.', '2018-07-14 16:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `uname` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `status` enum('pending','publish','answered') NOT NULL DEFAULT 'pending',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `uname`, `text`, `status`, `create_date`) VALUES
(64, 'John smith', 'What are some examples of divide and conquer algorithms?', 'answered', '2018-07-14 16:01:20'),
(68, 'John smith', 'What is linear searching?', 'answered', '2018-07-14 16:17:53'),
(69, 'John smith', 'What is a graph?', 'answered', '2018-07-14 16:19:24'),
(70, 'abood', 'How depth first traversal works?', 'answered', '2018-07-14 16:19:44'),
(72, 'John smith', 'What is interpolation search technique?', 'answered', '2018-07-14 16:21:17'),
(73, 'osahmad', 'What is a minimum spanning tree (MST)?', 'answered', '2018-07-14 16:21:45'),
(74, 'John smith', 'What is a tree?', 'answered', '2018-07-14 16:22:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_ibfk_1` (`qid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`qid`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
