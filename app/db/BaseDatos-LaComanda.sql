-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2021 at 07:47 PM
-- Server version: 8.0.13-4
-- PHP Version: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `VuiVZOayfO`
--

-- --------------------------------------------------------

--
-- Table structure for table `Encuesta`
--

CREATE TABLE `Encuesta` (
  `id` int(11) NOT NULL,
  `puntuacion_descripcion` varchar(66) COLLATE utf8_unicode_ci NOT NULL,
  `puntuacion_mozo` int(11) NOT NULL,
  `puntuacion_mesa` int(11) NOT NULL,
  `puntuacion_cocinero` int(11) NOT NULL,
  `puntuacion_restaurante` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `codigo_cliente` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Encuesta`
--

INSERT INTO `Encuesta` (`id`, `puntuacion_descripcion`, `puntuacion_mozo`, `puntuacion_mesa`, `puntuacion_cocinero`, `puntuacion_restaurante`, `id_mesa`, `codigo_cliente`, `fecha_hora`) VALUES
(1, 'Excelente servicio.', 10, 0, 0, 0, 1, 'C1661', '2021-05-30 10:27:00'),
(2, 'Excelente servicio.', 10, 10, 10, 10, 1, 'C1661', '2021-05-30 10:50:00'),
(3, 'Excelente', 5, 7, 7, 10, 1, 'C1734', '2021-06-12 12:07:00'),
(4, 'Excelente medio', 6, 6, 8, 10, 2, 'C1310', '2021-06-12 12:08:00'),
(5, 'Excelente medio', 6, 6, 8, 10, 2, 'C1310', '2021-06-12 12:10:00'),
(6, 'Excelente medio', 6, 6, 8, 10, 2, 'C1310', '2021-06-12 12:11:00'),
(7, 'Excelente medio', 6, 6, 8, 10, 2, 'C1310', '2021-06-12 12:11:00'),
(8, 'Excelente medio y ahi', 9, 7, 10, 8, 3, 'C1209', '2021-06-12 12:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `Estados_Mesas`
--

CREATE TABLE `Estados_Mesas` (
  `codigo` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Estados_Mesas`
--

INSERT INTO `Estados_Mesas` (`codigo`, `descripcion`) VALUES
(1, 'Con cliente esperando pedido'),
(2, 'Con cliente comiendo'),
(3, 'Con cliente pagando'),
(4, 'Cerrada');

-- --------------------------------------------------------

--
-- Table structure for table `Estados_Pedidos`
--

CREATE TABLE `Estados_Pedidos` (
  `codigo` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Estados_Pedidos`
--

INSERT INTO `Estados_Pedidos` (`codigo`, `descripcion`) VALUES
(1, 'Pendientes'),
(2, 'En preparacion'),
(3, 'Listo para servir'),
(4, 'Cancelado');

-- --------------------------------------------------------

--
-- Table structure for table `e_crypto`
--

CREATE TABLE `e_crypto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `precio` float NOT NULL,
  `foto` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nacionalidad` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `e_crypto`
--

INSERT INTO `e_crypto` (`id`, `nombre`, `precio`, `foto`, `nacionalidad`) VALUES
(2, 'Crypto 2', 200, '', 'paraguay'),
(3, 'Crypto 3', 300, '', 'brasil'),
(4, 'etereum', 1000, '', 'alemana'),
(5, 'bitcoin', 300, '', 'alemana');

-- --------------------------------------------------------

--
-- Table structure for table `e_hortaliza`
--

CREATE TABLE `e_hortaliza` (
  `id` int(11) NOT NULL,
  `precio` float NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_hortaliza` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `e_usuarios`
--

CREATE TABLE `e_usuarios` (
  `id` int(11) NOT NULL,
  `mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(1000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tipo_usuario` varchar(11) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `e_usuarios`
--

INSERT INTO `e_usuarios` (`id`, `mail`, `clave`, `tipo_usuario`) VALUES
(1, 'cc@cc', '$2y$10$V1qHPjDBeH8erhWR9DTLvevDRV8NwJPQDA7JKLRYXIaokj4pCEa7e', 'admin'),
(2, 'aa@cc', '$2y$10$V1qHPjDBeH8erhWR9DTLvevDRV8NwJPQDA7JKLRYXIaokj4pCEa7e', 'cliente'),
(3, 'asd@asd', '$2y$10$XgolksBgLwmA3rB3g92E8eSptCktEpovUtHYy2836jbhsM4VU3F.6', 'cliente'),
(4, 'asdfdsa@asd', '$2y$10$XgolksBgLwmA3rB3g92E8eSptCktEpovUtHYy2836jbhsM4VU3F.6', 'cliente');

-- --------------------------------------------------------

--
-- Table structure for table `e_venta`
--

CREATE TABLE `e_venta` (
  `id_crypto` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_hora` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cantidad_vendida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `e_venta`
--

INSERT INTO `e_venta` (`id_crypto`, `id_cliente`, `fecha_hora`, `cantidad_vendida`) VALUES
(1, 2, '2021-06-14 19:30:57', 300),
(2, 2, '2021-06-14 19:31:09', 500),
(3, 2, '2021-06-14 19:31:21', 250),
(4, 2, '2021-06-14 19:42:35', 250),
(5, 2, '2021-06-14 19:42:43', 250),
(4, 2, '2021-06-14 19:42:57', 250);

-- --------------------------------------------------------

--
-- Table structure for table `Historico_Facturacion_Mesas`
--

CREATE TABLE `Historico_Facturacion_Mesas` (
  `id_mesa` int(11) NOT NULL,
  `codigo_cliente` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `total_consumicion` float NOT NULL,
  `fecha_hora` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_mozo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Historico_Facturacion_Mesas`
--

INSERT INTO `Historico_Facturacion_Mesas` (`id_mesa`, `codigo_cliente`, `total_consumicion`, `fecha_hora`, `id_mozo`) VALUES
(1, 'C1661', 2800, '2021-05-30 10:21', 2),
(1, 'C1524', 1000, '2021-05-29 10:23', 2),
(2, 'C1584', 20000, '2021-05-28 10:23', 2),
(3, 'C1624', 1000, '2021-05-27 10:23', 2),
(3, 'C1742', 500, '2021-05-26 10:23', 2),
(3, 'C1734', 4346.6, '2021-06-12 11:31', 2),
(2, 'C1310', 2000, '2021-06-12 11:45', 8),
(2, 'C1310', 2000, '2021-06-12 11:49', 8),
(3, 'C1734', 4346.6, '2021-06-12 11:50', 2),
(1, 'C1209', 5000, '2021-06-12 11:58', 8);

-- --------------------------------------------------------

--
-- Table structure for table `Logs`
--

CREATE TABLE `Logs` (
  `id` int(100) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sector` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `entidad` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_entidad` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `operacion` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `datos_resultado_operacion` varchar(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `datos_operacion` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Logs`
--

INSERT INTO `Logs` (`id`, `id_usuario`, `id_sector`, `entidad`, `id_entidad`, `operacion`, `datos_resultado_operacion`, `datos_operacion`, `fecha_hora`) VALUES
(1, 2, '5', 'MESAS', '0', 'Cargar uno', '{\"mensaje\":\"Mesa creado con exito: C1478\"}', '{\"nombre_cliente\":\"Goku\"}', '2021-06-07 20:54:00'),
(2, 2, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"1\"}', '{\"id_producto\":\"2\",\"codigo_cliente\":\"C1478\",\"cantidad\":\"2\"}', '2021-06-07 20:55:00'),
(3, 2, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"2\"}', '{\"id_producto\":\"2\",\"codigo_cliente\":\"C1478\",\"cantidad\":\"2\"}', '2021-06-07 21:09:00'),
(4, 3, '1', 'PEDIDOS', '1', 'Modificar uno', '{\"mensaje\":\"Pedido tomado con exito.\"}', '{\"id\":\"1\",\"estado\":\"2\",\"minutos_preparacion\":\"10\"}', '2021-06-07 21:10:00'),
(5, 1, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-08 09:12:00'),
(6, 1, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-09 19:43:00'),
(7, 1, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:09:00'),
(8, 1, '6', 'PRODUCTOS', NULL, 'Obtener', '{\"listaProductos\":[{\"id\":2,\"nombre\":\"Milanesa\",\"precio\":1000,\"tipo\":\"1\"},{\"id\":3,\"nombre\":\"Arroz con leche\",\"precio\":1000,\"tipo\":\"2\"},{\"id\":4,\"nombre\":\"Daikiri\",\"precio\":102.2,\"tipo\":\"3\"},{\"id\":6,\"nombre\":\"Miller\",\"precio\":200,\"tipo\":\"4\"},{\"id\":7,\"nombre\":\"napolitana\",\"precio\":1000,\"tipo\":\"1\\r\\n\"},{\"id\":8,\"nombre\":\"helado\",\"precio\":1000,\"tipo\":\"2\\r\\n\"},{\"id\":9,\"nombre\":\"Daikiri\",\"precio\":1000,\"tipo\":\"3\\r\\n\"},{\"id\":10,\"nombre\":\"Cerveza\",\"precio\":1000,\"tipo\":\"4\"},{\"id\":11,\"nombre\":\"Stella\",\"precio\":250,\"tipo\":\"4\"}]}', 'null', '2021-06-12 10:24:00'),
(9, 1, '6', 'PRODUCTOS', NULL, 'Obtener', '{\"producto\":{\"id\":9,\"nombre\":\"Daikiri\",\"precio\":1000,\"tipo\":\"3\\r\\n\"}}', 'null', '2021-06-12 10:25:00'),
(10, 1, '6', 'PRODUCTOS', '0', 'Cargar uno', '{\"mensaje\":\"Producto creado con exito.\"}', '{\"nombre\":\"Brahma\",\"precio\":\"170\",\"tipo\":\"4\"}', '2021-06-12 10:27:00'),
(11, 1, '6', 'PRODUCTOS', '7', 'Modificar uno', '{\"mensaje\":\"Producto modificado con exito.\"}', '{\"id\":\"7\",\"precio\":\"573.3\"}', '2021-06-12 10:29:00'),
(12, 2, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:30:00'),
(13, 8, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:35:00'),
(14, 8, '5', 'MESAS', '0', 'Cargar uno', '{\"mensaje\":\"Mesa creado con exito: C1209\"}', '{\"nombre_cliente\":\"Cliente Juana\"}', '2021-06-12 10:35:00'),
(15, 8, '5', 'MESAS', '0', 'Cargar uno', '{\"mensaje\":\"Mesa creado con exito: C1310\"}', '{\"nombre_cliente\":\"Cliente Pedro\"}', '2021-06-12 10:36:00'),
(16, 2, '5', 'MESAS', '0', 'Cargar uno', '{\"mensaje\":\"Mesa creado con exito: C1734\"}', '{\"nombre_cliente\":\"Cliente Robocop\"}', '2021-06-12 10:37:00'),
(17, 2, '5', 'ERROR', NULL, 'Error', '{\"error\":\"No hay mesa libre.\"}', '{\"nombre_cliente\":\"Cliente Sin mesa\"}', '2021-06-12 10:37:00'),
(18, 2, '5', 'MESAS', NULL, 'Obtener', '{\"listaMesas\":[{\"id\":1,\"codigo_cliente\":\"C1209\",\"nombre_cliente\":\"Cliente Juana\",\"total_consumicion\":0,\"id_mozo\":8,\"mozo_nombre\":\"Mozo dos\",\"codigo_estado_mesa\":1,\"codigo_estado_mesa_descripcion\":\"Con cliente esperando pedido\",\"fecha_hora_inicio\":\"2021-06-12 10:35:00\",\"fecha_hora_fin\":null,\"libre\":null},{\"id\":2,\"codigo_cliente\":\"C1310\",\"nombre_cliente\":\"Cliente Pedro\",\"total_consumicion\":0,\"id_mozo\":8,\"mozo_nombre\":\"Mozo dos\",\"codigo_estado_mesa\":1,\"codigo_estado_mesa_descripcion\":\"Con cliente esperando pedido\",\"fecha_hora_inicio\":\"2021-06-12 10:36:00\",\"fecha_hora_fin\":null,\"libre\":null},{\"id\":3,\"codigo_cliente\":\"C1734\",\"nombre_cliente\":\"Cliente Robocop\",\"total_consumicion\":0,\"id_mozo\":2,\"mozo_nombre\":\"Mozo\",\"codigo_estado_mesa\":1,\"codigo_estado_mesa_descripcion\":\"Con cliente esperando pedido\",\"fecha_hora_inicio\":\"2021-06-12 10:37:00\",\"fecha_hora_fin\":null,\"libre\":null}]}', 'null', '2021-06-12 10:39:00'),
(19, 2, '5', 'MESAS', NULL, 'Obtener', '{\"id\":2,\"codigo_cliente\":\"C1310\",\"nombre_cliente\":\"Cliente Pedro\",\"total_consumicion\":0,\"id_mozo\":8,\"mozo_nombre\":\"Mozo dos\",\"codigo_estado_mesa\":1,\"codigo_estado_mesa_descripcion\":\"Con cliente esperando pedido\",\"fecha_hora_inicio\":\"2021-06-12 10:36:00\",\"fecha_hora_fin\":null,\"libre\":null}', 'null', '2021-06-12 10:42:00'),
(20, 2, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"3\"}', '{\"id_producto\":\"6\",\"codigo_cliente\":\"C1734\",\"cantidad\":\"1\"}', '2021-06-12 10:44:00'),
(21, 2, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"4\"}', '{\"id_producto\":\"7\",\"codigo_cliente\":\"C1734\",\"cantidad\":\"2\"}', '2021-06-12 10:45:00'),
(22, 2, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"5\"}', '{\"id_producto\":\"8\",\"codigo_cliente\":\"C1734\",\"cantidad\":\"3\"}', '2021-06-12 10:45:00'),
(23, 3, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:47:00'),
(24, 9, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:48:00'),
(25, 4, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:48:00'),
(26, 10, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:48:00'),
(27, 5, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:49:00'),
(28, 11, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:49:00'),
(29, 6, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:49:00'),
(30, 7, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-12 10:50:00'),
(31, 3, '1', 'PEDIDOS', NULL, 'Obtener', '{\"listaPedidos\":[{\"id\":1,\"id_mesa\":1,\"id_estado\":2,\"estado_descripcion\":\"En preparacion\",\"id_producto\":2,\"producto_descripcion\":\"Milanesa\",\"id_sector\":1,\"sector_descripcion\":\"Cocina\",\"id_empleado\":3,\"empleado_descripcion\":\"Cocinero\",\"minutos_preparacion\":10,\"fecha_hora_inicio\":\"2021-06-07 21:10\",\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1478\",\"cantidad\":2,\"baja_logica\":0},{\"id\":2,\"id_mesa\":1,\"id_estado\":4,\"estado_descripcion\":\"Cancelado\",\"id_producto\":2,\"producto_descripcion\":\"Milanesa\",\"id_sector\":1,\"sector_descripcion\":\"Cocina\",\"id_empleado\":null,\"empleado_descripcion\":null,\"minutos_preparacion\":null,\"fecha_hora_inicio\":null,\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1478\",\"cantidad\":2,\"baja_logica\":0},{\"id\":4,\"id_mesa\":3,\"id_estado\":1,\"estado_descripcion\":\"Pendientes\",\"id_producto\":7,\"producto_descripcion\":\"napolitana\",\"id_sector\":1,\"sector_descripcion\":\"Cocina\",\"id_empleado\":null,\"empleado_descripcion\":null,\"minutos_preparacion\":null,\"fecha_hora_inicio\":null,\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1734\",\"cantidad\":2,\"baja_logica\":0}]}', 'null', '2021-06-12 11:13:00'),
(32, 3, '1', 'PEDIDOS', NULL, 'Obtener', '{\"listaPedidos\":[{\"id\":1,\"id_mesa\":1,\"id_estado\":2,\"estado_descripcion\":\"En preparacion\",\"id_producto\":2,\"producto_descripcion\":\"Milanesa\",\"id_sector\":1,\"sector_descripcion\":\"Cocina\",\"id_empleado\":3,\"empleado_descripcion\":\"Cocinero\",\"minutos_preparacion\":10,\"fecha_hora_inicio\":\"2021-06-07 21:10\",\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1478\",\"cantidad\":2,\"baja_logica\":0},{\"id\":4,\"id_mesa\":3,\"id_estado\":1,\"estado_descripcion\":\"Pendientes\",\"id_producto\":7,\"producto_descripcion\":\"napolitana\",\"id_sector\":1,\"sector_descripcion\":\"Cocina\",\"id_empleado\":null,\"empleado_descripcion\":null,\"minutos_preparacion\":null,\"fecha_hora_inicio\":null,\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1734\",\"cantidad\":2,\"baja_logica\":0}]}', 'null', '2021-06-12 11:16:00'),
(33, 9, '1', 'PEDIDOS', '4', 'Modificar uno', '{\"mensaje\":\"Pedido tomado con exito.\"}', '{\"id\":\"4\",\"estado\":\"2\",\"minutos_preparacion\":\"1\"}', '2021-06-12 11:19:00'),
(34, 2, '5', 'PEDIDOS', '5', 'Modificar uno', '{\"mensaje\":\"El pedido fue cancelado por el mozo id: 2\"}', '{\"id\":\"5\",\"estado\":\"4\"}', '2021-06-12 11:24:00'),
(35, 9, '1', 'PEDIDOS', '4', 'Modificar uno', '{\"mensaje\":\"Pedido terminado con exito.\"}', '{\"id\":\"4\",\"estado\":\"3\"}', '2021-06-12 11:26:00'),
(36, 7, '4', 'PEDIDOS', '3', 'Modificar uno', '{\"mensaje\":\"Pedido tomado con exito.\"}', '{\"id\":\"3\",\"estado\":\"2\",\"minutos_preparacion\":\"5\"}', '2021-06-12 11:27:00'),
(37, 7, '4', 'PEDIDOS', '3', 'Modificar uno', '{\"mensaje\":\"Pedido terminado con exito.\"}', '{\"id\":\"3\",\"estado\":\"3\",\"minutos_preparacion\":\"5\"}', '2021-06-12 11:27:00'),
(38, 2, '5', 'MESAS', 'C1734', 'Modificar uno', '{\"mensaje\":\"Mesa modificado con exito.\"}', '{\"codigo_cliente\":\"C1734\",\"id_mesa\":\"3\",\"codigo_mesa_estado\":\"2\"}', '2021-06-12 11:29:00'),
(39, 2, '5', 'MESAS', 'C1734', 'Modificar uno', '{\"mensaje\":\"Mesa modificado con exito.\"}', '{\"codigo_cliente\":\"C1734\",\"id_mesa\":\"3\",\"codigo_mesa_estado\":\"3\"}', '2021-06-12 11:30:00'),
(40, 2, '5', 'ERROR', NULL, 'Error', '{\"error\":\"No coinciden los estados a cambiar las mesas con los permisos.\"}', '{\"codigo_cliente\":\"C1734\",\"id_mesa\":\"3\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:30:00'),
(41, 1, '6', 'MESAS', 'C1734', 'Modificar uno', '{\"mensaje\":\"Mesa modificado con exito.\"}', '{\"codigo_cliente\":\"C1734\",\"id_mesa\":\"3\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:31:00'),
(42, 2, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"6\"}', '{\"id_producto\":\"6\",\"codigo_cliente\":\"C1310\",\"cantidad\":\"10\"}', '2021-06-12 11:35:00'),
(43, 6, '4', 'PEDIDOS', NULL, 'Obtener', '{\"listaPedidos\":[{\"id\":6,\"id_mesa\":2,\"id_estado\":1,\"estado_descripcion\":\"Pendientes\",\"id_producto\":6,\"producto_descripcion\":\"Miller\",\"id_sector\":4,\"sector_descripcion\":\"Barra cervezas\",\"id_empleado\":null,\"empleado_descripcion\":null,\"minutos_preparacion\":null,\"fecha_hora_inicio\":null,\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1310\",\"cantidad\":10,\"baja_logica\":0}]}', 'null', '2021-06-12 11:37:00'),
(44, 6, '4', 'PEDIDOS', '6', 'Modificar uno', '{\"mensaje\":\"Pedido tomado con exito.\"}', '{\"id\":\"6\",\"estado\":\"2\",\"minutos_preparacion\":\"5\"}', '2021-06-12 11:37:00'),
(45, 6, '4', 'PEDIDOS', '6', 'Modificar uno', '{\"mensaje\":\"Pedido terminado con exito.\"}', '{\"id\":\"6\",\"estado\":\"3\"}', '2021-06-12 11:38:00'),
(46, 8, '5', 'MESAS', 'C1310', 'Modificar uno', '{\"mensaje\":\"Mesa modificado con exito.\"}', '{\"codigo_cliente\":\"C1310\",\"id_mesa\":\"2\",\"codigo_mesa_estado\":\"2\"}', '2021-06-12 11:39:00'),
(47, 8, '5', 'MESAS', 'C1310', 'Modificar uno', '{\"Mensaje\":\"Mesa cliente pagando\"}', '{\"codigo_cliente\":\"C1310\",\"id_mesa\":\"2\",\"codigo_mesa_estado\":\"3\"}', '2021-06-12 11:45:00'),
(48, 1, '6', 'MESAS', 'C1310', 'Modificar uno', '{\"Mensaje\":\"Mesa cerrada\"}', '{\"codigo_cliente\":\"C1310\",\"id_mesa\":\"2\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:45:00'),
(49, 1, '6', 'ERROR', NULL, 'Error', '{\"error\":\"No coinciden los estados a cambiar las mesas.\"}', '{\"codigo_cliente\":\"C1310\",\"id_mesa\":\"2\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:49:00'),
(50, 1, '6', 'MESAS', 'C1310', 'Modificar uno', '{\"Mensaje\":\"Mesa cerrada\"}', '{\"codigo_cliente\":\"C1310\",\"id_mesa\":\"2\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:49:00'),
(51, 1, '6', 'MESAS', 'C1734', 'Modificar uno', '{\"Mensaje\":\"Mesa cerrada\"}', '{\"codigo_cliente\":\"C1734\",\"id_mesa\":\"3\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:50:00'),
(52, 8, '5', 'PEDIDOS', '0', 'Cargar uno', '{\"id_pedido_creado\":\"7\"}', '{\"id_producto\":\"3\",\"codigo_cliente\":\"C1209\",\"cantidad\":\"5\"}', '2021-06-12 11:52:00'),
(53, 10, '2', 'PEDIDOS', NULL, 'Obtener', '{\"listaPedidos\":[{\"id\":7,\"id_mesa\":1,\"id_estado\":1,\"estado_descripcion\":\"Pendientes\",\"id_producto\":3,\"producto_descripcion\":\"Arroz con leche\",\"id_sector\":2,\"sector_descripcion\":\"Candy bar\",\"id_empleado\":null,\"empleado_descripcion\":null,\"minutos_preparacion\":null,\"fecha_hora_inicio\":null,\"fecha_hora_fin\":null,\"codigo_cliente\":\"C1209\",\"cantidad\":5,\"baja_logica\":0}]}', 'null', '2021-06-12 11:53:00'),
(54, 6, '4', 'ERROR', NULL, 'Error', '{\"error\":\"No existe el pedido que quiere actualizar o no es de su sector.\"}', '{\"id\":\"7\",\"estado\":\"2\",\"minutos_preparacion\":\"7\"}', '2021-06-12 11:55:00'),
(55, 10, '2', 'PEDIDOS', '7', 'Modificar uno', '{\"mensaje\":\"Pedido tomado con exito.\"}', '{\"id\":\"7\",\"estado\":\"2\",\"minutos_preparacion\":\"7\"}', '2021-06-12 11:56:00'),
(56, 10, '2', 'PEDIDOS', '7', 'Modificar uno', '{\"mensaje\":\"Pedido terminado con exito.\"}', '{\"id\":\"7\",\"estado\":\"3\",\"minutos_preparacion\":\"7\"}', '2021-06-12 11:56:00'),
(57, 8, '5', 'MESAS', 'C1209', 'Modificar uno', '{\"Mensaje\":\"Mesa cliente comiendo\"}', '{\"codigo_cliente\":\"C1209\",\"id_mesa\":\"1\",\"codigo_mesa_estado\":\"2\"}', '2021-06-12 11:57:00'),
(58, 8, '5', 'MESAS', 'C1209', 'Modificar uno', '{\"Mensaje\":\"Mesa cliente pagando\"}', '{\"codigo_cliente\":\"C1209\",\"id_mesa\":\"1\",\"codigo_mesa_estado\":\"3\"}', '2021-06-12 11:57:00'),
(59, 1, '6', 'MESAS', 'C1209', 'Modificar uno', '{\"Mensaje\":\"Mesa cerrada\"}', '{\"codigo_cliente\":\"C1209\",\"id_mesa\":\"1\",\"codigo_mesa_estado\":\"4\"}', '2021-06-12 11:58:00'),
(60, 0, '0', 'ENCUESTAS', NULL, NULL, '{\"mensaje\":\"Encuesta cargada.\"}', '{\"puntuacion_descripcion\":\"Excelente medio\",\"puntuacion_mesa\":\"6\",\"puntuacion_restaurante\":\"10\",\"puntuacion_mozo\":\"6\",\"puntuacion_cocinero\":\"8\",\"codigo_cliente\":\"C1310\"}', '2021-06-12 12:11:00'),
(61, 0, '0', 'ENCUESTAS', NULL, NULL, '{\"mensaje\":\"Encuesta cargada.\"}', '{\"puntuacion_descripcion\":\"Excelente medio y ahi\",\"puntuacion_mesa\":\"7\",\"puntuacion_restaurante\":\"8\",\"puntuacion_mozo\":\"9\",\"puntuacion_cocinero\":\"10\",\"codigo_cliente\":\"C1209\"}', '2021-06-12 12:12:00'),
(62, 1, '6', 'MESAS', NULL, 'Borrar', '{\"mensaje\":\"Mesa borrado con exito\"}', '{\"id\":\"1\"}', '2021-06-12 12:14:00'),
(63, 1, '6', 'MESAS', NULL, 'Borrar', '{\"mensaje\":\"Mesa borrado con exito\"}', '{\"id\":\"2\"}', '2021-06-12 12:15:00'),
(64, 1, '6', 'MESAS', NULL, 'Borrar', '{\"mensaje\":\"Mesa borrado con exito\"}', '{\"id\":\"3\"}', '2021-06-12 12:15:00'),
(65, 1, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-13 12:50:00'),
(66, 1, '6', 'PEDIDOS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:45:00'),
(67, 1, '6', 'PEDIDOS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:48:00'),
(68, 1, '6', 'PEDIDOS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:49:00'),
(69, 1, '6', 'PEDIDOS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:51:00'),
(70, 1, '6', 'MESAS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:51:00'),
(71, 1, '6', 'MESAS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:55:00'),
(72, 1, '6', 'MESAS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:57:00'),
(73, 1, '6', 'MESAS', NULL, 'Obtener', '{\"mensaje\":\"Descargado\"}', 'null', '2021-06-13 13:58:00'),
(74, 1, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-13 15:52:00'),
(75, 1, '6', 'PRODUCTOS', NULL, 'Obtener', '{\"mensaje\":\"Archivo descargado en formato csv\"}', 'null', '2021-06-13 15:53:00'),
(76, 1, '6', 'PRODUCTOS', NULL, 'Obtener', '', 'null', '2021-06-13 16:09:00'),
(77, 1, '0', 'SISTEMA', NULL, 'Ingreso sistema', NULL, NULL, '2021-06-15 16:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `Mesas`
--

CREATE TABLE `Mesas` (
  `id` int(11) NOT NULL,
  `codigo_cliente` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_cliente` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_consumicion` float DEFAULT NULL,
  `id_mozo` int(11) DEFAULT NULL,
  `codigo_estado_mesa` int(11) DEFAULT NULL,
  `fecha_hora_inicio` datetime DEFAULT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `libre` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Mesas`
--

INSERT INTO `Mesas` (`id`, `codigo_cliente`, `nombre_cliente`, `total_consumicion`, `id_mozo`, `codigo_estado_mesa`, `fecha_hora_inicio`, `fecha_hora_fin`, `libre`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Pedidos`
--

CREATE TABLE `Pedidos` (
  `id` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `codigo_cliente` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `minutos_preparacion` int(11) DEFAULT NULL,
  `fecha_hora_inicio` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_hora_fin` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `baja_logica` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Pedidos`
--

INSERT INTO `Pedidos` (`id`, `id_mesa`, `codigo_cliente`, `id_estado`, `id_sector`, `id_empleado`, `id_producto`, `cantidad`, `minutos_preparacion`, `fecha_hora_inicio`, `fecha_hora_fin`, `baja_logica`) VALUES
(1, 1, 'C1478', 2, 1, 3, 2, 2, 10, '2021-06-07 21:10', NULL, 0),
(2, 1, 'C1478', 4, 1, 9, 2, 2, NULL, NULL, NULL, 0),
(3, 3, 'C1734', 3, 4, 7, 6, 1, 5, '2021-06-12 11:27', '2021-06-12 11:27', 1),
(4, 3, 'C1734', 3, 1, 9, 7, 2, 1, '2021-06-12 11:19', '2021-06-12 11:26', 1),
(5, 3, 'C1734', 4, 2, 2, 8, 3, NULL, NULL, '2021-06-12 11:24', 1),
(6, 2, 'C1310', 3, 4, 6, 6, 10, 5, '2021-06-12 11:37', '2021-06-12 11:38', 1),
(7, 1, 'C1209', 3, 2, 10, 3, 5, 7, '2021-06-12 11:56', '2021-06-12 11:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Productos`
--

CREATE TABLE `Productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `precio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Productos`
--

INSERT INTO `Productos` (`id`, `nombre`, `tipo`, `precio`) VALUES
(2, 'Milanesa', '1', 1000),
(3, 'Arroz con leche', '2', 1000),
(4, 'Daikiri', '3', 102.2),
(6, 'Miller', '4', 200),
(7, 'napolitana', '1\r\n', 573.3),
(8, 'helado', '2\r\n', 1000),
(9, 'Daikiri', '3\r\n', 1000),
(10, 'Cerveza', '4', 1000),
(11, 'Stella', '4', 250),
(12, 'Brahma', '4', 170);

-- --------------------------------------------------------

--
-- Table structure for table `Sectores_Restaurant`
--

CREATE TABLE `Sectores_Restaurant` (
  `codigo` int(11) NOT NULL,
  `descripcion` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Sectores_Restaurant`
--

INSERT INTO `Sectores_Restaurant` (`codigo`, `descripcion`) VALUES
(1, 'Cocina'),
(2, 'Candy bar'),
(3, 'Barra tragos'),
(4, 'Barra cervezas'),
(5, 'Atencion Cliente'),
(6, 'Administracion');

-- --------------------------------------------------------

--
-- Table structure for table `Tipos_Usuarios`
--

CREATE TABLE `Tipos_Usuarios` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Tipos_Usuarios`
--

INSERT INTO `Tipos_Usuarios` (`id`, `descripcion`) VALUES
(1, 'Socio'),
(2, 'Empleado');

-- --------------------------------------------------------

--
-- Table structure for table `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `apellido` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `tipo_usuario` int(11) NOT NULL,
  `id_sector` int(11) NOT NULL,
  `baja_logica` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Usuarios`
--

INSERT INTO `Usuarios` (`id`, `nombre`, `apellido`, `clave`, `tipo_usuario`, `id_sector`, `baja_logica`) VALUES
(1, 'Socio Uno', 'Socio Uno', '$2y$10$.PDqMdNAMcet1osO.V3nMOEdty3okAK32SMOFRwvNV9dIepDEaf/K', 1, 6, 0),
(2, 'Mozo', 'Mozo', '$2y$10$vB.Bz2KLqEc5lQ252Xp5gucfcUQBA3ARgvZXxWda82HDGPrtxojCW', 2, 5, 0),
(3, 'Cocinero', 'Cocinero', '$2y$10$tN9pqm1RFVrBu8U60Lz.CukxutdHe.3XEWsqAfUGHvbEB9wQgT5BG', 2, 1, 0),
(4, 'Pastelero', 'Pastelero', '$2y$10$lKTm3bBWIPlizitbwV3GfuVbz.Cq/hd1LTJTOfWStBLVYdLg40Li.', 2, 2, 0),
(5, 'Bartender Tragos', 'Bartender Tragos', '$2y$10$XYDM4AuCa90icQhtB0EI1OTu6iKeFKsd/gTgEdDmdht7Y1x8.I2cG', 2, 3, 0),
(6, 'Bartender Birra', 'Bartender Birra', '$2y$10$BRpmxqwzePRvkqn4CboIwubphF0fiF9lJxMAfBvTzrhA0IDLQJ5la', 2, 4, 0),
(7, 'Bartender Birra Dos', 'Bartender Birra Dos', '$2y$10$f.AqqKFRODP0hRo8uQPrFO8UEgZnXSzF7YMIJrNgL2IFfkfOSP/ym', 2, 4, 0),
(8, 'Mozo dos', 'Mozo dos', '$2y$10$nhcR.TztzK0FTN9tE5duAeEqgmXd8S6VquyGEa4pgSl1JKWVzhmaC', 2, 5, 0),
(9, 'Cocinero dos', 'Cocinero dos', '$2y$10$KGFbe59Zw8sVXJvbNKVu6.cCJ2Fo8ReMxshZq7XqJml00e8Ip1kby', 2, 1, 0),
(10, 'Pastelero dos', 'Pastelero dos', '$2y$10$xWCNUrHL1XZXMdqLFMOZCeo/SOYhrv4yc6HA/7GW7PPdQHqF9Px0S', 2, 2, 0),
(11, 'Trados dos', 'Tragos dos', '$2y$10$BMFDleCYDvAbkH3HhQ6QBeINsCZZPACYWWhl6XHCVmPuYwwA658LG', 2, 3, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Encuesta`
--
ALTER TABLE `Encuesta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Estados_Mesas`
--
ALTER TABLE `Estados_Mesas`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `Estados_Pedidos`
--
ALTER TABLE `Estados_Pedidos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `e_crypto`
--
ALTER TABLE `e_crypto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `e_hortaliza`
--
ALTER TABLE `e_hortaliza`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `e_usuarios`
--
ALTER TABLE `e_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Logs`
--
ALTER TABLE `Logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Mesas`
--
ALTER TABLE `Mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Pedidos`
--
ALTER TABLE `Pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Productos`
--
ALTER TABLE `Productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Tipos_Usuarios`
--
ALTER TABLE `Tipos_Usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Encuesta`
--
ALTER TABLE `Encuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Estados_Mesas`
--
ALTER TABLE `Estados_Mesas`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Estados_Pedidos`
--
ALTER TABLE `Estados_Pedidos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `e_crypto`
--
ALTER TABLE `e_crypto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `e_hortaliza`
--
ALTER TABLE `e_hortaliza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `e_usuarios`
--
ALTER TABLE `e_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Logs`
--
ALTER TABLE `Logs`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `Mesas`
--
ALTER TABLE `Mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Pedidos`
--
ALTER TABLE `Pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Productos`
--
ALTER TABLE `Productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `Tipos_Usuarios`
--
ALTER TABLE `Tipos_Usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
