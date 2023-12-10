CREATE TABLE `flight_matching` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`flight_number_1` VARCHAR(50) NOT NULL,
	`sector_1` VARCHAR(50) NOT NULL,
	`day_1` VARCHAR(50) NOT NULL,
	`dep_time_1` VARCHAR(50) NOT NULL,
	`arr_time_1` VARCHAR(50) NOT NULL,
	`flight_number_2` VARCHAR(50) NOT NULL,
	`sector_2` VARCHAR(50) NOT NULL,
	`day_2` VARCHAR(50) NOT NULL,
	`dep_time_2` VARCHAR(50) NOT NULL,
	`arr_time_2` VARCHAR(50) NOT NULL,
	`number_of_days` VARCHAR(50) NOT NULL,
	`flight_date` INT NOT NULL,
	PRIMARY KEY (`id`)
);
