-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 17-01-2026 a las 21:52:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pryta`
--
DROP DATABASE IF EXISTS `pryta`;
CREATE DATABASE IF NOT EXISTS `pryta` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pryta`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `started_at` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `assigned_team` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `started_at`, `due_date`, `assigned_team`) VALUES
(6, 'Aprende Laravel', 'Instalación e introducción a Laravel', '2026-01-16', '2026-02-20', 11),
(7, 'Componentes React', 'Implementar componente de paginación con React', '2026-01-02', '2026-01-30', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `state` enum('Not assigned','Pending','On review','Finished') NOT NULL DEFAULT 'Not assigned',
  `started_on` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `member_assigned` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `task`
--

INSERT INTO `task` (`id`, `name`, `description`, `state`, `started_on`, `due_date`, `project_id`, `member_assigned`) VALUES
(1, 'Instalación', 'Instala Laragon e investiga como funciona', 'Pending', '2026-01-16', '2026-01-20', 6, 31),
(2, 'Php', 'Instala php 8.4 en Laragon', 'Pending', '2026-01-16', '2026-01-20', 6, 31),
(3, 'Node', 'Preparar entorno para instalar React', 'On review', '2026-01-16', '2026-01-20', 7, 30),
(4, 'React', 'Instala React y configura package.json', 'On review', '2026-01-16', '2026-01-20', 7, 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `team`
--

DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `team_leader` int(11) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `team`
--

INSERT INTO `team` (`id`, `name`, `description`, `creation_date`, `team_leader`, `is_available`) VALUES
(10, 'Equipo Frontend', 'Usa bootstrap en las tablas', '2026-01-12 00:00:00', 30, 1),
(11, 'Equipo Backend', 'Implementa arquitectura MVC', '2026-01-12 00:00:00', 29, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `name` varchar(40) NOT NULL,
  `surname` varchar(40) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `role` enum('Superadmin','Team Leader','Software Engineer','') NOT NULL DEFAULT 'Software Engineer',
  `email` varchar(254) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `name`, `surname`, `passwd`, `role`, `email`, `verified`, `active`, `team_id`) VALUES
(29, 'admin1', 'Administrador', 'Pryta', '$2y$10$KCbx1SCkPXOsJ8TvOOyM.O4Vmd7/ruV8/Uc4OoyhlQjNusRQ8P.VG', 'Superadmin', 'correo@ejemplo.com', 0, 1, 11),
(30, 'leader2', 'Team Leader', 'Pryta', '$2y$10$CBLOcFcnFWAToDj2ToXEc.lvG6cBGUouHb9O5VhpSprxNj.ur2GO2', 'Team Leader', 'correo@ejemplo.com', 1, 1, 10),
(31, 'develop3', 'Developer', 'Pryta', '$2y$10$YGpwhLrg8HZuqfpWSzNdjeKE7AE5qE7Lv2Vw.QFFmnhxnT.9Vywg6', 'Software Engineer', 'correo@ejemplo.com', 1, 1, 10),
(32, 'admin2', 'Admin', 'Pryta', '$2y$10$tDH.HmqqZT0EfmI7AxULJuwKPm.XcddUVaTOxx8R4gU9Mcbn2XZau', 'Superadmin', 'correo@ejemplo.com', 0, 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project_team` (`assigned_team`);

--
-- Indices de la tabla `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_task_project` (`project_id`),
  ADD KEY `fk_task_user` (`member_assigned`);

--
-- Indices de la tabla `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_leader` (`team_leader`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_project_team` FOREIGN KEY (`assigned_team`) REFERENCES `team` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_task_user` FOREIGN KEY (`member_assigned`) REFERENCES `user` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `fk_team_leader` FOREIGN KEY (`team_leader`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

-- Crear usuarios
CREATE USER IF NOT EXISTS 'teamleader'@'localhost' IDENTIFIED BY 'pryta_team123';
CREATE USER IF NOT EXISTS 'superadmin'@'localhost' IDENTIFIED BY 'pryta_admin123';
CREATE USER IF NOT EXISTS 'developer'@'localhost' IDENTIFIED BY 'pryta_develop123';

-- Dar permisos
GRANT USAGE ON pryta.* TO 'teamleader'@'localhost';
GRANT SELECT,INSERT, UPDATE ON pryta.task TO 'teamleader'@'localhost';

GRANT ALL PRIVILEGES ON pryta.* TO 'superadmin'@'localhost';

GRANT USAGE ON pryta.* TO 'developer'@'localhost';
GRANT SELECT on pryta.* TO 'developer'@'localhost';
GRANT UPDATE ON pryta.task TO 'developer'@'localhost';

-- Aplicar cambios
FLUSH PRIVILEGES;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
