ALTER TABLE `tb_owners`
	ADD COLUMN `billplz_api_key` VARCHAR(225) NULL DEFAULT NULL AFTER `date`,
	ADD COLUMN `billplz_signature_key` VARCHAR(225) NULL DEFAULT NULL AFTER `billplz_api_key`,
	ADD COLUMN `billplz_collection_id` VARCHAR(225) NULL DEFAULT NULL AFTER `billplz_signature_key`;
