
CREATE TABLE `searching` (
  `game_id` int(11) NOT NULL COMMENT 'Hra',
  `user_id` int(11) NOT NULL COMMENT 'Uživatel',
  FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) COMMENT='Spoluhráči';
