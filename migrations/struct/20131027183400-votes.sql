
CREATE TABLE `votes` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
);
