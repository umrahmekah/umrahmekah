ALTER TABLE `flight_matching`
	ALTER `flight_number_1` DROP DEFAULT,
	ALTER `sector_1` DROP DEFAULT,
	ALTER `day_1` DROP DEFAULT,
	ALTER `dep_time_1` DROP DEFAULT,
	ALTER `arr_time_1` DROP DEFAULT;
ALTER TABLE `flight_matching`
	CHANGE COLUMN `flight_number_1` `flight_number` VARCHAR(50) NOT NULL AFTER `id`,
	CHANGE COLUMN `sector_1` `sector` VARCHAR(50) NOT NULL AFTER `flight_number`,
	CHANGE COLUMN `day_1` `day` VARCHAR(50) NOT NULL AFTER `sector`,
	CHANGE COLUMN `dep_time_1` `dep_time` VARCHAR(50) NOT NULL AFTER `day`,
	CHANGE COLUMN `arr_time_1` `arr_time` VARCHAR(50) NOT NULL AFTER `dep_time`,
	ADD COLUMN `type` TINYINT NOT NULL AFTER `arr_time`,
	DROP COLUMN `flight_number_2`,
	DROP COLUMN `sector_2`,
	DROP COLUMN `day_2`,
	DROP COLUMN `dep_time_2`,
	DROP COLUMN `arr_time_2`,
	DROP COLUMN `number_of_days`;
