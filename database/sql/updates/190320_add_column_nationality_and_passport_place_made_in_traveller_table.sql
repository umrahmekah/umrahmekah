ALTER TABLE `travellers`
	ADD COLUMN `nationality` INT(3) NULL DEFAULT NULL AFTER `countryID`,
	ADD COLUMN `passport_place_made` VARCHAR(225) NULL DEFAULT NULL AFTER `passportcountry`;
