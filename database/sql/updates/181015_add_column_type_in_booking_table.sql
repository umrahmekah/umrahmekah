ALTER TABLE `bookings`
	ADD COLUMN `type` INT(11) NULL DEFAULT '1' AFTER `balance`;