ALTER TABLE `bookings`
	ADD COLUMN `source` TINYINT(4) NULL DEFAULT '0' AFTER `dismissed`;
ALTER TABLE `bookings`
	CHANGE COLUMN `source` `source_id` TINYINT(4) NULL DEFAULT '0' AFTER `dismissed`;
