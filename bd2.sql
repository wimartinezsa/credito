/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 5.5.5-10.4.32-MariaDB : Database - credito
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`credito` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `credito`;

/*Table structure for table `garantias` */

DROP TABLE IF EXISTS `garantias`;

CREATE TABLE `garantias` (
  `id_garantia` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(11) DEFAULT NULL,
  `ruta` varchar(300) DEFAULT NULL,
  `prestamo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_garantia`),
  KEY `prestamos_archivo` (`prestamo`),
  KEY `archivo_tipo_archivo` (`tipo`),
  CONSTRAINT `archivo_tipo_archivo` FOREIGN KEY (`tipo`) REFERENCES `tipo_garantia` (`id_tipo_garantia`),
  CONSTRAINT `prestamos_archivo` FOREIGN KEY (`prestamo`) REFERENCES `prestamos` (`id_prestamo`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `garantias` */

LOCK TABLES `garantias` WRITE;

UNLOCK TABLES;

/*Table structure for table `gastos` */

DROP TABLE IF EXISTS `gastos`;

CREATE TABLE `gastos` (
  `id_gasto` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `detalle` varchar(100) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `sociedad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_gasto`),
  KEY `gastos_sociedad` (`sociedad`),
  CONSTRAINT `gastos_sociedad` FOREIGN KEY (`sociedad`) REFERENCES `sociedades` (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `gastos` */

LOCK TABLES `gastos` WRITE;

UNLOCK TABLES;

/*Table structure for table `personas` */

DROP TABLE IF EXISTS `personas`;

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL AUTO_INCREMENT,
  `identificacion` bigint(20) NOT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `calificacion` enum('1','2','3','4','5','6','7','8','9','10') DEFAULT NULL,
  `rol` enum('Admin','Usuario') DEFAULT 'Usuario',
  `observacion` varchar(50) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `personas` */

LOCK TABLES `personas` WRITE;

insert  into `personas`(`id_persona`,`identificacion`,`nombres`,`direccion`,`telefono`,`calificacion`,`rol`,`observacion`,`token`,`password`,`estado`) values (1,96361787,'Wilson Martinez','Pitalito','3167512637','9','Admin',NULL,NULL,'96361787','Activo');

UNLOCK TABLES;

/*Table structure for table `prestamos` */

DROP TABLE IF EXISTS `prestamos`;

CREATE TABLE `prestamos` (
  `id_prestamo` int(11) NOT NULL AUTO_INCREMENT,
  `ficha` int(11) DEFAULT NULL,
  `fecha_prestamo` date DEFAULT NULL,
  `interes` float DEFAULT NULL,
  `tiempo` int(11) DEFAULT NULL,
  `valor_prestado` float DEFAULT NULL,
  `tipo` enum('financiado','mensual') DEFAULT NULL,
  `persona` int(11) DEFAULT NULL,
  `sociedad` int(11) DEFAULT NULL,
  `fiador` int(11) DEFAULT NULL,
  `estado` enum('aprobado','negado','finalizado') DEFAULT 'aprobado',
  PRIMARY KEY (`id_prestamo`),
  UNIQUE KEY `unique` (`ficha`),
  KEY `persona_prestamo` (`persona`),
  KEY `sociedad_prestamo` (`sociedad`),
  CONSTRAINT `persona_prestamo` FOREIGN KEY (`persona`) REFERENCES `personas` (`id_persona`),
  CONSTRAINT `sociedad_prestamo` FOREIGN KEY (`sociedad`) REFERENCES `sociedades` (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `prestamos` */

LOCK TABLES `prestamos` WRITE;

UNLOCK TABLES;

/*Table structure for table `sociedades` */

DROP TABLE IF EXISTS `sociedades`;

CREATE TABLE `sociedades` (
  `id_sociedad` int(11) NOT NULL AUTO_INCREMENT,
  `sociedad` varchar(50) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  PRIMARY KEY (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `sociedades` */

LOCK TABLES `sociedades` WRITE;

insert  into `sociedades`(`id_sociedad`,`sociedad`,`valor`) values (1,'SYC',1000000),(2,'MYC',100000),(3,'RTTTT',530000000);

UNLOCK TABLES;

/*Table structure for table `tipo_garantia` */

DROP TABLE IF EXISTS `tipo_garantia`;

CREATE TABLE `tipo_garantia` (
  `id_tipo_garantia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_tipo_garantia`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tipo_garantia` */

LOCK TABLES `tipo_garantia` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
