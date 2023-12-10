ALTER TABLE `flight_booking`
	ADD COLUMN `return_date` DATE NOT NULL AFTER `departure_date`,
	CHANGE COLUMN `flight_match_id` `flight_match_depart_id` INT(11) NULL DEFAULT NULL AFTER `tourdates_id`,
	ADD COLUMN `flight_match_return_id` INT(11) NULL DEFAULT NULL AFTER `flight_match_depart_id`,
	ADD COLUMN `payment_due` DATE NULL DEFAULT NULL AFTER `flight_match_return_id`,
	ADD COLUMN `email` VARCHAR(50) NULL DEFAULT NULL AFTER `return_date`;
