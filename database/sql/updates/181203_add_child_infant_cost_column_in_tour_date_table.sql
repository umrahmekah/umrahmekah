ALTER TABLE `tour_date`
	ADD COLUMN `cost_child_wo_bed` INT(11) NULL DEFAULT NULL AFTER `cost_child`,
	ADD COLUMN `cost_infant_wo_bed` INT(11) NULL DEFAULT NULL AFTER `cost_child_wo_bed`;
