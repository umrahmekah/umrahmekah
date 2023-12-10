ALTER TABLE `book_room`
	ADD COLUMN `parent_id` INT(11) NULL DEFAULT NULL AFTER `status`;
