-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para saas_odontologia
CREATE DATABASE IF NOT EXISTS `saas_odontologia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `saas_odontologia`;

-- Volcando estructura para tabla saas_odontologia.actividades
CREATE TABLE IF NOT EXISTS `actividades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modelo_id` bigint unsigned DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actividades_user_id_foreign` (`user_id`),
  KEY `actividades_accion_modulo_index` (`accion`,`modulo`),
  KEY `actividades_created_at_index` (`created_at`),
  CONSTRAINT `actividades_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.actividades: ~2 rows (aproximadamente)
DELETE FROM `actividades`;
INSERT INTO `actividades` (`id`, `user_id`, `accion`, `modulo`, `modelo_id`, `descripcion`, `ip`, `created_at`, `updated_at`) VALUES
	(1, NULL, 'actualizado', 'User', 9, 'Dr. Pedro Salazar', '127.0.0.1', '2026-08-10 17:12:56', '2026-08-10 17:12:56'),
	(2, NULL, 'actualizado', 'User', 10, 'Dra. Carmen Vega', '127.0.0.1', '2026-08-10 17:12:56', '2026-08-10 17:12:56'),
	(3, NULL, 'actualizado', 'User', 11, 'Dra. Lucia Mendez', '127.0.0.1', '2026-08-10 17:12:56', '2026-08-10 17:12:56'),
	(4, NULL, 'actualizado', 'User', 12, 'Dr. Andres Rojas', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(5, NULL, 'actualizado', 'User', 13, 'Jorge Receptor', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(6, NULL, 'creado', 'Paciente', 1, 'Naiara Vargas', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(7, NULL, 'creado', 'Paciente', 2, 'Nahia Bustos', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(8, NULL, 'creado', 'Paciente', 3, 'Óscar Dueñas', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(9, NULL, 'creado', 'Paciente', 4, 'Unai Espino', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(10, NULL, 'creado', 'Paciente', 5, 'Leo Lucio', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(11, NULL, 'creado', 'Paciente', 6, 'Nayara Ramírez', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(12, NULL, 'creado', 'Paciente', 7, 'Elena Castellano', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(13, NULL, 'creado', 'Paciente', 8, 'Lara Delarosa', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(14, NULL, 'creado', 'Paciente', 9, 'José Manuel Castaño', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(15, NULL, 'creado', 'Paciente', 10, 'Andrés Vidal', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(16, NULL, 'creado', 'Paciente', 11, 'Marina Valdivia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(17, NULL, 'creado', 'Paciente', 12, 'Andrés Trejo', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(18, NULL, 'creado', 'Paciente', 13, 'Noa Alba', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(19, NULL, 'creado', 'Paciente', 14, 'Emilia Gallegos', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(20, NULL, 'creado', 'Paciente', 15, 'Pol Álvarez', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(21, NULL, 'creado', 'Paciente', 16, 'Sergio Santamaría', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(22, NULL, 'creado', 'Paciente', 17, 'Raúl Villa', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(23, NULL, 'creado', 'Paciente', 18, 'Omar Herrera', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(24, NULL, 'creado', 'Paciente', 19, 'Abril Marcos', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(25, NULL, 'creado', 'Paciente', 20, 'Beatriz Aponte', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(26, NULL, 'creado', 'Paciente', 21, 'Marcos Juárez', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(27, NULL, 'creado', 'Paciente', 22, 'Celia Esquivel', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(28, NULL, 'creado', 'Paciente', 23, 'Elsa Luevano', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(29, NULL, 'creado', 'Paciente', 24, 'Iván Pardo', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(30, NULL, 'creado', 'Paciente', 25, 'Olivia Navarro', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(31, NULL, 'creado', 'Paciente', 26, 'Alicia Alonzo', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(32, NULL, 'creado', 'Paciente', 27, 'Martín Montalvo', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(33, NULL, 'creado', 'Paciente', 28, 'Rayan Orosco', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(34, NULL, 'creado', 'Paciente', 29, 'Carolina Véliz', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(35, NULL, 'creado', 'Paciente', 30, 'Gabriel Aguirre', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(36, NULL, 'creado', 'Cita', 1, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(37, NULL, 'creado', 'Cita', 2, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(38, NULL, 'creado', 'Cita', 3, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(39, NULL, 'creado', 'Cita', 4, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(40, NULL, 'creado', 'Cita', 5, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(41, NULL, 'creado', 'Cita', 6, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(42, NULL, 'creado', 'Cita', 7, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(43, NULL, 'creado', 'Cita', 8, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(44, NULL, 'creado', 'Cita', 9, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(45, NULL, 'creado', 'Cita', 10, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(46, NULL, 'creado', 'Cita', 11, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(47, NULL, 'creado', 'Cita', 12, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(48, NULL, 'creado', 'Cita', 13, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(49, NULL, 'creado', 'Cita', 14, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(50, NULL, 'creado', 'Cita', 15, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(51, NULL, 'creado', 'Cita', 16, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(52, NULL, 'creado', 'Cita', 17, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(53, NULL, 'creado', 'Cita', 18, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(54, NULL, 'creado', 'Cita', 19, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(55, NULL, 'creado', 'Cita', 20, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(56, NULL, 'creado', 'Cita', 21, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(57, NULL, 'creado', 'Cita', 22, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(58, NULL, 'creado', 'Cita', 23, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(59, NULL, 'creado', 'Cita', 24, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(60, NULL, 'creado', 'Cita', 25, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(61, NULL, 'creado', 'Cita', 26, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(62, NULL, 'creado', 'Cita', 27, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(63, NULL, 'creado', 'Cita', 28, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(64, NULL, 'creado', 'Cita', 29, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(65, NULL, 'creado', 'Cita', 30, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(66, NULL, 'creado', 'Cita', 31, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(67, NULL, 'creado', 'Cita', 32, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(68, NULL, 'creado', 'Cita', 33, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(69, NULL, 'creado', 'Cita', 34, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(70, NULL, 'creado', 'Cita', 35, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(71, NULL, 'creado', 'Cita', 36, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(72, NULL, 'creado', 'Cita', 37, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(73, NULL, 'creado', 'Cita', 38, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(74, NULL, 'creado', 'Cita', 39, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(75, NULL, 'creado', 'Cita', 40, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(76, NULL, 'creado', 'Cita', 41, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(77, NULL, 'creado', 'Cita', 42, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(78, NULL, 'creado', 'Cita', 43, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(79, NULL, 'creado', 'Cita', 44, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(80, NULL, 'creado', 'Cita', 45, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(81, NULL, 'creado', 'Cita', 46, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(82, NULL, 'creado', 'Cita', 47, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(83, NULL, 'creado', 'Cita', 48, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(84, NULL, 'creado', 'Cita', 49, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(85, NULL, 'creado', 'Cita', 50, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(86, NULL, 'creado', 'Cita', 51, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(87, NULL, 'creado', 'Cita', 52, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(88, NULL, 'creado', 'Cita', 53, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(89, NULL, 'creado', 'Cita', 54, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(90, NULL, 'creado', 'Cita', 55, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(91, NULL, 'creado', 'Cita', 56, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(92, NULL, 'creado', 'Cita', 57, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(93, NULL, 'creado', 'Cita', 58, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(94, NULL, 'creado', 'Cita', 59, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(95, NULL, 'creado', 'Cita', 60, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(96, NULL, 'creado', 'Cita', 61, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(97, NULL, 'creado', 'Cita', 62, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(98, NULL, 'creado', 'Cita', 63, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(99, NULL, 'creado', 'Cita', 64, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(100, NULL, 'creado', 'Cita', 65, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(101, NULL, 'creado', 'Cita', 66, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(102, NULL, 'creado', 'Cita', 67, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(103, NULL, 'creado', 'Cita', 68, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(104, NULL, 'creado', 'Cita', 69, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(105, NULL, 'creado', 'Cita', 70, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(106, NULL, 'creado', 'Cita', 71, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(107, NULL, 'creado', 'Cita', 72, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(108, NULL, 'creado', 'Cita', 73, 'Limpieza dental', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(109, NULL, 'creado', 'Cita', 74, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(110, NULL, 'creado', 'Cita', 75, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(111, NULL, 'creado', 'Cita', 76, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(112, NULL, 'creado', 'Cita', 77, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(113, NULL, 'creado', 'Cita', 78, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(114, NULL, 'creado', 'Cita', 79, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(115, NULL, 'creado', 'Cita', 80, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(116, NULL, 'creado', 'Cita', 81, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(117, NULL, 'creado', 'Cita', 82, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(118, NULL, 'creado', 'Cita', 83, 'Control de ortodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(119, NULL, 'creado', 'Cita', 84, 'Consulta general', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(120, NULL, 'creado', 'Cita', 85, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(121, NULL, 'creado', 'Cita', 86, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(122, NULL, 'creado', 'Cita', 87, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(123, NULL, 'creado', 'Cita', 88, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(124, NULL, 'creado', 'Cita', 89, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(125, NULL, 'creado', 'Cita', 90, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(126, NULL, 'creado', 'Cita', 91, 'Extraccion', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(127, NULL, 'creado', 'Cita', 92, 'Revision', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(128, NULL, 'creado', 'Cita', 93, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(129, NULL, 'creado', 'Cita', 94, 'Control post-operatorio', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(130, NULL, 'creado', 'Cita', 95, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(131, NULL, 'creado', 'Cita', 96, 'Urgencia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(132, NULL, 'creado', 'Cita', 97, 'Colocacion de corona', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(133, NULL, 'creado', 'Cita', 98, 'Blanqueamiento', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(134, NULL, 'creado', 'Cita', 99, 'Endodoncia', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(135, NULL, 'creado', 'Presupuesto', 1, 'PRE-000001', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(136, NULL, 'creado', 'Pago', 1, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(137, NULL, 'creado', 'Cuota', 1, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(138, NULL, 'creado', 'Cuota', 2, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(139, NULL, 'creado', 'Presupuesto', 2, 'PRE-000002', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(140, NULL, 'creado', 'Pago', 2, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(141, NULL, 'creado', 'Cuota', 3, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(142, NULL, 'creado', 'Cuota', 4, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(143, NULL, 'creado', 'Presupuesto', 3, 'PRE-000003', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(144, NULL, 'creado', 'Presupuesto', 4, 'PRE-000004', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(145, NULL, 'creado', 'Pago', 3, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(146, NULL, 'creado', 'Presupuesto', 5, 'PRE-000005', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(147, NULL, 'creado', 'Pago', 4, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(148, NULL, 'creado', 'Presupuesto', 6, 'PRE-000006', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(149, NULL, 'creado', 'Pago', 5, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(150, NULL, 'creado', 'Presupuesto', 7, 'PRE-000007', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(151, NULL, 'creado', 'Pago', 6, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(152, NULL, 'creado', 'Presupuesto', 8, 'PRE-000008', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(153, NULL, 'creado', 'Pago', 7, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(154, NULL, 'creado', 'Presupuesto', 9, 'PRE-000009', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(155, NULL, 'creado', 'Pago', 8, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(156, NULL, 'creado', 'Cuota', 5, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(157, NULL, 'creado', 'Cuota', 6, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(158, NULL, 'creado', 'Cuota', 7, NULL, '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(159, NULL, 'creado', 'Presupuesto', 10, 'PRE-000010', '127.0.0.1', '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(160, NULL, 'creado', 'Pago', 9, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(161, NULL, 'creado', 'Presupuesto', 11, 'PRE-000011', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(162, NULL, 'creado', 'Pago', 10, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(163, NULL, 'creado', 'Presupuesto', 12, 'PRE-000012', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(164, NULL, 'creado', 'Pago', 11, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(165, NULL, 'creado', 'Cuota', 8, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(166, NULL, 'creado', 'Cuota', 9, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(167, NULL, 'creado', 'Pago', 12, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(168, NULL, 'creado', 'Pago', 13, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(169, NULL, 'creado', 'Pago', 14, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(170, NULL, 'creado', 'Gasto', 81, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(171, NULL, 'creado', 'Gasto', 82, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(172, NULL, 'creado', 'Gasto', 83, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(173, NULL, 'creado', 'Gasto', 84, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(174, NULL, 'creado', 'Gasto', 85, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(175, NULL, 'creado', 'Gasto', 86, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(176, NULL, 'creado', 'Gasto', 87, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(177, NULL, 'creado', 'Gasto', 88, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(178, NULL, 'creado', 'Gasto', 89, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(179, NULL, 'creado', 'Gasto', 90, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(180, NULL, 'creado', 'Insumo', 1, 'Anestesia lidocaina 2%', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(181, NULL, 'creado', 'Insumo', 2, 'Resina compuesta A2', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(182, NULL, 'creado', 'Insumo', 3, 'Limas endodoncia K', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(183, NULL, 'creado', 'Insumo', 4, 'Guantes de nitrilo', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(184, NULL, 'creado', 'Insumo', 5, 'Barbijos triple capa', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(185, NULL, 'creado', 'Insumo', 6, 'Algodon dental', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(186, NULL, 'creado', 'Insumo', 7, 'Brackets metalicos', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(187, NULL, 'creado', 'Insumo', 8, 'Hilo de sutura', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(188, NULL, 'creado', 'Insumo', 9, 'Fluor gel', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(189, NULL, 'creado', 'Insumo', 10, 'Fresas de diamante', '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(190, NULL, 'creado', 'Consentimiento', 1, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(191, NULL, 'creado', 'Consentimiento', 2, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(192, NULL, 'creado', 'Consentimiento', 3, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(193, NULL, 'creado', 'Consentimiento', 4, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(194, NULL, 'creado', 'Consentimiento', 5, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(195, NULL, 'creado', 'Consentimiento', 6, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(196, NULL, 'creado', 'Consentimiento', 7, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(197, NULL, 'creado', 'Consentimiento', 8, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(198, NULL, 'creado', 'Consentimiento', 9, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(199, NULL, 'creado', 'Consentimiento', 10, NULL, '127.0.0.1', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(200, 9, 'acceso', 'Autenticacion', NULL, 'Inicio de sesion en el sistema', '127.0.0.1', '2026-08-06 15:36:00', '2026-08-04 05:00:00'),
	(201, 9, 'creado', 'Paciente', NULL, 'Registro de nuevo paciente', '127.0.0.1', '2026-07-25 20:12:00', '2026-07-15 05:00:00'),
	(202, 12, 'creado', 'Cita', NULL, 'Agendo una nueva cita', '127.0.0.1', '2026-06-14 20:43:00', '2026-06-27 05:00:00'),
	(203, 10, 'actualizado', 'Cita', NULL, 'Reprogramo una cita', '127.0.0.1', '2026-05-13 19:48:00', '2026-05-10 05:00:00'),
	(204, 4, 'creado', 'Presupuesto', NULL, 'Genero un presupuesto', '127.0.0.1', '2026-04-21 13:21:00', '2026-04-05 05:00:00'),
	(205, 9, 'actualizado', 'Presupuesto', NULL, 'Aprobo un presupuesto', '127.0.0.1', '2026-03-01 22:31:00', '2026-03-23 05:00:00'),
	(206, 11, 'creado', 'Pago', NULL, 'Registro un pago en caja', '127.0.0.1', '2026-08-03 17:24:00', '2026-08-10 05:00:00'),
	(207, 5, 'creado', 'Gasto', NULL, 'Registro un gasto operativo', '127.0.0.1', '2026-07-07 23:40:00', '2026-07-30 05:00:00'),
	(208, 11, 'actualizado', 'Insumo', NULL, 'Actualizo el stock de inventario', '127.0.0.1', '2026-06-06 18:28:00', '2026-06-01 05:00:00'),
	(209, 7, 'creado', 'Evolucion', NULL, 'Registro una evolucion clinica', '127.0.0.1', '2026-05-23 16:57:00', '2026-05-03 05:00:00'),
	(210, 3, 'creado', 'Consentimiento', NULL, 'Firmo un consentimiento informado', '127.0.0.1', '2026-04-13 21:14:00', '2026-04-08 05:00:00'),
	(211, 6, 'salida', 'Autenticacion', NULL, 'Cierre de sesion', '127.0.0.1', '2026-03-26 16:04:00', '2026-03-11 05:00:00');

-- Volcando estructura para tabla saas_odontologia.archivos
CREATE TABLE IF NOT EXISTS `archivos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` bigint unsigned NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tamano` bigint unsigned DEFAULT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `archivos_paciente_id_foreign` (`paciente_id`),
  CONSTRAINT `archivos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.archivos: ~0 rows (aproximadamente)
DELETE FROM `archivos`;

-- Volcando estructura para tabla saas_odontologia.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.cache: ~0 rows (aproximadamente)
DELETE FROM `cache`;

-- Volcando estructura para tabla saas_odontologia.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.cache_locks: ~0 rows (aproximadamente)
DELETE FROM `cache_locks`;

-- Volcando estructura para tabla saas_odontologia.citas
CREATE TABLE IF NOT EXISTS `citas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paciente_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `silla` tinyint unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','confirmada','completada','cancelada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `notas` text COLLATE utf8mb4_unicode_ci,
  `recordatorio_enviado_en` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `citas_token_unique` (`token`),
  KEY `citas_paciente_id_foreign` (`paciente_id`),
  KEY `citas_doctor_id_foreign` (`doctor_id`),
  KEY `citas_fecha_estado_index` (`fecha`,`estado`),
  KEY `citas_fecha_hora_index` (`fecha`,`hora`),
  CONSTRAINT `citas_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `citas_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.citas: ~99 rows (aproximadamente)
DELETE FROM `citas`;
INSERT INTO `citas` (`id`, `token`, `paciente_id`, `doctor_id`, `silla`, `fecha`, `hora`, `motivo`, `estado`, `notas`, `recordatorio_enviado_en`, `created_at`, `updated_at`) VALUES
	(1, 'WLhogCqoYzCFRLC8438tFkje6X5JbCnWA8BeaGGy', 29, 2, NULL, '2026-03-11', '15:00:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(2, 'ym9QBVBpXQlPMU8TBQvTLmQUck9JmTsYsBThctlj', 15, 7, NULL, '2026-03-04', '11:00:00', 'Endodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(3, 'KbaC6yq7irn8lbRTfNUBNJ83kgQK616Cri6V1SXx', 28, 2, NULL, '2026-03-15', '17:30:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(4, 'HO7eY8uHAnVlmkLXlwau8vffUNDEx8vh8CU5Y5GR', 24, 7, NULL, '2026-03-11', '08:00:00', 'Extraccion', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(5, 'I9kmxkceEAYErYuy5K4Z2RpOlVNvOEGwPHYEQWJ8', 1, 12, NULL, '2026-03-26', '11:00:00', 'Control de ortodoncia', 'cancelada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(6, 'sr6A1yURy2UnVqqLj3lAQyHrsCSSQHfb2XvupXQh', 25, 12, NULL, '2026-03-13', '17:00:00', 'Consulta general', 'cancelada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(7, 'aAdm27i8YhwtHrBgGkmc9PG1lDF3HS4yuGtzcowC', 22, 5, NULL, '2026-03-07', '11:00:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(8, 's42cg2QtQga6jdsvfkPVmanVg09mHCLlmwNa46xa', 17, 11, NULL, '2026-03-27', '17:30:00', 'Blanqueamiento', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(9, 'Pk2aXi8MnmUBZOqVvLAGZTfBWqo1oIdgGcK2NmZp', 19, 5, NULL, '2026-04-06', '14:30:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(10, 'V7vKX62w6JZjPPeBWKDbiXkLi3AvjnFIlgvq5w4A', 24, 6, NULL, '2026-04-16', '16:30:00', 'Control post-operatorio', 'cancelada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(11, 'Mq3aIWnQodFiPt6jv8cV5OzEerNgfZwdQxC0quYg', 24, 9, NULL, '2026-04-07', '12:30:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(12, 'BmQNWeT8l4xRFmF2AfomWmoVOqfKIUAPyne4rq66', 12, 12, NULL, '2026-04-18', '10:00:00', 'Extraccion', 'cancelada', NULL, NULL, '2026-08-10 17:12:57', '2026-08-10 17:12:57'),
	(13, 'VkFfn2VpBjIMPEtR91FVzcTqqwf9CpywjVSsGHUY', 21, 6, NULL, '2026-04-30', '12:00:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(14, '875iwtmuiVRI6d3S2Avjfr8yna2J3W5xrqWAwMiP', 26, 5, NULL, '2026-04-03', '17:30:00', 'Limpieza dental', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(15, 'NNkthLg9paYhPym3udhSwPu4yoUS3RGeZZ9LTMD1', 18, 12, NULL, '2026-04-23', '09:00:00', 'Revision', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(16, 'qVp10wonSwSOKw7mdxjZQTRJsdx3oTpxJu5NFPPd', 22, 5, NULL, '2026-04-08', '11:00:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(17, 'KGdcwt4TeNovnsnyJObMBgVaC5OV4dueJ1Z7phPr', 16, 12, NULL, '2026-04-14', '15:30:00', 'Endodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(18, 'YU4e8aBb7VyMmgmCi7JRjzVnnBvcAABujSzmJRAJ', 20, 10, NULL, '2026-04-14', '09:00:00', 'Blanqueamiento', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(19, 'N5HaftAZomBFwMzRzSs9spC2AflQKFoRUQb6t0Sl', 18, 10, NULL, '2026-04-18', '15:00:00', 'Limpieza dental', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(20, 'Y8uFNODOmRlKGlB9mhtnhWEfs60oxpm75N1j7kxD', 26, 6, NULL, '2026-05-13', '12:30:00', 'Control post-operatorio', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(21, 'ujsQPEN0D17CArUVdhdofKG4NMStItsG70hQlhqh', 2, 10, NULL, '2026-05-21', '13:30:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(22, '5GzB49ipe041kYC6EComugTV8LAQndo1RTZPPIE8', 30, 3, NULL, '2026-05-17', '13:30:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(23, 'Gz0y36Arzenmj4JGNJ0EVdeKNN4iwOhR3dyWAYem', 24, 4, NULL, '2026-05-25', '16:30:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(24, 'nKG4w1VaYqFWJgfa8KwPg8pqrIFsZccQ3l5BILAe', 2, 4, NULL, '2026-05-11', '14:00:00', 'Limpieza dental', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(25, 'ClJ9p3L7OL1xVlhw0VcyjswsZ35QBQYZGaMR0eaQ', 29, 2, NULL, '2026-05-25', '11:30:00', 'Control de ortodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(26, 'FRDHJ5HqM6yWvzxyAADHuvDeLBXF9t9DwcZobxtN', 12, 12, NULL, '2026-05-05', '17:00:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(27, 'nx9FNXvuP0r1cdgkJTLLnbLQHEqQDGPuI6Ojrso5', 17, 10, NULL, '2026-05-14', '13:00:00', 'Limpieza dental', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(28, '2GG8ce1KNGkvabprc293gUsSiyISqT527AYzkCxT', 13, 12, NULL, '2026-05-21', '10:00:00', 'Extraccion', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(29, '6BEBZ1oyN6F9IAlswNKi6hTyqb2aOQEzZruEUkuQ', 6, 11, NULL, '2026-05-20', '18:00:00', 'Endodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(30, 'OfeoytJpI8LCWjKmym0zP6IzX3oHFeLA6pvYTEi4', 5, 3, NULL, '2026-05-10', '11:00:00', 'Control de ortodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(31, 'mbqVKUvjxYiC7I39ud8WsAsG02ckiM9uNHuGP3YE', 8, 5, NULL, '2026-05-15', '13:00:00', 'Revision', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(32, 'iNwy8k4pJsrT4RUJ5Y6m0ONcqZjQZSRYDj9BxJEe', 12, 12, NULL, '2026-05-01', '09:00:00', 'Limpieza dental', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(33, 'oxc62cp9cPEVg20Nbv5micuOASPdNBfPiudAAswe', 21, 12, NULL, '2026-05-20', '09:00:00', 'Endodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(34, 'cIwNWe28JRZwYBTYMMEyJvqjSqJcccUUdrbGOd5r', 1, 10, NULL, '2026-06-18', '18:00:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(35, 'MPcQ9vTz0vm4EG4BbLF2NPIcWgyifvi2UmjBkwTP', 11, 3, NULL, '2026-06-21', '17:30:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(36, 'uLnpZV9nfm5CnAjlDXFJFBj899QZ86eAKTKMKQJo', 24, 9, NULL, '2026-06-04', '15:30:00', 'Blanqueamiento', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(37, 'Gtd3LFMr0mb0Ma2E7HhsJfcZRIfIV9VrugDFnaDZ', 21, 9, NULL, '2026-06-13', '12:30:00', 'Limpieza dental', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(38, '1Wa1NrlzYTFnjyUPlvYjpJjBNNn3jsNPMqAnNHKQ', 1, 6, NULL, '2026-06-10', '14:00:00', 'Extraccion', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(39, 'hTro7mllGouiJnStyOH22fWbRNfPAqUFjTXcS8uG', 15, 6, NULL, '2026-06-17', '08:00:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(40, '8lhrHOHJrPeRcIkPRi1mw9CvDw8xXbo00Ah5Yqmv', 20, 10, NULL, '2026-06-27', '12:30:00', 'Revision', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(41, '66cgZ7VRFJfrvRom7A7yxqrBSaqdo3UQfdWeMEjo', 5, 7, NULL, '2026-06-28', '14:30:00', 'Control de ortodoncia', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(42, 'HLA4jph3WVsDz2ohNqknxS6rFKLaoX2fcQGoOHhd', 7, 7, NULL, '2026-06-08', '10:00:00', 'Control de ortodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(43, 'yQn7nDqiTJm455k8UcQUSsfBznj8j7fMTxFKZhO1', 25, 10, NULL, '2026-06-17', '10:30:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(44, 'F7o7Tq0t39jlO1xdNOAULxtBqj4DMdSwGV1BRfpg', 17, 3, NULL, '2026-06-26', '12:30:00', 'Blanqueamiento', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(45, 'CjVeA5X1Qcygp4627lc13RcbIvIfXCdJdpAuxk7E', 18, 3, NULL, '2026-06-19', '10:00:00', 'Endodoncia', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(46, '3mEMoxnph8MAJZPTVImceBzGgg1I0jdYQOGdWuNn', 8, 6, NULL, '2026-06-19', '15:30:00', 'Colocacion de corona', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(47, 'X2YtCXIG12ZVX1NMAnabWlMul6EU7Y72ZFflqHK8', 13, 11, NULL, '2026-06-24', '16:00:00', 'Colocacion de corona', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(48, 'cJtTyGmoYptag8v05GWlEGq366zW6Tl2QZktMlwT', 28, 9, NULL, '2026-06-15', '16:00:00', 'Limpieza dental', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(49, 'KZMSmSqhXGMSr2uNaEk0Q1owonuEuqRlSzgAQ3vg', 2, 7, NULL, '2026-06-05', '11:30:00', 'Urgencia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(50, '8o5CYoaC9WVZeyMxisV63fXK6uWiPGb0hfcEEsbN', 24, 11, NULL, '2026-06-06', '09:00:00', 'Colocacion de corona', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(51, '303g3prkyaIIH79ySjlGckBf8zBcSj059VSugmMA', 27, 11, NULL, '2026-07-22', '10:30:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(52, 'bt9SElrRCLOADpshMee4BnC0ssT8f4Fc4knx8AV8', 4, 4, NULL, '2026-07-12', '08:30:00', 'Urgencia', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(53, 'f12Fh0KDPLjmaEv9xCvS2TFsjbEZDuBzkxGcyiJz', 28, 6, NULL, '2026-07-13', '10:30:00', 'Control post-operatorio', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(54, 'rpxpuEtA44rddCMQ12zAib4gixVj4QazX1qoxTyE', 5, 10, NULL, '2026-07-27', '15:30:00', 'Control de ortodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(55, 'TM1G91xFELnDOFT7jIV4SDO87IO7kqgmvmijdo3M', 4, 7, NULL, '2026-07-16', '15:00:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(56, '9xW9NX24OtQxlvU8dXakMjzBRUqS4Ik7ap6sIqA0', 24, 7, NULL, '2026-07-18', '12:30:00', 'Extraccion', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(57, 'ukByZg1fokjuY4I0nP98kPfoMmYLiplEOGlE8VZV', 9, 10, NULL, '2026-07-06', '18:30:00', 'Limpieza dental', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(58, 'sIZ7eZd8KuDMVBEqhiIKnJUD6Fieh8lunD55IdeC', 12, 4, NULL, '2026-07-27', '09:30:00', 'Colocacion de corona', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(59, '1raMKgoG7uV2oW1kd9HZCYIeXSADWgswpbGkmSo5', 26, 6, NULL, '2026-07-20', '12:00:00', 'Extraccion', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(60, 'nhqZPYLGqfT1nN9kdGfRkDsI2TiYIjM3EplHk8jC', 17, 6, NULL, '2026-07-15', '09:30:00', 'Revision', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(61, '86z2qtKrmANuPKvC3VkQctK0yj8S34hDWUK3l2mv', 4, 7, NULL, '2026-07-24', '13:30:00', 'Revision', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(62, 'GLs47ReVfaqyMTYu7xDgrqDUrw65MEOqhIQ7EveT', 4, 3, NULL, '2026-07-03', '17:00:00', 'Extraccion', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(63, '9MdnF3EcmCGg5VshrftkMTurgXtqG1kVxnq26xVB', 23, 11, NULL, '2026-07-19', '10:00:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(64, 'jy9jC18s2RgBZKqvwKd5pHMpRDxncOygvhpvgKd5', 25, 5, NULL, '2026-07-08', '09:00:00', 'Extraccion', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(65, 'kHhcf2LUn9UYlm2gBNnf3DbUZz7OyiEXwtSPxYNG', 26, 12, NULL, '2026-07-28', '08:00:00', 'Consulta general', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(66, 'OScK93TSTxXP9f24YFrdshBBwGADBVmWwsKXWg8i', 12, 9, NULL, '2026-07-30', '16:00:00', 'Limpieza dental', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(67, 'oYZYQwbCTrtrmQLDioZph9wgPrUl5Rvc8J39qrPY', 9, 4, NULL, '2026-07-19', '13:30:00', 'Endodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(68, 'YUE33p4HNCiWcCstY5hd35BTjBkAa6U2Lk37ONek', 29, 6, NULL, '2026-07-10', '12:00:00', 'Colocacion de corona', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(69, 'RPtP8oPsv4pNrl626Y1N5woWFB2dDgbVqm8pd8ls', 14, 12, NULL, '2026-07-09', '14:00:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(70, 'sB5egoWIsZqgR9tbhuQ6WnSQNoUIffcXwzbv4Mc0', 6, 5, NULL, '2026-07-28', '16:00:00', 'Endodoncia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(71, 'vxSunKcPVnKJh4SjW7yUtETnCxsTleGcZ0VfPdbB', 26, 12, NULL, '2026-08-09', '08:30:00', 'Control post-operatorio', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(72, '0aIYc4Pc8wgK4qcKhXojuTQhtl3ZvYds8FjIWGwD', 20, 9, NULL, '2026-08-03', '08:30:00', 'Revision', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(73, 'F9o0ZR6E0NQ13rPOTgMXEkGeqiuyWqDivVEB8pyt', 1, 11, NULL, '2026-08-03', '13:00:00', 'Limpieza dental', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(74, 'Ju7VwPTWkoiVkJppi2cS8lTAUhrlDsTqQIY0Terk', 23, 7, NULL, '2026-08-08', '18:00:00', 'Colocacion de corona', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(75, '1DBTs1TF8uJkL7McBPifyvefoMZDgGa8wyiVUtok', 8, 4, NULL, '2026-08-01', '16:30:00', 'Blanqueamiento', 'cancelada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(76, 'S3EX50DYBc5gjS8tkv0n6TpXZZO6KBPbEOilaL71', 16, 6, NULL, '2026-08-09', '11:30:00', 'Control de ortodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(77, 'W7ZYTbtl5JAYxd3st3WiKXYNBdoyVrsLFw6tAnC0', 6, 6, NULL, '2026-08-07', '08:30:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(78, 'TlFivCU5wPgjLuHn4O8YoU5dg2kDJVNIlnRg5XCy', 17, 4, NULL, '2026-08-10', '08:00:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(79, 'fwWhHgYRu4EVGWcaIphHznqoEfwQ7dIHGP1RSyws', 19, 5, NULL, '2026-08-10', '09:00:00', 'Urgencia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(80, 'aqfRk1YEW23O1kPQ5FYpzwTmloMY7CFVjiYi5cSg', 6, 11, NULL, '2026-08-10', '10:30:00', 'Control post-operatorio', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(81, 'QAcEWew27FAvxs3ELFzdQQLlTT2CQpAs2psdi41M', 22, 2, NULL, '2026-08-10', '11:00:00', 'Urgencia', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(82, 'c5ollj0FpLZVGhRnJ6HOMAIZ00UY91xNEJM3Mar5', 11, 4, NULL, '2026-08-10', '12:30:00', 'Control post-operatorio', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(83, 'lbKq6gekiBZ7xC8y4mPPCHRvQR9DNqSTJQaYBnGI', 8, 3, NULL, '2026-08-10', '15:00:00', 'Control de ortodoncia', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(84, 'bAn1PCjSsAdQnO7WQwblFmacD2cg6UyJo2wZ0x7o', 24, 4, NULL, '2026-08-10', '16:30:00', 'Consulta general', 'completada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(85, '18NVmNQvVEKh2XDJ2Nuu7OAtNOACTViCH8r48Yws', 30, 10, NULL, '2026-08-10', '17:00:00', 'Colocacion de corona', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(86, 'FPObGU8UrNTxgZmlvdut3XwhgEi81aghyrSTbw2X', 1, 11, NULL, '2026-09-03', '16:00:00', 'Control post-operatorio', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(87, '70a50YGhHvle6cBuJaL8jb0g2zh5sbLJcrv6Dnkh', 29, 3, NULL, '2026-08-16', '09:00:00', 'Blanqueamiento', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(88, 'iXwL1m2J1ZP7huKTzhYwQEjYl1uSDtit7UQIKWvO', 11, 4, NULL, '2026-08-17', '16:30:00', 'Extraccion', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(89, 'LkY1Uu9phEuC3gxgYcgMRlNnVwkJ3BoPacMfiOZZ', 17, 11, NULL, '2026-08-15', '18:00:00', 'Endodoncia', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(90, 'fk1SsWC3PQuupvS6kpmwJi66fcunusssHOW8ZEg7', 20, 11, NULL, '2026-08-16', '09:30:00', 'Colocacion de corona', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(91, 'OK1c6n1HJLpNrYDvrv5FGSSTpHMEUOPYaEHMoWbZ', 4, 3, NULL, '2026-08-12', '12:00:00', 'Extraccion', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(92, 'D6u7bL2XycipqwXlQmsi6nRh3KpKRXzQf9iHjhQJ', 1, 12, NULL, '2026-08-22', '09:00:00', 'Revision', 'pendiente', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(93, 'mf6somMxcPEmz0Q2bLCheYvDZPcIPZIXeuLrm9EU', 18, 12, NULL, '2026-08-16', '16:30:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(94, 'SuAfUgTZlcfZyUBYNYhDWJAZBHSJwbCJ5Yfn8fns', 7, 3, NULL, '2026-09-08', '11:00:00', 'Control post-operatorio', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(95, 'vRwr2yogbJzmaXWMKHNiJx7qvMKMKQQBStsYRY3p', 18, 10, NULL, '2026-08-17', '13:00:00', 'Urgencia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(96, 'ansYRoEEMsshyc7uZGXCH77OQ7ByJ9j7VYSPsFqA', 11, 4, NULL, '2026-08-22', '14:00:00', 'Urgencia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(97, '4Rz09XWrBU4Z1J3TUr2qrTnMl7ubPZ4SViH3mT9a', 10, 10, NULL, '2026-08-29', '08:00:00', 'Colocacion de corona', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(98, 'yrUyLFvhy9fjRnxY4zEOWbF8sSDLz6AeJruNtSVC', 24, 5, NULL, '2026-08-30', '13:30:00', 'Blanqueamiento', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(99, 'YYyebcv8YrGLscyfgW43oSWUdBW1QIt0SWXzwvdi', 2, 3, NULL, '2026-08-27', '08:30:00', 'Endodoncia', 'confirmada', NULL, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58');

-- Volcando estructura para tabla saas_odontologia.configuraciones
CREATE TABLE IF NOT EXISTS `configuraciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuraciones_clave_unique` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.configuraciones: ~0 rows (aproximadamente)
DELETE FROM `configuraciones`;

-- Volcando estructura para tabla saas_odontologia.consentimientos
CREATE TABLE IF NOT EXISTS `consentimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenido` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `firmado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_firma` date DEFAULT NULL,
  `firmante` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consentimientos_user_id_foreign` (`user_id`),
  KEY `consentimientos_paciente_id_firmado_index` (`paciente_id`,`firmado`),
  CONSTRAINT `consentimientos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consentimientos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.consentimientos: ~0 rows (aproximadamente)
DELETE FROM `consentimientos`;
INSERT INTO `consentimientos` (`id`, `paciente_id`, `user_id`, `tipo`, `titulo`, `contenido`, `firmado`, `fecha_firma`, `firmante`, `created_at`, `updated_at`) VALUES
	(1, 1, 9, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-06-03', 'Naiara Vargas', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(2, 2, 3, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-06-23', 'Nahia Bustos', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(3, 3, 3, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-06-08', 'Óscar Dueñas', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(4, 4, 6, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-05-27', 'Unai Espino', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(5, 5, 12, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-08-10', 'Leo Lucio', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(6, 6, 3, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-08-07', 'Nayara Ramírez', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(7, 7, 12, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-08-10', 'Elena Castellano', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(8, 8, 3, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-05-21', 'Lara Delarosa', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(9, 9, 3, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-08-06', 'José Manuel Castaño', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(10, 10, 6, 'general', 'Consentimiento informado de tratamiento', 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.', 1, '2026-07-17', 'Andrés Vidal', '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.cuotas
CREATE TABLE IF NOT EXISTS `cuotas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint unsigned NOT NULL,
  `pago_id` bigint unsigned DEFAULT NULL,
  `numero` int unsigned NOT NULL,
  `monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vence_el` date NOT NULL,
  `pagada` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cuotas_pago_id_foreign` (`pago_id`),
  KEY `cuotas_presupuesto_id_numero_index` (`presupuesto_id`,`numero`),
  KEY `cuotas_pagada_vence_el_index` (`pagada`,`vence_el`),
  CONSTRAINT `cuotas_pago_id_foreign` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cuotas_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.cuotas: ~4 rows (aproximadamente)
DELETE FROM `cuotas`;
INSERT INTO `cuotas` (`id`, `presupuesto_id`, `pago_id`, `numero`, `monto`, `vence_el`, `pagada`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 1, 60.75, '2026-09-04', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(2, 1, NULL, 2, 60.75, '2026-10-04', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(3, 2, NULL, 1, 1482.30, '2026-09-03', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(4, 2, NULL, 2, 1482.30, '2026-10-03', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(5, 9, NULL, 1, 595.33, '2026-05-16', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(6, 9, NULL, 2, 595.33, '2026-06-16', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(7, 9, NULL, 3, 595.33, '2026-07-16', 0, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(8, 12, NULL, 1, 216.00, '2026-04-27', 0, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(9, 12, NULL, 2, 216.00, '2026-05-27', 0, '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.documentos_fiscales
CREATE TABLE IF NOT EXISTS `documentos_fiscales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa_id` bigint unsigned DEFAULT NULL,
  `pais` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` bigint unsigned DEFAULT NULL,
  `emisor_snapshot` json DEFAULT NULL,
  `receptor_snapshot` json DEFAULT NULL,
  `moneda` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PEN',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `impuestos` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador',
  `referencia_externa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idempotency_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `intentos` int unsigned NOT NULL DEFAULT '0',
  `error` text COLLATE utf8mb4_unicode_ci,
  `xml_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cdr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origen_tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origen_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documentos_fiscales_uuid_unique` (`uuid`),
  UNIQUE KEY `documentos_fiscales_idempotency_key_unique` (`idempotency_key`),
  KEY `documentos_fiscales_origen_tipo_origen_id_index` (`origen_tipo`,`origen_id`),
  KEY `documentos_fiscales_empresa_id_index` (`empresa_id`),
  KEY `documentos_fiscales_pais_index` (`pais`),
  KEY `documentos_fiscales_estado_index` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.documentos_fiscales: ~0 rows (aproximadamente)
DELETE FROM `documentos_fiscales`;

-- Volcando estructura para tabla saas_odontologia.documento_eventos
CREATE TABLE IF NOT EXISTS `documento_eventos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documento_fiscal_id` bigint unsigned NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_anterior` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_nuevo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documento_eventos_documento_fiscal_id_foreign` (`documento_fiscal_id`),
  CONSTRAINT `documento_eventos_documento_fiscal_id_foreign` FOREIGN KEY (`documento_fiscal_id`) REFERENCES `documentos_fiscales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.documento_eventos: ~0 rows (aproximadamente)
DELETE FROM `documento_eventos`;

-- Volcando estructura para tabla saas_odontologia.documento_lineas
CREATE TABLE IF NOT EXISTS `documento_lineas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documento_fiscal_id` bigint unsigned NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(12,3) NOT NULL DEFAULT '1.000',
  `precio` decimal(12,2) NOT NULL DEFAULT '0.00',
  `impuesto_tasa` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `impuesto_monto` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documento_lineas_documento_fiscal_id_foreign` (`documento_fiscal_id`),
  CONSTRAINT `documento_lineas_documento_fiscal_id_foreign` FOREIGN KEY (`documento_fiscal_id`) REFERENCES `documentos_fiscales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.documento_lineas: ~0 rows (aproximadamente)
DELETE FROM `documento_lineas`;

-- Volcando estructura para tabla saas_odontologia.evoluciones
CREATE TABLE IF NOT EXISTS `evoluciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `paciente_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `diente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evoluciones_paciente_id_foreign` (`paciente_id`),
  KEY `evoluciones_user_id_foreign` (`user_id`),
  CONSTRAINT `evoluciones_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `evoluciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.evoluciones: ~0 rows (aproximadamente)
DELETE FROM `evoluciones`;
INSERT INTO `evoluciones` (`id`, `paciente_id`, `user_id`, `fecha`, `diente`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 14, 7, '2026-08-05', '11', 'Se realiza profilaxis y aplicacion de fluor. Paciente tolera bien.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(2, 3, 9, '2026-07-14', '46', 'Apertura camara pulpar, se instrumenta conducto. Continua proxima sesion.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(3, 17, 12, '2026-06-09', '36', 'Obturacion con resina en cara oclusal. Ajuste de oclusion.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(4, 10, 11, '2026-05-14', '24', 'Control de ortodoncia, cambio de ligaduras y activacion.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(5, 25, 5, '2026-04-30', '14', 'Extraccion sin complicaciones. Indicaciones post-operatorias.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(6, 11, 12, '2026-03-03', '16', 'Toma de impresiones para corona. Cementado provisional.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(7, 11, 4, '2026-08-06', '21', 'Curetaje por cuadrante. Se indica enjuague con clorhexidina.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(8, 1, 10, '2026-07-21', '47', 'Revision general, sin hallazgos patologicos.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(9, 17, 4, '2026-06-06', '36', 'Blanqueamiento en consultorio, primera sesion.', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(10, 26, 12, '2026-05-13', '37', 'Control post-operatorio, cicatrizacion adecuada.', '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.facturacion_configuraciones
CREATE TABLE IF NOT EXISTS `facturacion_configuraciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facturacion_configuraciones_clave_unique` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.facturacion_configuraciones: ~0 rows (aproximadamente)
DELETE FROM `facturacion_configuraciones`;

-- Volcando estructura para tabla saas_odontologia.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.failed_jobs: ~0 rows (aproximadamente)
DELETE FROM `failed_jobs`;

-- Volcando estructura para tabla saas_odontologia.gastos
CREATE TABLE IF NOT EXISTS `gastos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'otros',
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia','qr') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'efectivo',
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gastos_user_id_foreign` (`user_id`),
  KEY `gastos_fecha_index` (`fecha`),
  KEY `gastos_categoria_index` (`categoria`),
  CONSTRAINT `gastos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.gastos: ~80 rows (aproximadamente)
DELETE FROM `gastos`;
INSERT INTO `gastos` (`id`, `fecha`, `categoria`, `descripcion`, `monto`, `metodo`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, '2026-07-02', 'insumos', 'Compra de resinas y anestesia', 1997.00, 'efectivo', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(2, '2026-07-02', 'laboratorio', 'Trabajo de laboratorio (corona)', 914.00, 'efectivo', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(3, '2026-05-02', 'sueldos', 'Honorarios asistente dental', 2538.00, 'tarjeta', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(4, '2026-04-04', 'alquiler', 'Alquiler del consultorio', 162.00, 'tarjeta', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(5, '2026-03-18', 'servicios', 'Luz, agua e internet', 1609.00, 'transferencia', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(6, '2026-02-15', 'equipos', 'Mantenimiento de compresor', 3350.00, 'transferencia', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(7, '2026-07-01', 'marketing', 'Publicidad en redes sociales', 3189.00, 'qr', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(8, '2026-06-07', 'impuestos', 'Pago de impuestos municipales', 388.00, 'efectivo', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(9, '2026-05-31', 'insumos', 'Guantes y barbijos (bioseguridad)', 779.00, 'transferencia', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(10, '2026-04-13', 'otros', 'Gastos varios de oficina', 2673.00, 'efectivo', 1, '2026-07-02 13:23:47', '2026-07-02 13:23:47'),
	(11, '2026-07-08', 'insumos', 'Compra de resinas y anestesia', 2685.00, 'efectivo', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(12, '2026-07-08', 'laboratorio', 'Trabajo de laboratorio (corona)', 1291.00, 'qr', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(13, '2026-05-15', 'sueldos', 'Honorarios asistente dental', 810.00, 'transferencia', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(14, '2026-04-09', 'alquiler', 'Alquiler del consultorio', 1897.00, 'tarjeta', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(15, '2026-03-29', 'servicios', 'Luz, agua e internet', 2928.00, 'transferencia', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(16, '2026-02-08', 'equipos', 'Mantenimiento de compresor', 1498.00, 'qr', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(17, '2026-07-03', 'marketing', 'Publicidad en redes sociales', 2274.00, 'qr', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(18, '2026-06-27', 'impuestos', 'Pago de impuestos municipales', 3320.00, 'qr', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(19, '2026-05-27', 'insumos', 'Guantes y barbijos (bioseguridad)', 872.00, 'qr', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(20, '2026-04-04', 'otros', 'Gastos varios de oficina', 1769.00, 'efectivo', 1, '2026-07-08 14:43:17', '2026-07-08 14:43:17'),
	(21, '2026-07-10', 'insumos', 'Compra de resinas y anestesia', 856.00, 'transferencia', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(22, '2026-07-10', 'laboratorio', 'Trabajo de laboratorio (corona)', 2638.00, 'tarjeta', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(23, '2026-05-27', 'sueldos', 'Honorarios asistente dental', 2297.00, 'tarjeta', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(24, '2026-04-07', 'alquiler', 'Alquiler del consultorio', 3124.00, 'tarjeta', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(25, '2026-03-15', 'servicios', 'Luz, agua e internet', 1072.00, 'transferencia', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(26, '2026-02-18', 'equipos', 'Mantenimiento de compresor', 3251.00, 'qr', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(27, '2026-07-07', 'marketing', 'Publicidad en redes sociales', 964.00, 'tarjeta', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(28, '2026-06-05', 'impuestos', 'Pago de impuestos municipales', 2346.00, 'efectivo', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(29, '2026-05-27', 'insumos', 'Guantes y barbijos (bioseguridad)', 1472.00, 'qr', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(30, '2026-04-07', 'otros', 'Gastos varios de oficina', 1415.00, 'tarjeta', 1, '2026-07-10 13:51:59', '2026-07-10 13:51:59'),
	(31, '2026-07-13', 'insumos', 'Compra de resinas y anestesia', 3483.00, 'transferencia', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(32, '2026-07-13', 'laboratorio', 'Trabajo de laboratorio (corona)', 3442.00, 'qr', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(33, '2026-05-19', 'sueldos', 'Honorarios asistente dental', 2300.00, 'qr', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(34, '2026-04-29', 'alquiler', 'Alquiler del consultorio', 1046.00, 'tarjeta', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(35, '2026-03-05', 'servicios', 'Luz, agua e internet', 916.00, 'tarjeta', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(36, '2026-02-22', 'equipos', 'Mantenimiento de compresor', 3474.00, 'qr', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(37, '2026-07-12', 'marketing', 'Publicidad en redes sociales', 464.00, 'transferencia', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(38, '2026-06-09', 'impuestos', 'Pago de impuestos municipales', 2988.00, 'transferencia', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(39, '2026-05-30', 'insumos', 'Guantes y barbijos (bioseguridad)', 2985.00, 'transferencia', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(40, '2026-04-12', 'otros', 'Gastos varios de oficina', 2267.00, 'qr', 1, '2026-07-13 15:00:10', '2026-07-13 15:00:10'),
	(41, '2026-07-14', 'insumos', 'Compra de resinas y anestesia', 780.00, 'qr', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(42, '2026-07-14', 'laboratorio', 'Trabajo de laboratorio (corona)', 1108.00, 'efectivo', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(43, '2026-05-22', 'sueldos', 'Honorarios asistente dental', 1699.00, 'efectivo', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(44, '2026-04-21', 'alquiler', 'Alquiler del consultorio', 3065.00, 'qr', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(45, '2026-03-31', 'servicios', 'Luz, agua e internet', 2429.00, 'efectivo', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(46, '2026-02-26', 'equipos', 'Mantenimiento de compresor', 3411.00, 'tarjeta', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(47, '2026-07-05', 'marketing', 'Publicidad en redes sociales', 160.00, 'qr', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(48, '2026-06-22', 'impuestos', 'Pago de impuestos municipales', 1336.00, 'transferencia', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(49, '2026-05-06', 'insumos', 'Guantes y barbijos (bioseguridad)', 1102.00, 'efectivo', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(50, '2026-04-19', 'otros', 'Gastos varios de oficina', 2303.00, 'tarjeta', 1, '2026-07-14 15:02:03', '2026-07-14 15:02:03'),
	(51, '2026-07-28', 'insumos', 'Compra de resinas y anestesia', 1920.00, 'tarjeta', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(52, '2026-07-28', 'laboratorio', 'Trabajo de laboratorio (corona)', 2948.00, 'efectivo', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(53, '2026-05-23', 'sueldos', 'Honorarios asistente dental', 1153.00, 'qr', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(54, '2026-04-28', 'alquiler', 'Alquiler del consultorio', 2555.00, 'tarjeta', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(55, '2026-03-29', 'servicios', 'Luz, agua e internet', 729.00, 'efectivo', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(56, '2026-02-13', 'equipos', 'Mantenimiento de compresor', 1694.00, 'transferencia', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(57, '2026-07-12', 'marketing', 'Publicidad en redes sociales', 487.00, 'tarjeta', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(58, '2026-06-24', 'impuestos', 'Pago de impuestos municipales', 1530.00, 'tarjeta', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(59, '2026-05-16', 'insumos', 'Guantes y barbijos (bioseguridad)', 224.00, 'efectivo', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(60, '2026-04-04', 'otros', 'Gastos varios de oficina', 3320.00, 'efectivo', 1, '2026-07-29 04:13:38', '2026-07-29 04:13:38'),
	(61, '2026-07-31', 'insumos', 'Compra de resinas y anestesia', 453.00, 'transferencia', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(62, '2026-07-31', 'laboratorio', 'Trabajo de laboratorio (corona)', 510.00, 'tarjeta', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(63, '2026-05-18', 'sueldos', 'Honorarios asistente dental', 1454.00, 'qr', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(64, '2026-04-06', 'alquiler', 'Alquiler del consultorio', 2898.00, 'tarjeta', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(65, '2026-03-29', 'servicios', 'Luz, agua e internet', 2751.00, 'tarjeta', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(66, '2026-02-19', 'equipos', 'Mantenimiento de compresor', 371.00, 'transferencia', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(67, '2026-07-09', 'marketing', 'Publicidad en redes sociales', 3363.00, 'transferencia', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(68, '2026-06-27', 'impuestos', 'Pago de impuestos municipales', 1657.00, 'tarjeta', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(69, '2026-05-11', 'insumos', 'Guantes y barbijos (bioseguridad)', 3097.00, 'efectivo', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(70, '2026-04-29', 'otros', 'Gastos varios de oficina', 1014.00, 'transferencia', 1, '2026-07-31 15:58:28', '2026-07-31 15:58:28'),
	(71, '2026-08-02', 'insumos', 'Compra de resinas y anestesia', 2450.00, 'qr', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(72, '2026-08-02', 'laboratorio', 'Trabajo de laboratorio (corona)', 1887.00, 'transferencia', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(73, '2026-06-18', 'sueldos', 'Honorarios asistente dental', 880.00, 'qr', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(74, '2026-05-14', 'alquiler', 'Alquiler del consultorio', 3356.00, 'transferencia', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(75, '2026-04-23', 'servicios', 'Luz, agua e internet', 1316.00, 'tarjeta', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(76, '2026-03-22', 'equipos', 'Mantenimiento de compresor', 540.00, 'tarjeta', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(77, '2026-08-01', 'marketing', 'Publicidad en redes sociales', 1400.00, 'tarjeta', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(78, '2026-07-05', 'impuestos', 'Pago de impuestos municipales', 815.00, 'transferencia', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(79, '2026-06-24', 'insumos', 'Guantes y barbijos (bioseguridad)', 3440.00, 'tarjeta', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(80, '2026-05-30', 'otros', 'Gastos varios de oficina', 2129.00, 'transferencia', 1, '2026-08-02 17:11:41', '2026-08-02 17:11:41'),
	(81, '2026-08-10', 'insumos', 'Compra de resinas y anestesia', 569.00, 'qr', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(82, '2026-08-10', 'laboratorio', 'Trabajo de laboratorio (corona)', 3089.00, 'efectivo', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(83, '2026-06-10', 'sueldos', 'Honorarios asistente dental', 217.00, 'qr', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(84, '2026-05-22', 'alquiler', 'Alquiler del consultorio', 1213.00, 'qr', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(85, '2026-04-06', 'servicios', 'Luz, agua e internet', 3089.00, 'efectivo', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(86, '2026-03-18', 'equipos', 'Mantenimiento de compresor', 1195.00, 'transferencia', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(87, '2026-08-02', 'marketing', 'Publicidad en redes sociales', 550.00, 'qr', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(88, '2026-07-05', 'impuestos', 'Pago de impuestos municipales', 2616.00, 'qr', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(89, '2026-06-08', 'insumos', 'Guantes y barbijos (bioseguridad)', 644.00, 'efectivo', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(90, '2026-05-10', 'otros', 'Gastos varios de oficina', 3245.00, 'efectivo', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.insumos
CREATE TABLE IF NOT EXISTS `insumos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `unidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unidad',
  `stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `proveedor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insumos_categoria_index` (`categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.insumos: ~0 rows (aproximadamente)
DELETE FROM `insumos`;
INSERT INTO `insumos` (`id`, `nombre`, `categoria`, `unidad`, `stock`, `stock_minimo`, `costo_unitario`, `proveedor`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Anestesia lidocaina 2%', 'anestesia', 'caja', 40.00, 10.00, 45.00, 'Meraz de Sierra', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(2, 'Resina compuesta A2', 'restauracion', 'unidad', 25.00, 8.00, 60.00, 'Aguado de Salazar e Hija', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(3, 'Limas endodoncia K', 'endodoncia', 'paquete', 15.00, 5.00, 120.00, 'Asociación Nájera y Flia.', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(4, 'Guantes de nitrilo', 'proteccion', 'caja', 8.00, 10.00, 35.00, 'Cordero y Conde y Flia.', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(5, 'Barbijos triple capa', 'proteccion', 'caja', 6.00, 10.00, 25.00, 'Global Munguía', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(6, 'Algodon dental', 'descartable', 'paquete', 30.00, 5.00, 12.00, 'Pastor-Roque', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(7, 'Brackets metalicos', 'ortodoncia', 'paquete', 12.00, 4.00, 80.00, 'Baca y Rueda', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(8, 'Hilo de sutura', 'cirugia', 'unidad', 20.00, 6.00, 18.00, 'Viajes Mateos y Flia.', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(9, 'Fluor gel', 'limpieza', 'frasco', 18.00, 5.00, 22.00, 'Grupo Carbonell e Hijos', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(10, 'Fresas de diamante', 'instrumental', 'unidad', 5.00, 8.00, 15.00, 'Grupo Aguilera-Matías', 1, '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.jobs: ~0 rows (aproximadamente)
DELETE FROM `jobs`;

-- Volcando estructura para tabla saas_odontologia.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.job_batches: ~0 rows (aproximadamente)
DELETE FROM `job_batches`;

-- Volcando estructura para tabla saas_odontologia.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.migrations: ~17 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_06_16_000001_create_pacientes_table', 1),
	(5, '2026_06_16_000002_create_citas_table', 1),
	(6, '2026_06_16_000003_create_tratamientos_table', 2),
	(7, '2026_06_16_000004_create_presupuestos_table', 3),
	(8, '2026_06_16_000005_create_pagos_table', 3),
	(9, '2026_06_16_000006_create_configuraciones_table', 3),
	(10, '2026_06_16_000007_create_actividades_table', 4),
	(11, '2026_06_16_000008_add_odontograma_to_pacientes', 5),
	(12, '2026_06_16_000009_create_evoluciones_table', 6),
	(13, '2026_06_16_000010_create_archivos_table', 6),
	(14, '2026_06_16_000011_add_realizado_to_presupuesto_items', 6),
	(15, '2026_06_17_000001_add_facturacion_y_cuotas', 6),
	(16, '2026_06_17_000002_add_caja_gastos_comisiones', 7),
	(17, '2026_06_17_000003_add_silla_to_citas', 8),
	(18, '2026_06_17_000004_add_token_to_citas', 9),
	(19, '2026_06_17_000005_antecedentes_y_consentimientos', 10),
	(20, '2026_06_17_000006_create_inventario', 11),
	(21, '2026_06_17_000007_add_portal_to_pacientes', 12),
	(22, '2026_06_18_000001_create_documentos_fiscales_table', 13),
	(23, '2026_08_10_000001_create_facturacion_configuraciones_table', 14);

-- Volcando estructura para tabla saas_odontologia.movimientos_inventario
CREATE TABLE IF NOT EXISTS `movimientos_inventario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `insumo_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `tipo` enum('entrada','salida','ajuste') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `stock_resultante` decimal(10,2) NOT NULL DEFAULT '0.00',
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_inventario_user_id_foreign` (`user_id`),
  KEY `movimientos_inventario_insumo_id_fecha_index` (`insumo_id`,`fecha`),
  CONSTRAINT `movimientos_inventario_insumo_id_foreign` FOREIGN KEY (`insumo_id`) REFERENCES `insumos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_inventario_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.movimientos_inventario: ~20 rows (aproximadamente)
DELETE FROM `movimientos_inventario`;
INSERT INTO `movimientos_inventario` (`id`, `insumo_id`, `user_id`, `tipo`, `cantidad`, `stock_resultante`, `motivo`, `fecha`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'entrada', 50.00, 50.00, 'Compra inicial', '2026-03-13', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(2, 1, 1, 'salida', 10.00, 40.00, 'Consumo en tratamientos', '2026-06-17', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(3, 2, 1, 'entrada', 35.00, 35.00, 'Compra inicial', '2026-04-21', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(4, 2, 1, 'salida', 10.00, 25.00, 'Consumo en tratamientos', '2026-06-03', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(5, 3, 1, 'entrada', 25.00, 25.00, 'Compra inicial', '2026-03-10', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(6, 3, 1, 'salida', 10.00, 15.00, 'Consumo en tratamientos', '2026-07-27', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(7, 4, 1, 'entrada', 18.00, 18.00, 'Compra inicial', '2026-04-13', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(8, 4, 1, 'salida', 10.00, 8.00, 'Consumo en tratamientos', '2026-08-05', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(9, 5, 1, 'entrada', 16.00, 16.00, 'Compra inicial', '2026-06-19', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(10, 5, 1, 'salida', 10.00, 6.00, 'Consumo en tratamientos', '2026-07-08', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(11, 6, 1, 'entrada', 40.00, 40.00, 'Compra inicial', '2026-05-01', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(12, 6, 1, 'salida', 10.00, 30.00, 'Consumo en tratamientos', '2026-07-27', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(13, 7, 1, 'entrada', 22.00, 22.00, 'Compra inicial', '2026-04-28', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(14, 7, 1, 'salida', 10.00, 12.00, 'Consumo en tratamientos', '2026-06-21', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(15, 8, 1, 'entrada', 30.00, 30.00, 'Compra inicial', '2026-04-07', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(16, 8, 1, 'salida', 10.00, 20.00, 'Consumo en tratamientos', '2026-07-07', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(17, 9, 1, 'entrada', 28.00, 28.00, 'Compra inicial', '2026-03-21', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(18, 9, 1, 'salida', 10.00, 18.00, 'Consumo en tratamientos', '2026-07-30', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(19, 10, 1, 'entrada', 15.00, 15.00, 'Compra inicial', '2026-07-28', '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(20, 10, 1, 'salida', 10.00, 5.00, 'Consumo en tratamientos', '2026-08-06', '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.pacientes
CREATE TABLE IF NOT EXISTS `pacientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `documento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `portal_activo` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','O') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_sangre` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alergias` text COLLATE utf8mb4_unicode_ci,
  `enfermedades` json DEFAULT NULL,
  `medicacion` text COLLATE utf8mb4_unicode_ci,
  `habitos` json DEFAULT NULL,
  `antecedentes_notas` text COLLATE utf8mb4_unicode_ci,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `odontograma` json DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pacientes_documento_index` (`documento`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.pacientes: ~30 rows (aproximadamente)
DELETE FROM `pacientes`;
INSERT INTO `pacientes` (`id`, `nombre`, `apellido`, `documento`, `telefono`, `email`, `password`, `portal_activo`, `fecha_nacimiento`, `genero`, `tipo_sangre`, `direccion`, `alergias`, `enfermedades`, `medicacion`, `habitos`, `antecedentes_notas`, `observaciones`, `odontograma`, `activo`, `created_at`, `updated_at`, `remember_token`) VALUES
	(1, 'Naiara', 'Vargas', '5656070', '77079679', 'mireia.guzman@example.com', NULL, 0, '2002-12-20', 'M', NULL, 'Paseo Riera, 652, 6º C', 'Ibuprofeno', '["Hipertension"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-08-06 22:22:00', '2026-08-06 22:22:00', NULL),
	(2, 'Nahia', 'Bustos', '5852718', '72417659', 'pelaez.adrian@example.org', NULL, 0, '1964-08-04', 'F', NULL, 'Travessera Diego, 7, 4º D', 'Ninguna conocida', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-07-13 14:21:00', '2026-07-13 14:21:00', NULL),
	(3, 'Óscar', 'Dueñas', '2104514', '75886592', 'iterrazas@example.net', NULL, 0, '1993-11-23', 'F', NULL, 'Avenida Briones, 7, Bajos', 'Penicilina', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-06-21 14:52:00', '2026-06-21 14:52:00', NULL),
	(4, 'Unai', 'Espino', '7298310', '73219265', 'cgarza@example.com', NULL, 0, '2006-07-11', 'F', NULL, 'Calle Claudia, 4, Bajos', 'Latex', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-05-02 23:38:00', '2026-05-02 23:38:00', NULL),
	(5, 'Leo', 'Lucio', '1556720', '73634588', 'samuel85@example.org', NULL, 0, '1996-10-22', 'F', NULL, 'Ruela Jon, 496, 9º A', 'Anestesia local', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-04-09 23:41:00', '2026-04-09 23:41:00', NULL),
	(6, 'Nayara', 'Ramírez', '6288008', '72759653', 'nadia70@example.net', NULL, 0, '2015-01-10', 'M', NULL, 'Praza Gonzáles, 3, 7º', 'Ibuprofeno', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-03-11 16:55:00', '2026-03-11 16:55:00', NULL),
	(7, 'Elena', 'Castellano', '7157093', '72729607', 'irene41@example.org', NULL, 0, '1987-07-09', 'M', NULL, 'Camiño Miguel, 180, Ático 3º', 'Ibuprofeno', '["Hipertension"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-08-05 15:42:00', '2026-08-05 15:42:00', NULL),
	(8, 'Lara', 'Delarosa', '7948729', '79005801', 'nahia.cardona@example.com', NULL, 0, '1970-09-21', 'M', NULL, 'Travesía Mireles, 75, 8º C', 'Anestesia local', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-07-15 22:51:00', '2026-07-15 22:51:00', NULL),
	(9, 'José Manuel', 'Castaño', '1102949', '72753761', 'pelayo.martin@example.com', NULL, 0, '1974-11-17', 'M', NULL, 'Plaza Del Río, 124, Entre suelo 4º', 'Ibuprofeno', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-06-01 17:41:00', '2026-06-01 17:41:00', NULL),
	(10, 'Andrés', 'Vidal', '1028226', '74054114', 'garica.franciscojavier@example.net', NULL, 0, '2018-01-21', 'F', NULL, 'Camiño Sosa, 7, Bajos', 'Ninguna conocida', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-05-31 17:03:00', '2026-05-31 17:03:00', NULL),
	(11, 'Marina', 'Valdivia', '7309963', '72405155', 'amparo.banda@example.com', NULL, 0, '1961-01-08', 'F', NULL, 'Ruela Naiara, 5, 41º E', 'Ibuprofeno', '["Diabetes"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-04-01 21:53:00', '2026-04-01 21:53:00', NULL),
	(12, 'Andrés', 'Trejo', '7374890', '78756829', 'ainhoa38@example.net', NULL, 0, '1968-06-23', 'F', NULL, 'Plaza Delapaz, 21, Entre suelo 3º', 'Ninguna conocida', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-03-09 18:57:00', '2026-03-09 18:57:00', NULL),
	(13, 'Noa', 'Alba', '9294434', '77960793', 'pablo.serrano@example.org', NULL, 0, '1964-09-09', 'M', NULL, 'Carrer Terán, 86, Ático 7º', 'Anestesia local', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-08-06 15:44:00', '2026-08-06 15:44:00', NULL),
	(14, 'Emilia', 'Gallegos', '3422482', '76957860', 'angel.abad@example.org', NULL, 0, '1997-03-19', 'M', NULL, 'Travessera Cabello, 502, Bajos', 'Ibuprofeno', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-07-24 22:04:00', '2026-07-24 22:04:00', NULL),
	(15, 'Pol', 'Álvarez', '4349077', '74409606', 'rayan06@example.org', NULL, 0, '2001-09-30', 'M', NULL, 'Travesía Marc, 184, 44º C', 'Anestesia local', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-06-20 18:59:00', '2026-06-20 18:59:00', NULL),
	(16, 'Sergio', 'Santamaría', '1041491', '76920332', 'pastor.santiago@example.net', NULL, 0, '1999-12-13', 'F', NULL, 'Travesía Saúl, 4, 90º B', 'Latex', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-05-18 17:30:00', '2026-05-18 17:30:00', NULL),
	(17, 'Raúl', 'Villa', '1599137', '71726780', 'cuenca.fernando@example.net', NULL, 0, '2008-04-14', 'F', NULL, 'Camiño Valdés, 1, Ático 1º', 'Ibuprofeno', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-04-27 23:23:00', '2026-04-27 23:23:00', NULL),
	(18, 'Omar', 'Herrera', '3524170', '79734008', 'isaac.romo@example.org', NULL, 0, '1969-09-07', 'M', NULL, 'Paseo Noriega, 9, 0º E', 'Ibuprofeno', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-03-18 17:04:00', '2026-03-18 17:04:00', NULL),
	(19, 'Abril', 'Marcos', '9324683', '77228336', 'alba41@example.org', NULL, 0, '2001-11-21', 'F', NULL, 'Passeig Ángel, 78, Bajos', 'Latex', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-08-03 18:09:00', '2026-08-03 18:09:00', NULL),
	(20, 'Beatriz', 'Aponte', '6619912', '79845657', 'alexia.navarro@example.org', NULL, 0, '2017-05-07', 'F', NULL, 'Avenida Arguello, 4, 0º D', 'Anestesia local', '["Hipertension"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-07-14 16:04:00', '2026-07-14 16:04:00', NULL),
	(21, 'Marcos', 'Juárez', '4085956', '77533530', 'carrillo.juan@example.org', NULL, 0, '1970-12-10', 'M', NULL, 'Passeig Pozo, 5, 75º E', 'Latex', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-06-11 21:16:00', '2026-06-11 21:16:00', NULL),
	(22, 'Celia', 'Esquivel', '3880147', '77122766', 'gerard.ordonez@example.org', NULL, 0, '1992-07-06', 'F', NULL, 'Ronda Rosa María, 38, 55º D', 'Ibuprofeno', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-05-01 20:47:00', '2026-05-01 20:47:00', NULL),
	(23, 'Elsa', 'Luevano', '6308477', '78110696', 'rocha.jon@example.net', NULL, 0, '1977-01-24', 'M', NULL, 'Camino Samuel, 26, 01º B', 'Anestesia local', '["Hipertension"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-04-08 22:23:00', '2026-04-08 22:23:00', NULL),
	(24, 'Iván', 'Pardo', '8156367', '78120040', 'znavas@example.org', NULL, 0, '1974-02-12', 'M', NULL, 'Camiño Sanz, 9, 6º E', 'Ninguna conocida', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-03-16 19:32:00', '2026-03-16 19:32:00', NULL),
	(25, 'Olivia', 'Navarro', '7803381', '71530990', 'arnau23@example.org', NULL, 0, '1962-01-12', 'F', NULL, 'Carrer Jimena, 6, 2º D', 'Anestesia local', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-08-07 14:50:00', '2026-08-07 14:50:00', NULL),
	(26, 'Alicia', 'Alonzo', '2151045', '78226655', 'erik06@example.com', NULL, 0, '1999-11-04', 'F', NULL, 'Plaça Esteve, 614, 7º C', 'Ibuprofeno', '["Asma"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-07-16 13:39:00', '2026-07-16 13:39:00', NULL),
	(27, 'Martín', 'Montalvo', '3967638', '73429467', 'sauceda.isabel@example.com', NULL, 0, '1985-01-25', 'M', NULL, 'Rúa Rendón, 808, Ático 6º', 'Ninguna conocida', '["Diabetes"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-06-05 23:54:00', '2026-06-05 23:54:00', NULL),
	(28, 'Rayan', 'Orosco', '4579629', '71384750', 'ogurule@example.org', NULL, 0, '1994-03-31', 'F', NULL, 'Praza Arias, 216, 5º E', 'Anestesia local', '["Gastritis"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-05-31 20:37:00', '2026-05-31 20:37:00', NULL),
	(29, 'Carolina', 'Véliz', '8612129', '74866368', 'dpastor@example.org', NULL, 0, '2005-03-16', 'F', NULL, 'Plaça Vargas, 488, 4º D', 'Penicilina', '["Ninguna"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-04-02 16:12:00', '2026-04-02 16:12:00', NULL),
	(30, 'Gabriel', 'Aguirre', '8649650', '77308893', 'gabriela.arredondo@example.net', NULL, 0, '1992-07-26', 'F', NULL, 'Avinguda Mejía, 249, 3º', 'Ibuprofeno', '["Hipertension"]', NULL, NULL, NULL, 'Paciente demo generado para el dashboard.', NULL, 1, '2026-03-09 13:36:00', '2026-03-09 13:36:00', NULL);

-- Volcando estructura para tabla saas_odontologia.pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paciente_id` bigint unsigned NOT NULL,
  `presupuesto_id` bigint unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia','qr') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'efectivo',
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pagos_numero_unique` (`numero`),
  KEY `pagos_paciente_id_foreign` (`paciente_id`),
  KEY `pagos_presupuesto_id_foreign` (`presupuesto_id`),
  KEY `pagos_fecha_index` (`fecha`),
  CONSTRAINT `pagos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagos_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.pagos: ~1 rows (aproximadamente)
DELETE FROM `pagos`;
INSERT INTO `pagos` (`id`, `numero`, `paciente_id`, `presupuesto_id`, `fecha`, `monto`, `metodo`, `referencia`, `notas`, `created_at`, `updated_at`) VALUES
	(1, 'REC-000001', 4, 1, '2026-08-05', 148.50, 'transferencia', 'REF-25315', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(2, 'REC-000002', 29, 2, '2026-08-03', 2525.40, 'transferencia', 'REF-14732', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(3, 'REC-000003', 21, 4, '2026-07-30', 2720.00, 'tarjeta', 'REF-97923', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(4, 'REC-000004', 3, 5, '2026-06-27', 4788.00, 'tarjeta', 'REF-38800', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(5, 'REC-000005', 28, 6, '2026-06-24', 2040.00, 'tarjeta', 'REF-39878', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(6, 'REC-000006', 30, 7, '2026-05-22', 1160.00, 'tarjeta', 'REF-92854', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(7, 'REC-000007', 23, 8, '2026-05-01', 1260.00, 'transferencia', 'REF-49501', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(8, 'REC-000008', 28, 9, '2026-04-21', 2014.00, 'efectivo', 'REF-59144', NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(9, 'REC-000009', 21, 10, '2026-05-03', 1850.00, 'tarjeta', 'REF-78276', NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(10, 'REC-000010', 27, 11, '2026-03-31', 1720.00, 'tarjeta', 'REF-40868', NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(11, 'REC-000011', 26, 12, '2026-03-31', 368.00, 'transferencia', 'REF-87099', NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(12, 'REC-000012', 28, 9, '2026-08-10', 312.00, 'tarjeta', 'REF-48186', NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(13, 'REC-000013', 27, 11, '2026-08-10', 1034.00, 'qr', 'REF-24936', NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(14, 'REC-000014', 26, 12, '2026-08-10', 409.00, 'efectivo', 'REF-44518', NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.password_reset_tokens: ~0 rows (aproximadamente)
DELETE FROM `password_reset_tokens`;

-- Volcando estructura para tabla saas_odontologia.presupuestos
CREATE TABLE IF NOT EXISTS `presupuestos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paciente_id` bigint unsigned NOT NULL,
  `doctor_id` bigint unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `estado` enum('borrador','aprobado','rechazado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'borrador',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notas` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presupuestos_codigo_unique` (`codigo`),
  KEY `presupuestos_paciente_id_foreign` (`paciente_id`),
  KEY `presupuestos_doctor_id_foreign` (`doctor_id`),
  KEY `presupuestos_estado_fecha_index` (`estado`,`fecha`),
  CONSTRAINT `presupuestos_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presupuestos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.presupuestos: ~12 rows (aproximadamente)
DELETE FROM `presupuestos`;
INSERT INTO `presupuestos` (`id`, `codigo`, `paciente_id`, `doctor_id`, `fecha`, `estado`, `subtotal`, `descuento`, `total`, `notas`, `created_at`, `updated_at`) VALUES
	(1, 'PRE-000001', 4, 4, '2026-08-04', 'aprobado', 300.00, 30.00, 270.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(2, 'PRE-000002', 29, 5, '2026-08-03', 'aprobado', 6100.00, 610.00, 5490.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(3, 'PRE-000003', 7, 2, '2026-07-13', 'rechazado', 400.00, 0.00, 400.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(4, 'PRE-000004', 21, 5, '2026-07-30', 'aprobado', 2720.00, 0.00, 2720.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(5, 'PRE-000005', 3, 5, '2026-06-23', 'aprobado', 5320.00, 532.00, 4788.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(6, 'PRE-000006', 28, 4, '2026-06-24', 'aprobado', 2040.00, 0.00, 2040.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(7, 'PRE-000007', 30, 12, '2026-05-18', 'aprobado', 1160.00, 0.00, 1160.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(8, 'PRE-000008', 23, 3, '2026-05-01', 'aprobado', 1400.00, 140.00, 1260.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(9, 'PRE-000009', 28, 2, '2026-04-16', 'aprobado', 3800.00, 0.00, 3800.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(10, 'PRE-000010', 21, 10, '2026-04-29', 'aprobado', 1850.00, 0.00, 1850.00, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(11, 'PRE-000011', 27, 4, '2026-03-27', 'aprobado', 1720.00, 0.00, 1720.00, NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(12, 'PRE-000012', 26, 5, '2026-03-27', 'aprobado', 800.00, 0.00, 800.00, NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.presupuesto_items
CREATE TABLE IF NOT EXISTS `presupuesto_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint unsigned NOT NULL,
  `tratamiento_id` bigint unsigned DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int unsigned NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realizado` tinyint(1) NOT NULL DEFAULT '0',
  `realizado_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `presupuesto_items_presupuesto_id_foreign` (`presupuesto_id`),
  KEY `presupuesto_items_tratamiento_id_foreign` (`tratamiento_id`),
  CONSTRAINT `presupuesto_items_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presupuesto_items_tratamiento_id_foreign` FOREIGN KEY (`tratamiento_id`) REFERENCES `tratamientos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.presupuesto_items: ~21 rows (aproximadamente)
DELETE FROM `presupuesto_items`;
INSERT INTO `presupuesto_items` (`id`, `presupuesto_id`, `tratamiento_id`, `descripcion`, `cantidad`, `precio_unitario`, `subtotal`, `realizado`, `realizado_at`, `created_at`, `updated_at`) VALUES
	(1, 1, 21, 'Consulta / Diagnostico', 2, 150.00, 300.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(2, 2, 24, 'Ortodoncia (instalacion)', 1, 2500.00, 2500.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(3, 2, 17, 'Carilla de porcelana', 2, 1800.00, 3600.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(4, 3, 14, 'Control de ortodoncia', 2, 200.00, 400.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(5, 4, 2, 'Radiografia periapical', 1, 80.00, 80.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(6, 4, 20, 'Aplicacion de sellantes (nino)', 2, 120.00, 240.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(7, 4, 10, 'Tratamiento de conducto (multiradicular)', 2, 1200.00, 2400.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(8, 5, 12, 'Extraccion de cordal', 1, 800.00, 800.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(9, 5, 4, 'Aplicacion de fluor', 1, 120.00, 120.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(10, 5, 19, 'Protesis parcial removible', 2, 2200.00, 4400.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(11, 6, 11, 'Extraccion simple', 2, 300.00, 600.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(12, 6, 8, 'Incrustacion', 2, 600.00, 1200.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(13, 6, 4, 'Aplicacion de fluor', 2, 120.00, 240.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(14, 7, 26, 'Curetaje / Periodoncia', 1, 600.00, 600.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(15, 7, 15, 'Curetaje por cuadrante', 1, 400.00, 400.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(16, 7, 2, 'Radiografia periapical', 2, 80.00, 160.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(17, 8, 21, 'Consulta / Diagnostico', 2, 150.00, 300.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(18, 8, 15, 'Curetaje por cuadrante', 2, 400.00, 800.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(19, 8, 11, 'Extraccion simple', 1, 300.00, 300.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(20, 9, 26, 'Curetaje / Periodoncia', 1, 600.00, 600.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(21, 9, 18, 'Corona de porcelana', 2, 1600.00, 3200.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(22, 10, 18, 'Corona de porcelana', 1, 1600.00, 1600.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(23, 10, 6, 'Resina simple', 1, 250.00, 250.00, 0, NULL, '2026-08-10 17:12:58', '2026-08-10 17:12:58'),
	(24, 11, 12, 'Extraccion de cordal', 2, 800.00, 1600.00, 0, NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(25, 11, 4, 'Aplicacion de fluor', 1, 120.00, 120.00, 0, NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59'),
	(26, 12, 12, 'Extraccion de cordal', 1, 800.00, 800.00, 0, NULL, '2026-08-10 17:12:59', '2026-08-10 17:12:59');

-- Volcando estructura para tabla saas_odontologia.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.sessions: ~3 rows (aproximadamente)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('mMR8kZgaEeB36K2ujOUl37id59IyifeyyMYa1pb7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY2kxUTU5amtOc3JzRWFyYmprTENnTEgzejFlSk1NWXlQZzdsekxncCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1786217052),
	('shBYi5hphBjYlGczp0YuXScFAMrprApt6ozOvu73', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTzM5RU5qV0hJa1FLcHVnUUl5U0pvczRnMjZteVhnZkNQYjBHYjU2aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1786378383),
	('X9vFWZIxnzpIiLHZEH2D123cNfc2pPZZgFMRP0Lx', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUFlWQjZhTU03WUVCampGWXlQRHNEUmh4R3lsSXZDUGw4YmYzWWd1TCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODA5MC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1786300230);

-- Volcando estructura para tabla saas_odontologia.tratamientos
CREATE TABLE IF NOT EXISTS `tratamientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `duracion_min` smallint unsigned DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tratamientos_categoria_index` (`categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.tratamientos: ~25 rows (aproximadamente)
DELETE FROM `tratamientos`;
INSERT INTO `tratamientos` (`id`, `nombre`, `categoria`, `descripcion`, `precio`, `duracion_min`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Consulta y diagnostico', 'Diagnostico', NULL, 150.00, 30, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(2, 'Radiografia periapical', 'Diagnostico', NULL, 80.00, 15, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(3, 'Limpieza dental (profilaxis)', 'Preventiva', NULL, 200.00, 40, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(4, 'Aplicacion de fluor', 'Preventiva', NULL, 120.00, 20, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(5, 'Sellado de fosas y fisuras', 'Preventiva', NULL, 100.00, 25, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(6, 'Resina simple', 'Restauracion', NULL, 250.00, 45, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(7, 'Resina compuesta', 'Restauracion', NULL, 350.00, 60, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(8, 'Incrustacion', 'Restauracion', NULL, 600.00, 60, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(9, 'Tratamiento de conducto (1 conducto)', 'Endodoncia', NULL, 700.00, 60, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(10, 'Tratamiento de conducto (multiradicular)', 'Endodoncia', NULL, 1200.00, 90, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(11, 'Extraccion simple', 'Cirugia', NULL, 300.00, 30, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(12, 'Extraccion de cordal', 'Cirugia', NULL, 800.00, 60, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(13, 'Brackets metalicos (instalacion)', 'Ortodoncia', NULL, 2500.00, 90, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(14, 'Control de ortodoncia', 'Ortodoncia', NULL, 200.00, 30, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(15, 'Curetaje por cuadrante', 'Periodoncia', NULL, 400.00, 45, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(16, 'Blanqueamiento dental', 'Estetica', NULL, 1500.00, 75, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(17, 'Carilla de porcelana', 'Estetica', NULL, 1800.00, 90, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(18, 'Corona de porcelana', 'Protesis', NULL, 1600.00, 90, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(19, 'Protesis parcial removible', 'Protesis', NULL, 2200.00, 60, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(20, 'Aplicacion de sellantes (nino)', 'Odontopediatria', NULL, 120.00, 30, 1, '2026-06-16 14:32:55', '2026-06-16 14:32:55'),
	(21, 'Consulta / Diagnostico', 'Diagnostico', NULL, 150.00, 30, 1, '2026-07-02 13:23:45', '2026-07-02 13:23:45'),
	(22, 'Resina / Obturacion', 'Restauracion', NULL, 350.00, 45, 1, '2026-07-02 13:23:45', '2026-07-02 13:23:45'),
	(23, 'Endodoncia unirradicular', 'Endodoncia', NULL, 900.00, 60, 1, '2026-07-02 13:23:45', '2026-07-02 13:23:45'),
	(24, 'Ortodoncia (instalacion)', 'Ortodoncia', NULL, 2500.00, 90, 1, '2026-07-02 13:23:45', '2026-07-02 13:23:45'),
	(25, 'Implante dental', 'Cirugia', NULL, 4500.00, 90, 1, '2026-07-02 13:23:45', '2026-07-02 13:23:45'),
	(26, 'Curetaje / Periodoncia', 'Periodoncia', NULL, 600.00, 45, 1, '2026-07-02 13:23:45', '2026-07-02 13:23:45');

-- Volcando estructura para tabla saas_odontologia.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'recepcion',
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `especialidad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comision` decimal(5,2) NOT NULL DEFAULT '0.00',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_odontologia.users: ~13 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `rol`, `telefono`, `especialidad`, `comision`, `avatar`, `activo`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Sistema', 'admin@odontocrm.com', NULL, '$2y$12$nsUECn0FriqM56rTbloc3uSs4QMtQZxFq7Gswbj.OFuDQxpFY5zVi', 'admin', '70000000', NULL, 0.00, NULL, 1, NULL, '2026-06-16 14:11:05', '2026-06-16 14:14:05'),
	(2, 'Dr. Carlos Rodriguez', 'doctor1@odontocrm.com', NULL, '$2y$12$ghHFKEODr/T9VHnRit5p0.S52dcyfMyamyb6ysihUdQ9LVkgxOOkO', 'doctor', '72186968', 'Ortodoncia', 10.00, NULL, 1, NULL, '2026-06-16 14:11:06', '2026-07-02 13:23:46'),
	(3, 'Dra. Maria Lopez', 'doctor2@odontocrm.com', NULL, '$2y$12$.jbKncd25mK7XMrSAk2vhuyUkvE64t2MrWXXlvP3Bug.RWRf6KJgW', 'doctor', '77682761', 'Endodoncia', 10.00, NULL, 1, NULL, '2026-06-16 14:11:06', '2026-07-02 13:23:46'),
	(4, 'Dr. Juan Perez', 'doctor3@odontocrm.com', NULL, '$2y$12$LdNqpy4i1BZ8ZLx5ov/Kqe0h30.Kd8SeceS9cVsqg3vjhMUIpRF5S', 'doctor', '77521305', 'Cirugia Oral', 10.00, NULL, 1, NULL, '2026-06-16 14:11:06', '2026-07-02 13:23:46'),
	(5, 'Dra. Ana Martinez', 'doctor4@odontocrm.com', NULL, '$2y$12$TDXfriLEwqvD7j4d1sHjmumOtK/BvumhaKZGsiTF2/UB8c7DjBzNG', 'doctor', '72685837', 'Odontopediatria', 10.00, NULL, 1, NULL, '2026-06-16 14:11:06', '2026-07-02 13:23:46'),
	(6, 'Dr. Luis Gomez', 'doctor5@odontocrm.com', NULL, '$2y$12$Xzd1vqAeF4SbdYgefq10Be.G23q1TRdWVt0wdCUl3hehdbwz7PKNG', 'doctor', '74113820', 'Periodoncia', 10.00, NULL, 1, NULL, '2026-06-16 14:11:06', '2026-07-02 13:23:46'),
	(7, 'Dra. Sofia Fernandez', 'doctor6@odontocrm.com', NULL, '$2y$12$0GnqEXRKW6aDLPUWBS.pReQnKvDtSAxDrqP7SJa2s7CAYS5iHxpDq', 'doctor', '75631500', 'Estetica Dental', 10.00, NULL, 1, NULL, '2026-06-16 14:11:07', '2026-07-02 13:23:46'),
	(8, 'Laura Recepcion', 'recepcion@odontocrm.com', NULL, '$2y$12$gR3f0QM9Qhr.WAg9bhYn6u7LXGjh4EpzSXpWEvyLXxTU8WzrSNTOK', 'recepcion', NULL, NULL, 0.00, NULL, 1, NULL, '2026-06-16 14:11:07', '2026-06-16 14:11:07'),
	(9, 'Dr. Pedro Salazar', 'staff1@odontocrm.com', NULL, '$2y$12$BApvLy1DfrTHvjfqtkuxM.cmflJODW6qGybC1XYI.axsWdyz7vAZa', 'doctor', '78476980', 'Implantologia', 12.00, NULL, 1, NULL, '2026-06-16 17:20:16', '2026-08-10 17:12:56'),
	(10, 'Dra. Carmen Vega', 'staff2@odontocrm.com', NULL, '$2y$12$pa/Q8O2cOl.XigkfT0JOT.4j.4t9IJsLlLDIci3hkC3uA2U8.jNPC', 'doctor', '74670805', 'Rehabilitacion Oral', 10.00, NULL, 1, NULL, '2026-06-16 17:20:16', '2026-08-10 17:12:56'),
	(11, 'Dra. Lucia Mendez', 'staff3@odontocrm.com', NULL, '$2y$12$ZbPWPND94oRwJP4mjKhQ1uUxISaauJ2z6.mgTiqYZP/DWn4OKJ2ai', 'doctor', '72710447', 'Ortodoncia', 15.00, NULL, 1, NULL, '2026-06-16 17:20:16', '2026-08-10 17:12:56'),
	(12, 'Dr. Andres Rojas', 'staff4@odontocrm.com', NULL, '$2y$12$0.TF1Y8QyX0MSrI7ZRXrju6W6nnXiPhRINICih2mO64U2FY1oj5Cu', 'doctor', '72162648', 'Endodoncia', 10.00, NULL, 1, NULL, '2026-06-16 17:20:16', '2026-08-10 17:12:57'),
	(13, 'Jorge Receptor', 'staff5@odontocrm.com', NULL, '$2y$12$7bR92XBKt7uIl7BrKfKkzOHmSg4KLz7pmkJgpMIb6xvLMDVvNGsZG', 'recepcion', '73147233', NULL, 0.00, NULL, 1, NULL, '2026-07-02 13:23:46', '2026-08-10 17:12:57');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
