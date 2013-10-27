
CREATE TABLE `reservations` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `game_id` int(11) NOT NULL COMMENT 'Hra',
  `user_id` int(11) NOT NULL COMMENT 'Uživatel',
  `date` date NOT NULL COMMENT 'Datum',
  FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) COMMENT='Rezervace';
