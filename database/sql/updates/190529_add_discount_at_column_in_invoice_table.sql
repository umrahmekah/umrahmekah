ALTER TABLE `invoice`
	ADD COLUMN `discount_at` TIMESTAMP NULL DEFAULT NULL AFTER `discount_by`;
