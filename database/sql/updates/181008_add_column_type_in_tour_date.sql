ALTER TABLE `tour_date`
	ADD COLUMN `type` TINYINT(1) NULL DEFAULT '1' AFTER `status`;