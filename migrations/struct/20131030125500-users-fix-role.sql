
ALTER TABLE `users` CHANGE `role` `role` enum('editor') COLLATE 'utf8_unicode_ci' NULL AFTER `email`;
