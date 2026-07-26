-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-07-2026 a las 22:52:18
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
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `fecha_creacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `usuario`, `password`, `nombre`, `fecha_creacion`) VALUES
(1, 'admin', '$2y$10$JHfPceS.mXb6iGr74MHQzOzH21JiSvQGAg2uU8b3szKlO4GJN5OK6', 'Administrador Principal', '2026-07-25 16:07:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `email`, `direccion`, `telefono`, `fecha_registro`, `usuario`, `password`) VALUES
(9, 'Administrador', 'admin@gmail.com', 'iacc s/n', '912345678', '2026-07-23 03:09:50', 'admin', '$2y$10$5jQ64nUpN2fGjluNrlZqYuWDhn1fXknRc3RwfZqTOHUMpg53V3w/S'),
(10, 'Gianina Gaete', 'ggaete@gmail.com', 'San Francisco 13102', '992915021', '2026-07-24 20:05:10', 'Nina', '$2y$10$qJX9ggJHWNOHX1z4Qynj0uZB8XcNcjNBLSNrHkMvYy3Vj.SO1NYuS'),
(11, 'Isabella Moraga', 'isa_mor@gmail.com', 'Tromén 349, Concepción', '+56987654321', '2026-07-24 20:46:02', 'Isa', '$2y$10$omDaIT66vGWrVgiVR.AP0OH0L4aWqKNvQ7nvRawR2jI66IyfDvzb6'),
(12, 'Carlos Matías Di Piazza Fariña', 'carlosmdipiazzaf@gmail.com', 'Av General Rondizzoni 1856', '979095565', '2026-07-24 21:44:22', 'carlosmdipiazzaf', '$2y$10$7tKAq1nra65XpZPpvqPj9OYJSmQVmtbBVnj8evWcWqE6M0oDYSpci');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` int(10) NOT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `metodo_pago` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id_compra`, `id_cliente`, `id_producto`, `cantidad`, `total`, `fecha_compra`, `metodo_pago`) VALUES
(13, NULL, 2, 1, 4990, '2026-07-23 02:56:54', 'Tarjeta de Débito'),
(14, NULL, 5, 1, 49990, '2026-07-23 03:01:31', 'Transferencia'),
(15, 10, 5, 1, 49990, '2026-07-24 20:08:10', 'Tarjeta de Débito'),
(16, 10, 1, 1, 324990, '2026-07-24 20:59:12', 'Tarjeta de Débito'),
(17, 9, 6, 1, 59990, '2026-07-24 21:13:54', 'Transferencia'),
(18, 12, 6, 1, 59990, '2026-07-24 22:09:39', 'Tarjeta'),
(19, 12, 1, 1, 324990, '2026-07-24 22:09:39', 'Tarjeta'),
(20, 12, 3, 1, 18990, '2026-07-24 22:19:10', 'WebPay'),
(21, 12, 2, 1, 4990, '2026-07-24 22:20:07', 'Tarjeta'),
(22, 9, 6, 1, 59990, '2026-07-24 22:30:48', 'WebPay'),
(23, 12, 4, 1, 74990, '2026-07-26 02:30:06', 'Transferencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` int(10) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(50) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `categoria`, `imagen`, `fecha_creacion`) VALUES
(1, 'Laptop Dell Inspiron', 'Laptop Dell con procesador Intel i5, 8GB RAM, 256GB SSD', 324990, 200, 'Tecnología', 'https://www.blackmoreit.com/cdn/shop/files/20240920_155238.jpg', '2026-07-09 17:17:53'),
(2, 'Mouse Inalámbrico Logitech', 'Mouse inalámbrico con sensor óptico de alta precisión', 4990, 200, 'Tecnología', 'https://i0.wp.com/maqtech.pe/wp-content/uploads/2022/06/MOUSE-INALAMBRICO-LOGITECH-M170-BLACK_3-100.jpg?fit=1081%2C1081&amp;ssl=1', '2026-07-09 17:17:53'),
(3, 'Teclado Mecánico RGB', 'Teclado mecánico con iluminación RGB personalizable', 18990, 12, 'Tecnología', 'https://guiasopensource.net/wp-content/uploads/teclado-mecanico-hardware-libre-1.webp', '2026-07-09 17:17:53'),
(4, 'Monitor 24 pulgadas', 'Monitor Full HD 1920x1080, 75Hz, HDMI', 74990, 500, 'Tecnología', 'https://intercompras.com/images/productgallery/SAMSUNG_LF27T350FHLXZX_ICECAT_51439174.jpg', '2026-07-09 17:17:53'),
(5, 'Silla Ergonómica', 'Silla ergonómica con soporte lumbar ajustable', 49990, 150, 'Oficina', 'https://m.media-amazon.com/images/I/81Lgluuy9WL._AC_.jpg', '2026-07-09 17:17:53'),
(6, 'Escritorio Moderno', 'Escritorio de madera con cajones, 120x60cm', 59990, 100, 'Oficina', 'https://tse4.mm.bing.net/th/id/OIP.uJJuvFVWl4yvTezDsRMM8wHaHa', '2026-07-09 17:17:53'),
(7, 'Proyector X5 Pro', 'Tecnología ASA 3.0 para enfoque y corrección trapezoidal automática.', 349990, 15, 'Tecnología', 'https://casaroyal.vtexassets.com/arquivos/ids/170218-800-800?v=639034718277430000&width=800&height=800&aspect=true', '2026-07-11 01:05:14');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
