ALTER TABLE `flight_date`
	ADD COLUMN `entry_by` INT NULL AFTER `email`;
ALTER TABLE `flight_date`
	ADD COLUMN `owner_id` INT(11) NULL DEFAULT NULL AFTER `entry_by`;
ALTER TABLE `flight_matching`
	ADD COLUMN `entry_by` INT NULL DEFAULT NULL AFTER `flight_date`,
	ADD COLUMN `owner_id` INT NULL DEFAULT NULL AFTER `entry_by`;
