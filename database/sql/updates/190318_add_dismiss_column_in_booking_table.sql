ALTER TABLE `bookings`
	ADD COLUMN `dismissed` TINYINT NULL DEFAULT '0' AFTER `infant_number`;
