CREATE TABLE `flight_booking` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`departure_date` DATE NOT NULL,
	`pnr` VARCHAR(50) NULL DEFAULT NULL,
	`pax` INT NOT NULL,
	`status` TINYINT NOT NULL DEFAULT '1',
	`tourdates_id` INT NULL DEFAULT NULL,
	`flight_match_id` INT NOT NULL,
	`entry_by` INT NULL DEFAULT NULL,
	`owner_id` INT NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
);
