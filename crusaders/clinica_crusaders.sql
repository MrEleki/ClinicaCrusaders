-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-07-2025 a las 03:56:50
-- Versión del servidor: 8.0.42-0ubuntu0.24.04.1
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clinica_crusaders`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cita`
--

CREATE TABLE `cita` (
  `id` int NOT NULL,
  `cliente` int DEFAULT NULL,
  `doctor` int DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `consultorio` int DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cita`
--

INSERT INTO `cita` (`id`, `cliente`, `doctor`, `estado`, `consultorio`, `fecha`) VALUES
(6, 1, 1, 1, 1, '2025-07-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultorio`
--

CREATE TABLE `consultorio` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `estado` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `consultorio`
--

INSERT INTO `consultorio` (`id`, `nombre`, `direccion`, `codigo`, `estado`) VALUES
(1, 'Principal', 'Avenida Este 6, entre las avenidas México y Norte 13 en Caracas', '1', 1),
(2, 'Hoyada', 'Avenida Universidad, entre las esquinas de Corazón de Jesús y Coliseo', '2', 1),
(3, 'Chacao', 'Avenida Francisco de Miranda, en el sector de Chacao, dentro del municipio Chacao, estado Miranda, específicamente en el centro-este de Caracas', '3', 1),
(4, 'Petare', 'Avenida Principal de La Urbina, en el sector Petare del municipio Sucre del estado Miranda', '4', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctores`
--

CREATE TABLE `doctores` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `especializacion` int DEFAULT NULL,
  `horario` int DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `cantidad_citas` int DEFAULT '0',
  `disponibilidad` tinyint(1) DEFAULT '1',
  `consultorio` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `doctores`
--

INSERT INTO `doctores` (`id`, `nombre`, `apellido`, `especializacion`, `horario`, `telefono`, `cantidad_citas`, `disponibilidad`, `consultorio`) VALUES
(1, 'Gregory', 'House', 6, NULL, '12345678910', 0, 1, 1),
(2, 'Chad', 'Jepetti', 1, NULL, '75649878945', 0, 1, 4),
(3, 'Leonard', 'McCoy', 5, NULL, '65498712346', 0, 1, 2),
(4, 'Nick', 'Riviera', 2, NULL, '12375389712', 0, 1, 3),
(5, 'Bruce', 'Banner', 3, NULL, '04141238097', 0, 1, 3),
(6, 'Jhon', 'Watson', 4, NULL, '04125348769', 0, 1, 1),
(7, 'Heinz', 'Doofenshmirtz', 7, NULL, '02124357945', 0, 1, 3),
(8, 'Shaun', 'Murphy', 8, NULL, '04240978432', 0, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especializacion`
--

CREATE TABLE `especializacion` (
  `id` int NOT NULL,
  `especializacion` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `especializacion`
--

INSERT INTO `especializacion` (`id`, `especializacion`) VALUES
(1, 'Cardiología'),
(2, 'Pediatría'),
(3, 'Obstetricía'),
(4, 'Dermatología'),
(5, 'Neurología'),
(6, 'Neumologia'),
(7, 'Oncología'),
(8, 'Urología');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_cita`
--

CREATE TABLE `estado_cita` (
  `id` int NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estado_cita`
--

INSERT INTO `estado_cita` (`id`, `estado`) VALUES
(1, 'pendiente'),
(2, 'aceptada'),
(3, 'cancelada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_consultorio`
--

CREATE TABLE `estado_consultorio` (
  `id` int NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estado_consultorio`
--

INSERT INTO `estado_consultorio` (`id`, `estado`) VALUES
(1, 'activo'),
(2, 'inactivo'),
(3, 'remodelación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id` int NOT NULL,
  `inicio` time NOT NULL,
  `fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_registro` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `apellido`, `email`, `password`, `telefono`, `fecha_registro`) VALUES
(1, 'Daniel', 'Palacios', 'MrEleki488@proton.me', '$argon2id$v=19$m=65536,t=4,p=2$TmpmMG4uUE9JRUppblNHZA$J4ECkQPojnPNYii/ifAY3rUczwTeXcWsNY9yLRt5dks', '12345678901123', '2025-07-09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cita`
--
ALTER TABLE `cita`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente` (`cliente`),
  ADD KEY `doctor` (`doctor`),
  ADD KEY `estado` (`estado`),
  ADD KEY `consultorio` (`consultorio`);

--
-- Indices de la tabla `consultorio`
--
ALTER TABLE `consultorio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `doctores`
--
ALTER TABLE `doctores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `especializacion` (`especializacion`),
  ADD KEY `horario` (`horario`),
  ADD KEY `consultorio` (`consultorio`);

--
-- Indices de la tabla `especializacion`
--
ALTER TABLE `especializacion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_consultorio`
--
ALTER TABLE `estado_consultorio`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cita`
--
ALTER TABLE `cita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `consultorio`
--
ALTER TABLE `consultorio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `doctores`
--
ALTER TABLE `doctores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `especializacion`
--
ALTER TABLE `especializacion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estado_cita`
--
ALTER TABLE `estado_cita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_consultorio`
--
ALTER TABLE `estado_consultorio`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cita`
--
ALTER TABLE `cita`
  ADD CONSTRAINT `cita_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `cita_ibfk_2` FOREIGN KEY (`doctor`) REFERENCES `doctores` (`id`),
  ADD CONSTRAINT `cita_ibfk_3` FOREIGN KEY (`estado`) REFERENCES `estado_cita` (`id`),
  ADD CONSTRAINT `cita_ibfk_4` FOREIGN KEY (`consultorio`) REFERENCES `consultorio` (`id`);

--
-- Filtros para la tabla `consultorio`
--
ALTER TABLE `consultorio`
  ADD CONSTRAINT `consultorio_ibfk_1` FOREIGN KEY (`estado`) REFERENCES `estado_consultorio` (`id`);

--
-- Filtros para la tabla `doctores`
--
ALTER TABLE `doctores`
  ADD CONSTRAINT `doctores_ibfk_1` FOREIGN KEY (`especializacion`) REFERENCES `especializacion` (`id`),
  ADD CONSTRAINT `doctores_ibfk_2` FOREIGN KEY (`horario`) REFERENCES `horario` (`id`),
  ADD CONSTRAINT `doctores_ibfk_3` FOREIGN KEY (`consultorio`) REFERENCES `consultorio` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
