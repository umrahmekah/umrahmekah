ALTER TABLE `invoice`
	ADD COLUMN `discount_by` INT(11) NULL DEFAULT NULL AFTER `entry_by`;
