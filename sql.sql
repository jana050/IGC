
ALTER TABLE `sd_acv_shutdown` ADD `hos_id` INT NULL AFTER `last_modified_time`, ADD `hos_remarks` TEXT NULL AFTER `hos_id`, ADD `hos_time` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `hos_remarks`, ADD `hod_id` INT NULL AFTER `hos_time`, ADD `hod_remarks` TEXT NULL AFTER `hod_id`, ADD `hod_time` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `hod_remarks`;

ALTER TABLE `sd_electrical_shutdown` ADD `hos_id` INT NULL AFTER `last_modified_time`, ADD `hos_remarks` TEXT NULL AFTER `hos_id`, ADD `hos_time` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `hos_remarks`, ADD `hod_id` INT NULL AFTER `hos_time`, ADD `hod_remarks` TEXT NULL AFTER `hod_id`, ADD `hod_time` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `hod_remarks`;

ALTER TABLE sd_electrical
ADD COLUMN `date_of_closure` DATE DEFAULT NULL,
ADD COLUMN `supervisor_description` LONGTEXT DEFAULT NULL,
ADD COLUMN `supervisor` VARCHAR(255) DEFAULT NULL,
ADD COLUMN `supervisor_time` DATETIME DEFAULT NULL;

ALTER TABLE sd_telephone
ADD COLUMN `date_of_closure` DATE DEFAULT NULL,
ADD COLUMN `supervisor_description` LONGTEXT DEFAULT NULL,
ADD COLUMN `supervisor_time` DATETIME DEFAULT NULL,
ADD COLUMN `supervisor` INT(11) DEFAULT NULL;

ALTER TABLE sd_network
ADD COLUMN `date_of_closure` DATE DEFAULT NULL,
ADD COLUMN `supervisor_description` LONGTEXT DEFAULT NULL,
ADD COLUMN `supervisor_time` DATETIME DEFAULT NULL,
ADD COLUMN `supervisor` INT(11) DEFAULT NULL;

-- Free-form remarks the requester can add to a GEM Direct proposal at
-- creation / rework time. Shown on the View and Edit dialogs.
ALTER TABLE sd_gem_direct
ADD COLUMN `remarks` TEXT DEFAULT NULL AFTER `justification_purchase`;
