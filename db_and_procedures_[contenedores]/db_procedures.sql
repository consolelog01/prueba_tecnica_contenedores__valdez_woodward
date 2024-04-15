-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-04-2024 a las 06:29:52
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_contenedor_entrada_salida`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `eliminarEntradaAlmacen`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminarEntradaAlmacen` (IN `$idContenedor` INT)   BEGIN
    DELETE salida_almacen FROM salida_almacen INNER JOIN entrada_almacen ON salida_almacen.entrada_almacenfk = entrada_almacen.entrada_almacenpk
    WHERE entrada_almacen.idcontenedorfk = $idContenedor;
	DELETE FROM entrada_almacen WHERE idcontenedorfk = $idContenedor;
    DELETE FROM contenedor WHERE idcontenedorpk = $idContenedor;
END$$

DROP PROCEDURE IF EXISTS `modificarContenedor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `modificarContenedor` (IN `$numeroContenedor` VARCHAR(11), IN `$tamanioContenedor` VARCHAR(4), IN `$idContenedorConsulta` INT)   BEGIN
	UPDATE contenedor SET numero_contenedor = $numeroContenedor, tamanio_contenedor = $tamanioContenedor WHERE idcontenedorpk = $idContenedorConsulta;
END$$

DROP PROCEDURE IF EXISTS `registrarContenedor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarContenedor` (IN `$numero_contenedor` VARCHAR(11), IN `$tamanio_contenedor` VARCHAR(4))   BEGIN
	INSERT INTO contenedor(numero_contenedor, tamanio_contenedor) VALUES($numero_contenedor, $tamanio_contenedor);
END$$

DROP PROCEDURE IF EXISTS `registrarEntradaContenedor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarEntradaContenedor` (IN `$idcontenedorfk` INT, IN `$fecha_entrada` DATE, IN `$flujo_entrada` CHAR(1), IN `$numero_economico` VARCHAR(25))   BEGIN
	INSERT INTO entrada_almacen (idcontenedorfk, fecha_entrada, flujo_entrada, numero_economico) VALUES($idcontenedorfk, $fecha_entrada, $flujo_entrada, $numero_economico);
END$$

DROP PROCEDURE IF EXISTS `registrarSalidaContenedor`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `registrarSalidaContenedor` (IN `$entrada_almacenfk` INT, IN `$fecha_salida` DATE, IN `$flujo_salida` CHAR(1), IN `$numero_economico` VARCHAR(25))   BEGIN
	INSERT INTO salida_almacen (entrada_almacenfk, fecha_salida, flujo_salida, numero_economico) VALUES($entrada_almacenfk, $fecha_salida, $flujo_salida, $numero_economico);
END$$

DROP PROCEDURE IF EXISTS `validarContenedorAlmacenSinSalida`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `validarContenedorAlmacenSinSalida` (IN `$idcontenedorpk` INT)   BEGIN
	SELECT idcontenedorpk, entrada_almacenpk, salida_almacenpk FROM contenedor 
	INNER JOIN entrada_almacen ON entrada_almacen.idcontenedorfk = contenedor.idcontenedorpk 
	LEFT JOIN salida_almacen ON salida_almacen.entrada_almacenfk = entrada_almacen.entrada_almacenpk 
	WHERE idcontenedorpk = $idcontenedorpk AND salida_almacen.fecha_salida IS NULL;
END$$

DELIMITER ;
-- ------------------------<.><.><.><.>------------------------

