-- database/schema.sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `proyecto` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `proyecto`;

-- Estructura de tabla para la tabla `propietarios`
CREATE TABLE IF NOT EXISTS `propietarios` (
  `Idpropietario` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  PRIMARY KEY (`Idpropietario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `taxis`
CREATE TABLE IF NOT EXISTS `taxis` (
  `Placa` int(11) NOT NULL AUTO_INCREMENT,
  `Modelo` varchar(100) NOT NULL,
  `Marca` varchar(100) NOT NULL,
  `Idpropietario` int(11) NOT NULL,
  PRIMARY KEY (`Placa`),
  KEY `Idpropietario` (`Idpropietario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `conductores`
CREATE TABLE IF NOT EXISTS `conductores` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(255) NOT NULL,
  `Telefono` varchar(10) NOT NULL,
  `Placa` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Placa` (`Placa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nombres` varchar(100) NOT NULL,
  `Apellidos` varchar(100) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `remember_token_expires` datetime DEFAULT NULL,
  `Correo` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `idx_usuarios_remember_token` (`remember_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Restricciones
ALTER TABLE `conductores`
  ADD CONSTRAINT `conductores_ibfk_1` FOREIGN KEY (`Placa`) REFERENCES `taxis` (`Placa`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `taxis`
  ADD CONSTRAINT `taxis_ibfk_2` FOREIGN KEY (`Idpropietario`) REFERENCES `propietarios` (`Idpropietario`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Estructura de tabla para la tabla `audit_log`
CREATE TABLE IF NOT EXISTS `audit_log` (
  `id`             int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id`     int(11) NULL,
  `usuario_nombre` varchar(150) NOT NULL DEFAULT 'sistema',
  `accion`         varchar(60) NOT NULL,
  `entidad`        varchar(40) NOT NULL,
  `entidad_id`     varchar(60) NULL,
  `descripcion`    varchar(255) NOT NULL,
  `ip`             varchar(45) NULL,
  `creado_en`      datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_entidad`     (`entidad`),
  KEY `idx_audit_usuario`     (`usuario_id`),
  KEY `idx_audit_creado`      (`creado_en`),
  KEY `idx_audit_entidad_eid` (`entidad`, `entidad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
