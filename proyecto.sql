-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-06-2023 a las 00:24:42
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--
CREATE DATABASE IF NOT EXISTS `proyecto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `proyecto`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductores`
--
-- Creación: 29-05-2023 a las 19:57:27
--

CREATE TABLE IF NOT EXISTS `conductores` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(255) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  `Placa` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Placa` (`Placa`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `conductores`:
--   `Placa`
--       `taxis` -> `Placa`
--

--
-- Volcado de datos para la tabla `conductores`
--

INSERT INTO `conductores` (`ID`, `Nombres`, `Telefono`, `Placa`) VALUES(11, 'Jose Enrique Bustamante', '62515487', 7);
INSERT INTO `conductores` (`ID`, `Nombres`, `Telefono`, `Placa`) VALUES(13, 'Jose', '78454653', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietarios`
--
-- Creación: 29-05-2023 a las 20:25:31
--

CREATE TABLE IF NOT EXISTS `propietarios` (
  `Idpropietario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`Idpropietario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `propietarios`:
--

--
-- Volcado de datos para la tabla `propietarios`
--

INSERT INTO `propietarios` (`Idpropietario`, `Nombre`, `Telefono`) VALUES(5, 'Ronaldo Muruchi Mendoza', '78784889');
INSERT INTO `propietarios` (`Idpropietario`, `Nombre`, `Telefono`) VALUES(6, 'Raúl Mendoza Zambrana', '75252637');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `taxis`
--
-- Creación: 29-05-2023 a las 20:28:05
--

CREATE TABLE IF NOT EXISTS `taxis` (
  `Placa` int(11) NOT NULL AUTO_INCREMENT,
  `Modelo` varchar(100) NOT NULL,
  `Marca` varchar(100) NOT NULL,
  `Idpropietario` int(11) NOT NULL,
  PRIMARY KEY (`Placa`),
  KEY `Idpropietario` (`Idpropietario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `taxis`:
--   `Idpropietario`
--       `propietarios` -> `Idpropietario`
--

--
-- Volcado de datos para la tabla `taxis`
--

INSERT INTO `taxis` (`Placa`, `Modelo`, `Marca`, `Idpropietario`) VALUES(6, 'BMW Descapotable', 'Suzuki', 5);
INSERT INTO `taxis` (`Placa`, `Modelo`, `Marca`, `Idpropietario`) VALUES(7, 'Mini Van', 'Toyota', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--
-- Creación: 29-05-2023 a las 20:41:27
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Clave` varchar(50) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELACIONES PARA LA TABLA `usuarios`:
--

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `Nombres`, `Apellidos`, `Usuario`, `Clave`, `Correo`) VALUES(1, 'José Andrés', 'Meneces Lopez', 'Administrador', '1234', 'jandrespb4@gmail.com');
INSERT INTO `usuarios` (`ID`, `Nombres`, `Apellidos`, `Usuario`, `Clave`, `Correo`) VALUES(3, 'Juan Juanito', 'Pérez Mamio', 'Perez15', 'password', 'JuanPerez@hotmail.com');
INSERT INTO `usuarios` (`ID`, `Nombres`, `Apellidos`, `Usuario`, `Clave`, `Correo`) VALUES(4, 'John Bold', 'Travolta', 'Pulp Fiction', 'hand gun', 'Travolta007@gmail.com');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `conductores`
--
ALTER TABLE `conductores`
  ADD CONSTRAINT `conductores_ibfk_1` FOREIGN KEY (`Placa`) REFERENCES `taxis` (`Placa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `taxis`
--
ALTER TABLE `taxis`
  ADD CONSTRAINT `taxis_ibfk_2` FOREIGN KEY (`Idpropietario`) REFERENCES `propietarios` (`Idpropietario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
