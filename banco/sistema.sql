-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 26, 2014 at 03:09 PM
-- Server version: 5.6.14
-- PHP Version: 5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sistema`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `pk_admin` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(20) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `permissao` int(11) NOT NULL,
  PRIMARY KEY (`pk_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`pk_admin`, `usuario`, `senha`, `nome`, `permissao`) VALUES
(1, 'admin', '88052b22c8c2349c0599bd39a654c534', 'Administrador', 10);

-- --------------------------------------------------------

--
-- Table structure for table `alunos`
--

CREATE TABLE IF NOT EXISTS `alunos` (
  `ra` varchar(10) NOT NULL,
  `fk_curso` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`ra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `alunos`
--

INSERT INTO `alunos` (`ra`, `fk_curso`, `nome`, `sexo`, `email`) VALUES
('11024783', 1, 'Renato Nori', 'M', 'renatoioshida@gmail.com'),
('11194982', 1, 'Vitor Novo', 'M', 'vitornovos@gmail.com'),
('12121314', 2, 'Henrique', 'M', 'eduardo@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE IF NOT EXISTS `cursos` (
  `pk_curso` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`pk_curso`),
  KEY `pk_curso` (`pk_curso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cursos`
--

INSERT INTO `cursos` (`pk_curso`, `nome`) VALUES
(1, 'Engenharia de Computação'),
(2, 'Engenharia Civil'),
(3, 'Engenharia Ambiental');

-- --------------------------------------------------------

--
-- Table structure for table `log_acesso`
--

CREATE TABLE IF NOT EXISTS `log_acesso` (
  `pk_acesso` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `navegador` text NOT NULL,
  `fk_admin` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`pk_acesso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `materias`
--

CREATE TABLE IF NOT EXISTS `materias` (
  `pk_materia` int(11) NOT NULL AUTO_INCREMENT,
  `fk_curso` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `creditos` int(11) NOT NULL,
  PRIMARY KEY (`pk_materia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `materias`
--

INSERT INTO `materias` (`pk_materia`, `fk_curso`, `nome`, `creditos`) VALUES
(1, 1, 'Cálculo A', 4),
(2, 2, 'Cálculo I', 4),
(3, 1, 'Sistemas de Informação', 4);

-- --------------------------------------------------------

--
-- Table structure for table `professores`
--

CREATE TABLE IF NOT EXISTS `professores` (
  `rp` varchar(10) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`rp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `professores`
--

INSERT INTO `professores` (`rp`, `nome`, `sexo`, `email`) VALUES
('P1000', 'Otávio', 'M', 'otavio@gmail.com'),
('P1001', 'Juan', 'M', 'juan@gmail.com'),
('P1002', 'Fernando K', 'M', 'fek@gmail.com'),
('P1003', 'Thiago Aguirre', 'M', 'thiago@gmail.com');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
