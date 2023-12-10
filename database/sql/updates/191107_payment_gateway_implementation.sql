CREATE TABLE invoice_payment_methods(
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	invoiceID INT(11) NOT NULL,
	meta TEXT,
	insertId INT(11) NOT NULL,
	owner_id INT(11) NOT NULL,
	created_at DATETIME,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE tb_owners ADD payment_gateway_id INT(11) DEFAULT 0 AFTER billplz_collection_id;
ALTER TABLE tb_owners ADD payment_gateway_data TEXT DEFAULT NULL AFTER payment_gateway_id;

DROP TABLE IF EXISTS `payment_gateways`;

CREATE TABLE `payment_gateways` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `payment_gateways` WRITE;
/*!40000 ALTER TABLE `payment_gateways` DISABLE KEYS */;

INSERT INTO `payment_gateways` (`id`, `name`, `meta`, `status`)
VALUES
	(1,'Billplz',NULL,'1'),
	(2,'BayarInd',NULL,'1');
