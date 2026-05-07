/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 8.0.30 : Database - credito
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`credito` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `credito`;

/*Table structure for table `administradores` */

DROP TABLE IF EXISTS `administradores`;

CREATE TABLE `administradores` (
  `id_administrador` int NOT NULL AUTO_INCREMENT,
  `sociedad` int DEFAULT NULL,
  `persona` int DEFAULT NULL,
  PRIMARY KEY (`id_administrador`),
  KEY `administrador_persona` (`persona`),
  KEY `unique_encargado` (`sociedad`,`persona`),
  CONSTRAINT `administrador_persona` FOREIGN KEY (`persona`) REFERENCES `personas` (`id_persona`),
  CONSTRAINT `administrador_sociedad` FOREIGN KEY (`sociedad`) REFERENCES `sociedades` (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `administradores` */

LOCK TABLES `administradores` WRITE;

insert  into `administradores`(`id_administrador`,`sociedad`,`persona`) values (19,11,27),(21,11,30),(26,13,31),(27,14,30);

UNLOCK TABLES;

/*Table structure for table `cuotas` */

DROP TABLE IF EXISTS `cuotas`;

CREATE TABLE `cuotas` (
  `id_cuota` int NOT NULL AUTO_INCREMENT,
  `fecha_pago` date DEFAULT NULL,
  `fecha_recaudo` date DEFAULT NULL,
  `nro_cuota` int DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `tipo` enum('cuota_fija','interes_mensual','capital') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `prestamo` int DEFAULT NULL,
  `estado` enum('pendiente','pagado') DEFAULT NULL,
  `movimiento` int DEFAULT NULL,
  PRIMARY KEY (`id_cuota`),
  UNIQUE KEY `unico_mes_cuota` (`nro_cuota`,`prestamo`),
  KEY `prestamo_cuota` (`prestamo`),
  KEY `movimientos_cuota` (`movimiento`),
  CONSTRAINT `movimientos_cuota` FOREIGN KEY (`movimiento`) REFERENCES `movimientos` (`id_movimiento`),
  CONSTRAINT `prestamo_cuota` FOREIGN KEY (`prestamo`) REFERENCES `prestamos` (`id_prestamo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `cuotas` */

LOCK TABLES `cuotas` WRITE;

insert  into `cuotas`(`id_cuota`,`fecha_pago`,`fecha_recaudo`,`nro_cuota`,`valor`,`tipo`,`prestamo`,`estado`,`movimiento`) values (456,'2026-05-15','2026-04-23',1,9.6,'interes_mensual',75,'pagado',81),(458,'2026-07-15','2026-04-01',3,9.6,'interes_mensual',75,'pagado',99),(459,'2026-08-22',NULL,4,9.6,'interes_mensual',75,'pendiente',NULL),(460,'2026-09-15',NULL,5,9.6,'interes_mensual',75,'pendiente',NULL),(461,'2026-09-15',NULL,6,240,'capital',75,'pendiente',NULL),(462,'2026-05-22',NULL,1,150,'interes_mensual',76,'pendiente',NULL),(463,'2026-06-22',NULL,2,150,'interes_mensual',76,'pendiente',NULL),(464,'2026-07-22',NULL,3,150,'interes_mensual',76,'pendiente',NULL),(465,'2026-08-22',NULL,4,150,'interes_mensual',76,'pendiente',NULL),(466,'2026-09-22',NULL,5,150,'interes_mensual',76,'pendiente',NULL),(467,'2026-09-22',NULL,6,5000,'capital',76,'pendiente',NULL),(468,'2026-05-22','2026-04-23',1,2100,'interes_mensual',77,'pagado',78),(469,'2026-06-22',NULL,2,2100,'interes_mensual',77,'pendiente',NULL),(470,'2026-07-22',NULL,3,2100,'interes_mensual',77,'pendiente',NULL),(471,'2026-08-22',NULL,4,2100,'interes_mensual',77,'pendiente',NULL),(472,'2026-09-22',NULL,5,2100,'interes_mensual',77,'pendiente',NULL),(473,'2026-10-22',NULL,6,2100,'interes_mensual',77,'pendiente',NULL),(474,'2026-11-22',NULL,7,2100,'interes_mensual',77,'pendiente',NULL),(475,'2026-11-22',NULL,8,70000,'capital',77,'pendiente',NULL),(476,'2026-04-22',NULL,7,3500,'cuota_fija',75,'pendiente',NULL),(477,'2026-05-14','2026-04-26',1,102,'interes_mensual',78,'pagado',86),(478,'2026-06-14',NULL,2,102,'interes_mensual',78,'pendiente',NULL),(479,'2026-07-14',NULL,3,102,'interes_mensual',78,'pendiente',NULL),(480,'2026-07-14',NULL,4,3400,'capital',78,'pendiente',NULL),(481,'2026-05-27',NULL,1,15,'interes_mensual',79,'pendiente',NULL),(482,'2026-06-27',NULL,2,15,'interes_mensual',79,'pendiente',NULL),(483,'2026-07-27',NULL,3,15,'interes_mensual',79,'pendiente',NULL),(484,'2026-07-27',NULL,4,500,'capital',79,'pendiente',NULL),(485,'2026-05-28',NULL,1,15,'interes_mensual',80,'pendiente',NULL),(486,'2026-06-28',NULL,2,15,'interes_mensual',80,'pendiente',NULL),(487,'2026-07-28',NULL,3,15,'interes_mensual',80,'pendiente',NULL),(488,'2026-07-28',NULL,4,500,'capital',80,'pendiente',NULL),(489,'2026-05-27',NULL,1,15,'interes_mensual',81,'pendiente',NULL),(490,'2026-06-27',NULL,2,15,'interes_mensual',81,'pendiente',NULL),(491,'2026-07-27',NULL,3,15,'interes_mensual',81,'pendiente',NULL),(492,'2026-07-27',NULL,4,500,'capital',81,'pendiente',NULL),(493,'2026-05-20',NULL,1,15,'interes_mensual',83,'pendiente',NULL),(494,'2026-06-20',NULL,2,15,'interes_mensual',83,'pendiente',NULL),(495,'2026-07-20',NULL,3,15,'interes_mensual',83,'pendiente',NULL),(496,'2026-07-20',NULL,4,500,'capital',83,'pendiente',NULL),(497,'2026-05-27',NULL,1,15,'interes_mensual',84,'pendiente',NULL),(498,'2026-06-27',NULL,2,15,'interes_mensual',84,'pendiente',NULL),(499,'2026-07-27',NULL,3,15,'interes_mensual',84,'pendiente',NULL),(500,'2026-07-27',NULL,4,500,'capital',84,'pendiente',NULL),(501,'2026-05-27',NULL,1,3,'interes_mensual',86,'pendiente',NULL),(502,'2026-06-27',NULL,2,3,'interes_mensual',86,'pendiente',NULL),(503,'2026-07-27',NULL,3,3,'interes_mensual',86,'pendiente',NULL),(504,'2026-07-27',NULL,4,100,'capital',86,'pendiente',NULL);

UNLOCK TABLES;

/*Table structure for table `garantias` */

DROP TABLE IF EXISTS `garantias`;

CREATE TABLE `garantias` (
  `id_garantia` int NOT NULL AUTO_INCREMENT,
  `tipo` int DEFAULT NULL,
  `ruta` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `prestamo` int DEFAULT NULL,
  PRIMARY KEY (`id_garantia`),
  KEY `prestamos_archivo` (`prestamo`),
  KEY `archivo_tipo_archivo` (`tipo`),
  CONSTRAINT `archivo_tipo_archivo` FOREIGN KEY (`tipo`) REFERENCES `tipo_garantia` (`id_tipo_garantia`),
  CONSTRAINT `prestamos_archivo` FOREIGN KEY (`prestamo`) REFERENCES `prestamos` (`id_prestamo`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `garantias` */

LOCK TABLES `garantias` WRITE;

insert  into `garantias`(`id_garantia`,`tipo`,`ruta`,`prestamo`) values (26,1,'../../uploads/garantias/1777341418_img20250611_16472188.pdf',75);

UNLOCK TABLES;

/*Table structure for table `gastos` */

DROP TABLE IF EXISTS `gastos`;

CREATE TABLE `gastos` (
  `id_gasto` int NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `detalle` varchar(100) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `movimiento` int DEFAULT NULL,
  `estado` enum('ejecutado','anulado') DEFAULT 'ejecutado',
  PRIMARY KEY (`id_gasto`),
  KEY `movimiento_gasto` (`movimiento`),
  CONSTRAINT `movimiento_gasto` FOREIGN KEY (`movimiento`) REFERENCES `movimientos` (`id_movimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `gastos` */

LOCK TABLES `gastos` WRITE;

insert  into `gastos`(`id_gasto`,`fecha`,`detalle`,`valor`,`movimiento`,`estado`) values (12,'2026-04-22','nnn',344,69,'ejecutado'),(13,'2026-04-22','Factura de gas',1000,82,'anulado'),(14,'2026-04-15','1414',200,83,'anulado'),(15,'2026-04-22','gfgfg',300,84,'anulado'),(16,'2026-04-27','factura ada',300,98,'ejecutado');

UNLOCK TABLES;

/*Table structure for table `movimientos` */

DROP TABLE IF EXISTS `movimientos`;

CREATE TABLE `movimientos` (
  `id_movimiento` int NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `sociedad` int DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `caja` float DEFAULT NULL,
  `tipo` enum('credito','gasto','adicion','cuota','devolucion') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estado` enum('ejecutado','anulado') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'ejecutado',
  PRIMARY KEY (`id_movimiento`),
  KEY `sociedad_movimientos` (`sociedad`),
  CONSTRAINT `sociedad_movimientos` FOREIGN KEY (`sociedad`) REFERENCES `sociedades` (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `movimientos` */

LOCK TABLES `movimientos` WRITE;

insert  into `movimientos`(`id_movimiento`,`fecha`,`sociedad`,`valor`,`caja`,`tipo`,`estado`) values (69,'2026-04-08',11,500000,500000,'adicion','anulado'),(70,'2026-04-15',11,240,499760,'credito','ejecutado'),(71,'2026-04-22',12,200,200,'adicion','ejecutado'),(72,'2026-04-22',11,5000,494760,'credito','ejecutado'),(73,'2026-04-22',11,70000,424760,'credito','ejecutado'),(74,'2026-04-22',11,9.6,424770,'cuota','ejecutado'),(75,'2026-04-22',11,9.6,424760,'devolucion','anulado'),(76,'2026-04-22',11,9.6,424770,'cuota','ejecutado'),(77,'2026-04-22',11,9.6,424780,'cuota','ejecutado'),(78,'2026-04-23',11,2100,426880,'cuota','ejecutado'),(79,'2026-04-23',11,9.6,426870,'devolucion','anulado'),(80,'2026-04-23',11,9.6,426860,'devolucion','anulado'),(81,'2026-04-23',11,9.6,426870,'cuota','ejecutado'),(82,'2026-04-22',11,1000,925870,'gasto','anulado'),(83,'2026-04-15',11,200,926670,'gasto','anulado'),(84,'2026-04-22',11,300,926570,'gasto','anulado'),(85,'2026-04-14',11,3400,923470,'credito','ejecutado'),(86,'2026-04-27',11,102,923572,'cuota','ejecutado'),(87,'2026-04-27',13,500,500,'adicion','ejecutado'),(88,'2026-04-27',13,100,600,'adicion','ejecutado'),(89,'2026-04-27',14,1000,1000,'adicion','ejecutado'),(90,'2026-04-27',11,500,923072,'credito','ejecutado'),(91,'2026-04-28',11,500,922572,'credito','ejecutado'),(92,'2026-04-27',11,500,922072,'credito','ejecutado'),(94,'2026-04-20',11,500,921572,'credito','ejecutado'),(95,'2026-04-27',14,500,921072,'credito','ejecutado'),(97,'2026-04-27',14,100,900,'credito','ejecutado'),(98,'2026-04-27',14,300,600,'gasto','ejecutado'),(99,'2026-04-28',11,9.6,921082,'cuota','ejecutado');

UNLOCK TABLES;

/*Table structure for table `personas` */

DROP TABLE IF EXISTS `personas`;

CREATE TABLE `personas` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `identificacion` bigint NOT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `calificacion` enum('1','2','3','4','5','6','7','8','9','10') DEFAULT NULL,
  `rol` enum('Admin','Usuario','Socio') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Usuario',
  `observacion` varchar(50) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Activo',
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `unico_persona` (`identificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `personas` */

LOCK TABLES `personas` WRITE;

insert  into `personas`(`id_persona`,`identificacion`,`nombres`,`direccion`,`telefono`,`calificacion`,`rol`,`observacion`,`token`,`password`,`estado`) values (27,96361787,'Wilson Martinez Saldarriaga','Pitalito','3167512637','10','Admin','nnn','6e56d31ef9f47493183b26437f9a4f055a64b2a65918113e2058383a5e254b20','96361787','Inactivo'),(30,96361047,'Wilmer Martinez Saldarriaga','Cra 2 # 3-21','3212222','10','Socio','N/A','fc84a2d6a61213676ddb845ca02fbbae855f82b360df31850198996585e0adf1','12345','Inactivo'),(31,11,'nnnn','11','11','1','Socio','11',NULL,'123456','Activo');

UNLOCK TABLES;

/*Table structure for table `prestamos` */

DROP TABLE IF EXISTS `prestamos`;

CREATE TABLE `prestamos` (
  `id_prestamo` int NOT NULL AUTO_INCREMENT,
  `ficha` int DEFAULT NULL,
  `fecha_prestamo` date DEFAULT NULL,
  `interes` float DEFAULT NULL,
  `tiempo` int DEFAULT NULL,
  `valor_prestado` float DEFAULT NULL,
  `valor_futuro` float DEFAULT NULL,
  `tipo` enum('financiado','mensual') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `persona` int DEFAULT NULL,
  `movimiento` int DEFAULT NULL,
  `fiador` int DEFAULT NULL,
  `estado` enum('aprobado','negado','finalizado') DEFAULT 'aprobado',
  PRIMARY KEY (`id_prestamo`),
  UNIQUE KEY `unique` (`ficha`),
  KEY `persona_prestamo` (`persona`),
  KEY `prestamos_movimientos` (`movimiento`),
  CONSTRAINT `persona_prestamo` FOREIGN KEY (`persona`) REFERENCES `personas` (`id_persona`),
  CONSTRAINT `prestamos_movimientos` FOREIGN KEY (`movimiento`) REFERENCES `movimientos` (`id_movimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `prestamos` */

LOCK TABLES `prestamos` WRITE;

insert  into `prestamos`(`id_prestamo`,`ficha`,`fecha_prestamo`,`interes`,`tiempo`,`valor_prestado`,`valor_futuro`,`tipo`,`persona`,`movimiento`,`fiador`,`estado`) values (75,22222,'2026-04-15',4,5,240,288,'mensual',27,70,27,'aprobado'),(76,344,'2026-04-22',3,5,5000,5750,'mensual',30,72,27,'aprobado'),(77,777,'2026-04-22',3,7,70000,84700,'mensual',30,73,27,'aprobado'),(78,44444,'2026-04-14',3,3,3400,3706,'mensual',27,85,27,'aprobado'),(79,2365,'2026-04-27',3,3,500,545,'mensual',31,90,27,'aprobado'),(80,354,'2026-04-28',3,3,500,545,'mensual',31,91,27,'aprobado'),(81,500,'2026-04-27',3,3,500,545,'mensual',30,92,27,'aprobado'),(83,300,'2026-04-20',3,3,500,545,'mensual',30,94,27,'aprobado'),(84,999,'2026-04-27',3,3,500,545,'mensual',30,95,27,'aprobado'),(86,657,'2026-04-27',3,3,100,109,'mensual',30,97,27,'aprobado');

UNLOCK TABLES;

/*Table structure for table `sociedades` */

DROP TABLE IF EXISTS `sociedades`;

CREATE TABLE `sociedades` (
  `id_sociedad` int NOT NULL AUTO_INCREMENT,
  `sociedad` varchar(50) DEFAULT NULL,
  `caja` float DEFAULT NULL,
  PRIMARY KEY (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `sociedades` */

LOCK TABLES `sociedades` WRITE;

insert  into `sociedades`(`id_sociedad`,`sociedad`,`caja`) values (11,'CMY',921082),(12,'nnn',200),(13,'MYC',600),(14,'DMG',600);

UNLOCK TABLES;

/*Table structure for table `tipo_garantia` */

DROP TABLE IF EXISTS `tipo_garantia`;

CREATE TABLE `tipo_garantia` (
  `id_tipo_garantia` int NOT NULL AUTO_INCREMENT,
  `nombre_tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id_tipo_garantia`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tipo_garantia` */

LOCK TABLES `tipo_garantia` WRITE;

insert  into `tipo_garantia`(`id_tipo_garantia`,`nombre_tipo`) values (1,'Cedula Cliente'),(2,'Cedula del Fiador'),(3,'Certificado de Tradición y Libertad'),(4,'Copia de escritura'),(5,'Foto del Predio'),(6,'Foto del Cliente'),(7,'Recibo de Servicio Publico'),(8,'Paz y Salvo de Impuesto'),(9,'Archivo de Proceso');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
