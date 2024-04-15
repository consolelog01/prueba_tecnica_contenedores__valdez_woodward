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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contenedor`
--

DROP TABLE IF EXISTS `contenedor`;
CREATE TABLE `contenedor` (
  `idcontenedorpk` int(11) NOT NULL,
  `numero_contenedor` varchar(11) NOT NULL,
  `tamanio_contenedor` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrada_almacen`
--

DROP TABLE IF EXISTS `entrada_almacen`;
CREATE TABLE `entrada_almacen` (
  `entrada_almacenpk` int(11) NOT NULL,
  `idcontenedorfk` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `flujo_entrada` char(1) NOT NULL COMMENT 'S = Si',
  `numero_economico` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salida_almacen`
--

DROP TABLE IF EXISTS `salida_almacen`;
CREATE TABLE `salida_almacen` (
  `salida_almacenpk` int(11) NOT NULL,
  `entrada_almacenfk` int(11) NOT NULL,
  `fecha_salida` date DEFAULT NULL,
  `flujo_salida` char(1) NOT NULL DEFAULT 'N' COMMENT 'S = Si\nN = No',
  `numero_economico` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contenedor`
--
ALTER TABLE `contenedor`
  ADD PRIMARY KEY (`idcontenedorpk`);

--
-- Indices de la tabla `entrada_almacen`
--
ALTER TABLE `entrada_almacen`
  ADD PRIMARY KEY (`entrada_almacenpk`),
  ADD KEY `fk_entrada_contenedor_idx` (`idcontenedorfk`);

--
-- Indices de la tabla `salida_almacen`
--
ALTER TABLE `salida_almacen`
  ADD PRIMARY KEY (`salida_almacenpk`),
  ADD KEY `fk_salida_almacen_entrada_almacen1_idx` (`entrada_almacenfk`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `contenedor`
--
ALTER TABLE `contenedor`
  MODIFY `idcontenedorpk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entrada_almacen`
--
ALTER TABLE `entrada_almacen`
  MODIFY `entrada_almacenpk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salida_almacen`
--
ALTER TABLE `salida_almacen`
  MODIFY `salida_almacenpk` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entrada_almacen`
--
ALTER TABLE `entrada_almacen`
  ADD CONSTRAINT `fk_entrada_contenedor` FOREIGN KEY (`idcontenedorfk`) REFERENCES `contenedor` (`idcontenedorpk`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `salida_almacen`
--
ALTER TABLE `salida_almacen`
  ADD CONSTRAINT `fk_salida_almacen_entrada_almacen1` FOREIGN KEY (`entrada_almacenfk`) REFERENCES `entrada_almacen` (`entrada_almacenpk`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
