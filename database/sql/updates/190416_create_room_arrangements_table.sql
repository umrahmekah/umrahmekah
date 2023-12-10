CREATE TABLE `room_arrangements` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`tourdate_id` INT NOT NULL,
	`room_arrangement` LONGTEXT NOT NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
);

ALTER TABLE `room_arrangements`
	ADD COLUMN `entry_by` INT NOT NULL AFTER `room_arrangement`,
	ADD COLUMN `owner_id` INT NOT NULL AFTER `entry_by`;
