
CREATE TABLE `pages` (
	`id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(255) NOT NULL COMMENT 'Identifikátor',
	`markdown` text NOT NULL COMMENT 'Text (markdown)'
) COMMENT='Stránky';

ALTER TABLE `pages` ADD UNIQUE `name` (`name`);
