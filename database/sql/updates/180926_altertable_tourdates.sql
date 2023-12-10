ALTER TABLE `tour_date`
	ADD COLUMN `cost_quint` INT(11) NULL DEFAULT NULL AFTER `available_quad`,
	ADD COLUMN `available_quint` INT(11) NULL DEFAULT NULL AFTER `cost_quint`,
	ADD COLUMN `cost_sext` INT(11) NULL DEFAULT NULL AFTER `available_quint`,
	ADD COLUMN `available_sext` INT(11) NULL DEFAULT NULL AFTER `cost_sext`;
