-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-12-2023 a las 20:20:47
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
-- Base de datos: `bot_tesla`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

CREATE TABLE `area` (
  `id_area` int(11) NOT NULL,
  `nombre_area` text NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`id_area`, `nombre_area`, `estado`) VALUES
(1, 'administracion', 0),
(2, 'Diseño', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flujos`
--

CREATE TABLE `flujos` (
  `id_flujo` int(11) NOT NULL,
  `nombre_flujo` text NOT NULL,
  `palabra_clave` text NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `flujos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interaccion`
--

CREATE TABLE `interaccion` (
  `id_interaccion` int(11) NOT NULL,
  `numero_user` int(11) NOT NULL,
  `mensaje_recibido` text NOT NULL,
  `id_asistente` int(11) NOT NULL,
  `mensaje_enviado_id` int(11) NOT NULL,
  `fecha_interaccion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `interaccion`
--

--
-- Estructura de tabla para la tabla `msg_armados`
--

CREATE TABLE `msg_armados` (
  `id_msg` int(11) NOT NULL,
  `nombre_msg` varchar(24) NOT NULL,
  `encabezado_msg` varchar(60) NOT NULL,
  `cuerpo_msg` varchar(1024) NOT NULL,
  `pie_msg` char(60) NOT NULL,
  `tipo_msg` int(11) NOT NULL,
  `estado_msg` int(11) NOT NULL,
  `flujo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `msg_armados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `msg_tipo`
--

CREATE TABLE `msg_tipo` (
  `id_tipo` int(11) NOT NULL,
  `descripcion_tipo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `msg_tipo`
--

INSERT INTO `msg_tipo` (`id_tipo`, `descripcion_tipo`) VALUES
(1, 'Texto'),
(2, 'Boton'),
(3, 'Lista');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeros`
--

CREATE TABLE `numeros` (
  `id_numero` int(11) NOT NULL,
  `telefono_id` bigint(11) NOT NULL,
  `area` int(11) NOT NULL,
  `numero` bigint(11) NOT NULL,
  `flujo_id` int(11) NOT NULL,
  `token_api` text NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `numeros`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `numeros_usuarios`
--

CREATE TABLE `numeros_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `numero` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `numeros_usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relaciones`
--

CREATE TABLE `relaciones` (
  `id_relacion` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `opciones_id` int(11) NOT NULL,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `relaciones`
--
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `flujos`
--
ALTER TABLE `flujos`
  ADD PRIMARY KEY (`id_flujo`);

--
-- Indices de la tabla `interaccion`
--
ALTER TABLE `interaccion`
  ADD PRIMARY KEY (`id_interaccion`),
  ADD KEY `mensaje_enviado_id` (`mensaje_enviado_id`),
  ADD KEY `id_asistente` (`id_asistente`),
  ADD KEY `numero_user` (`numero_user`);

--
-- Indices de la tabla `msg_armados`
--
ALTER TABLE `msg_armados`
  ADD PRIMARY KEY (`id_msg`),
  ADD KEY `tipo_msg` (`tipo_msg`),
  ADD KEY `flujo` (`flujo_id`);

--
-- Indices de la tabla `msg_tipo`
--
ALTER TABLE `msg_tipo`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `numeros`
--
ALTER TABLE `numeros`
  ADD PRIMARY KEY (`id_numero`),
  ADD KEY `flujo_asignado` (`flujo_id`),
  ADD KEY `area` (`area`);

--
-- Indices de la tabla `numeros_usuarios`
--
ALTER TABLE `numeros_usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `relaciones`
--
ALTER TABLE `relaciones`
  ADD PRIMARY KEY (`id_relacion`),
  ADD KEY `msg_id` (`msg_id`),
  ADD KEY `opciones_id` (`opciones_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `flujos`
--
ALTER TABLE `flujos`
  MODIFY `id_flujo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `interaccion`
--
ALTER TABLE `interaccion`
  MODIFY `id_interaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=437;

--
-- AUTO_INCREMENT de la tabla `msg_armados`
--
ALTER TABLE `msg_armados`
  MODIFY `id_msg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT de la tabla `msg_tipo`
--
ALTER TABLE `msg_tipo`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `numeros`
--
ALTER TABLE `numeros`
  MODIFY `id_numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `numeros_usuarios`
--
ALTER TABLE `numeros_usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `relaciones`
--
ALTER TABLE `relaciones`
  MODIFY `id_relacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `interaccion`
--
ALTER TABLE `interaccion`
  ADD CONSTRAINT `interaccion_ibfk_1` FOREIGN KEY (`mensaje_enviado_id`) REFERENCES `msg_armados` (`id_msg`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interaccion_ibfk_2` FOREIGN KEY (`id_asistente`) REFERENCES `numeros` (`id_numero`),
  ADD CONSTRAINT `interaccion_ibfk_3` FOREIGN KEY (`numero_user`) REFERENCES `numeros_usuarios` (`id_usuario`);

--
-- Filtros para la tabla `msg_armados`
--
ALTER TABLE `msg_armados`
  ADD CONSTRAINT `msg_armados_ibfk_1` FOREIGN KEY (`tipo_msg`) REFERENCES `msg_tipo` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `msg_armados_ibfk_2` FOREIGN KEY (`flujo_id`) REFERENCES `flujos` (`id_flujo`);

--
-- Filtros para la tabla `numeros`
--
ALTER TABLE `numeros`
  ADD CONSTRAINT `numeros_ibfk_2` FOREIGN KEY (`area`) REFERENCES `area` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `numeros_ibfk_3` FOREIGN KEY (`flujo_id`) REFERENCES `flujos` (`id_flujo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `relaciones`
--
ALTER TABLE `relaciones`
  ADD CONSTRAINT `relaciones_ibfk_1` FOREIGN KEY (`msg_id`) REFERENCES `msg_armados` (`id_msg`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relaciones_ibfk_2` FOREIGN KEY (`opciones_id`) REFERENCES `msg_armados` (`id_msg`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
