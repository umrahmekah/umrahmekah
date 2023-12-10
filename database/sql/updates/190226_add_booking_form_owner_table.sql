ALTER TABLE `tb_owners`
	CHANGE COLUMN `theme` `theme` VARCHAR(11) NULL DEFAULT 'blue-ocean' AFTER `captcha`,
	ADD COLUMN `booking_form` INT NULL DEFAULT '1' AFTER `theme`;
