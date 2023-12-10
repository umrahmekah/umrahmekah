ALTER TABLE `travellers`
	ADD COLUMN `last_name` VARCHAR(100) NULL DEFAULT '' AFTER `nameandsurname`,
	ADD COLUMN `gender` VARCHAR(50) NULL DEFAULT '' AFTER `last_name`;
