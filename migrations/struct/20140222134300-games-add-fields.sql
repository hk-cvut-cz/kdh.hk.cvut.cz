
ALTER TABLE `games`
ADD `tags` varchar(250) COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Tagy (comma sep)' AFTER `url`,
ADD `players` varchar(50) COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Hráčů (2-5)' AFTER `tags`,
ADD `time` varchar(50) COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Čas (100 min)' AFTER `players`,
ADD `published` int unsigned NOT NULL COMMENT 'Rok vydání' AFTER `time`,
ADD `publisher` varchar(250) COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Vydavatel' AFTER `published`,
ADD `rating` double unsigned NOT NULL COMMENT 'Hodnocení (0..10)' AFTER `publisher`,
ADD `cover` varchar(1023) COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Url coveru' AFTER `rating`,
ADD `text` text COLLATE 'utf8_unicode_ci' NOT NULL COMMENT 'Popis (markdown)' AFTER `cover`,
COMMENT='Hry';
