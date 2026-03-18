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

/*Table structure for table `cuotas` */

DROP TABLE IF EXISTS `cuotas`;

CREATE TABLE `cuotas` (
  `id_cuota` int NOT NULL AUTO_INCREMENT,
  `fecha_cuota` date DEFAULT NULL,
  `mes` int DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `tipo` enum('cuota_fija','interes_mensual','capital') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `prestamo` int DEFAULT NULL,
  `estado` enum('pendiente','pagado') DEFAULT NULL,
  PRIMARY KEY (`id_cuota`),
  KEY `prestamo_cuota` (`prestamo`),
  CONSTRAINT `prestamo_cuota` FOREIGN KEY (`prestamo`) REFERENCES `prestamos` (`id_prestamo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=348 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `cuotas` */

LOCK TABLES `cuotas` WRITE;

insert  into `cuotas`(`id_cuota`,`fecha_cuota`,`mes`,`valor`,`tipo`,`prestamo`,`estado`) values (228,'2026-02-18',1,150000,'cuota_fija',48,'pendiente'),(229,'2026-04-18',2,150000,'cuota_fija',48,'pagado'),(230,'2026-05-18',3,150000,'cuota_fija',48,'pagado'),(231,'2026-06-18',4,150000,'cuota_fija',48,'pagado'),(232,'2026-03-02',1,125000,'cuota_fija',42,'pagado'),(233,'2026-04-02',2,125000,'cuota_fija',42,'pagado'),(234,'2026-05-02',3,125000,'cuota_fija',42,'pendiente'),(235,'2026-06-02',4,125000,'cuota_fija',42,'pendiente'),(236,'2026-07-02',5,125000,'cuota_fija',42,'pendiente'),(248,'2026-03-25',1,15000,'interes_mensual',49,'pagado'),(249,'2026-04-25',2,15000,'interes_mensual',49,'pagado'),(250,'2026-05-25',3,15000,'interes_mensual',49,'pagado'),(251,'2026-06-25',4,15000,'interes_mensual',49,'pendiente'),(252,'2026-07-25',5,15000,'interes_mensual',49,'pendiente'),(253,'2026-08-25',6,15000,'interes_mensual',49,'pendiente'),(254,'2026-09-25',7,15000,'interes_mensual',49,'pendiente'),(255,'2026-10-25',8,15000,'interes_mensual',49,'pendiente'),(256,'2026-11-25',9,15000,'interes_mensual',49,'pendiente'),(257,'2026-12-25',10,15000,'interes_mensual',49,'pendiente'),(258,'2026-12-25',10,300000,'capital',49,'pendiente'),(295,'2026-04-05',1,13500,'interes_mensual',50,'pagado'),(296,'2026-05-05',2,13500,'interes_mensual',50,'pendiente'),(297,'2026-06-05',3,13500,'interes_mensual',50,'pendiente'),(298,'2026-07-05',4,13500,'interes_mensual',50,'pendiente'),(299,'2026-07-05',4,300000,'capital',50,'pendiente'),(330,'2026-04-01',1,100000,'cuota_fija',51,'pendiente'),(331,'2026-05-01',2,100000,'cuota_fija',51,'pendiente'),(332,'2026-06-01',3,100000,'cuota_fija',51,'pendiente'),(333,'2026-07-01',4,100000,'cuota_fija',51,'pendiente'),(334,'2026-08-01',5,100000,'cuota_fija',51,'pendiente'),(343,'2026-03-02',1,35000,'interes_mensual',41,'pagado'),(344,'2026-04-02',2,35000,'interes_mensual',41,'pagado'),(345,'2026-05-02',3,35000,'interes_mensual',41,'pendiente'),(346,'2026-06-02',4,35000,'interes_mensual',41,'pendiente'),(347,'2026-06-02',4,700000,'capital',41,'pendiente');

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `garantias` */

LOCK TABLES `garantias` WRITE;

insert  into `garantias`(`id_garantia`,`tipo`,`ruta`,`prestamo`) values (4,1,'../../uploads/garantias/1772982797_mapa mental.pdf',50),(5,1,'../../uploads/garantias/1772982875_mapa mental.pdf',50),(6,1,'../../uploads/garantias/1772983357_mapa mental.pdf',50),(7,1,'../../uploads/garantias/1772983464_mapa mental.pdf',50),(17,4,'../../uploads/garantias/1772987605_mapa mental.pdf',41),(19,3,'../../uploads/garantias/1773344449_mapa mental.pdf',41);

UNLOCK TABLES;

/*Table structure for table `gastos` */

DROP TABLE IF EXISTS `gastos`;

CREATE TABLE `gastos` (
  `id_gasto` int NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `detalle` varchar(100) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `sociedad` int DEFAULT NULL,
  PRIMARY KEY (`id_gasto`),
  KEY `gastos_sociedad` (`sociedad`),
  CONSTRAINT `gastos_sociedad` FOREIGN KEY (`sociedad`) REFERENCES `sociedades` (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `gastos` */

LOCK TABLES `gastos` WRITE;

insert  into `gastos`(`id_gasto`,`fecha`,`detalle`,`valor`,`sociedad`) values (2,'2026-02-25','FACTURA',100000,1),(5,'2026-02-25','facturasssss',3200000,1);

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
  `rol` enum('Admin','Usuario') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Usuario',
  `observacion` varchar(50) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Activo',
  PRIMARY KEY (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `personas` */

LOCK TABLES `personas` WRITE;

insert  into `personas`(`id_persona`,`identificacion`,`nombres`,`direccion`,`telefono`,`calificacion`,`rol`,`observacion`,`token`,`password`,`estado`) values (12,34234,'Junito','asdsa','3112323232','6','Usuario','DFFDFG GDFGGDF',NULL,NULL,'Inactivo'),(13,343423,NULL,'asddsa','23424',NULL,'Usuario',NULL,NULL,NULL,'Inactivo'),(16,22233,'fsddfsf','cra 23 clle 2','3167512637',NULL,'Usuario',NULL,NULL,NULL,'Inactivo'),(18,565656,'5656','5656','65656',NULL,'Usuario',NULL,NULL,NULL,'Inactivo'),(19,34343,'343','434','3434',NULL,'Usuario',NULL,NULL,NULL,'Activo'),(20,323,'2323','232','232',NULL,'Usuario',NULL,NULL,NULL,'Activo'),(21,434,'434','343','3434','5','Usuario','df fdfdafadsfdsaf',NULL,NULL,'Activo'),(22,8888,'88','88','88',NULL,'Usuario',NULL,NULL,NULL,'Activo'),(23,999,'999','99','99',NULL,'Usuario',NULL,NULL,NULL,'Inactivo'),(24,777,'777','77','77',NULL,'Usuario',NULL,NULL,NULL,'Inactivo'),(25,5555,'5555','5555','5555',NULL,'Usuario',NULL,NULL,NULL,'Activo'),(26,333,'333','33','33','5','Usuario','Hola',NULL,NULL,'Activo'),(27,96361787,'Wilson Martinez S.','Pitalito','3167512637','10','Admin',NULL,'10d3b6152b93326d46e128e454267bf3aa78b11c6c4870fb993d492e530dd656','96361787','Activo');

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
  `tipo` enum('financiado','mensual') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `persona` int DEFAULT NULL,
  `sociedad` int DEFAULT NULL,
  `fiador` int DEFAULT NULL,
  `estado` enum('aprobado','negado','finalizado') DEFAULT 'aprobado',
  PRIMARY KEY (`id_prestamo`),
  UNIQUE KEY `unique` (`ficha`),
  KEY `persona_prestamo` (`persona`),
  KEY `sociedad_prestamo` (`sociedad`),
  CONSTRAINT `persona_prestamo` FOREIGN KEY (`persona`) REFERENCES `personas` (`id_persona`),
  CONSTRAINT `sociedad_prestamo` FOREIGN KEY (`sociedad`) REFERENCES `sociedades` (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `prestamos` */

LOCK TABLES `prestamos` WRITE;

insert  into `prestamos`(`id_prestamo`,`ficha`,`fecha_prestamo`,`interes`,`tiempo`,`valor_prestado`,`tipo`,`persona`,`sociedad`,`fiador`,`estado`) values (41,101,'2026-02-02',5,4,700000,'mensual',12,1,12,'aprobado'),(42,201,'2026-02-02',5,5,500000,'financiado',12,1,25,'aprobado'),(48,4555,'2026-02-18',5,4,500000,'financiado',12,1,22,'aprobado'),(49,9632,'2026-02-25',5,10,300000,'mensual',12,1,16,'negado'),(50,409,'2026-03-05',4.5,4,300000,'mensual',16,1,27,'aprobado'),(51,9633,'2026-03-01',5,5,400000,'financiado',12,2,12,'negado');

UNLOCK TABLES;

/*Table structure for table `sociedades` */

DROP TABLE IF EXISTS `sociedades`;

CREATE TABLE `sociedades` (
  `id_sociedad` int NOT NULL AUTO_INCREMENT,
  `sociedad` varchar(50) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  PRIMARY KEY (`id_sociedad`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `sociedades` */

LOCK TABLES `sociedades` WRITE;

insert  into `sociedades`(`id_sociedad`,`sociedad`,`valor`) values (1,'SYC',30000000),(2,'MYC',100000),(3,'RTTTT',530000000);

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
