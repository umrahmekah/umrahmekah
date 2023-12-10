ALTER TABLE `tours`
	ADD COLUMN `landing_image` VARCHAR(255) NULL DEFAULT NULL AFTER `tourimage`,
	ADD COLUMN `view_image` VARCHAR(255) NULL DEFAULT NULL AFTER `landing_image`;
