-- SQL to add slip_content_hash column to orders table
-- Run this in phpMyAdmin

ALTER TABLE `orders` ADD COLUMN `slip_content_hash` VARCHAR(32) NULL AFTER `slip_hash`;
