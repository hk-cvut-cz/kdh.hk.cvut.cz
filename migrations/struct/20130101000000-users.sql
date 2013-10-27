
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `rups_id` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
);

ALTER TABLE `users` ADD UNIQUE `rups_id` (`rups_id`);
ALTER TABLE `users` ADD UNIQUE `login` (`login`);
