ALTER TABLE `bookings`
	ADD COLUMN `adult_number` INT NULL DEFAULT '0' AFTER `old_traveller`,
	ADD COLUMN `child_number` INT NULL DEFAULT '0' AFTER `adult_number`,
	ADD COLUMN `infant_number` INT NULL DEFAULT '0' AFTER `child_number`;
ALTER TABLE `tb_owners`
	CHANGE COLUMN `booking_form` `booking_form` INT(11) NULL DEFAULT '2' AFTER `theme`;
