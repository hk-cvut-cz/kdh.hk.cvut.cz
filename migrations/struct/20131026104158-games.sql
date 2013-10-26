
CREATE TABLE `games` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL  COMMENT 'Jméno hry' ,
  `url` text NOT NULL COMMENT 'Url hry',
  `status` enum('available','broken','purchased','proposed') NOT NULL COMMENT 'Stav'
) COMMENT 'Hry';
