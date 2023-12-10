ALTER TABLE `def_tour_categories`
	ADD COLUMN `type` TINYINT(1) NULL DEFAULT '1' AFTER `status`;