CREATE TABLE IF NOT EXISTS `interactions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` int,
    `post_id` int,
    `interaction_type` text,
    PRIMARY KEY (`id`)
);



CREATE TABLE IF NOT EXISTS `users`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` text,
    `password` text,
    `is_admin` BOOLEAN,
    `create_time` DATETIME,
    `last_activity_time` DATETIME,
    PRIMARY KEY (`id`)
);

-- INSERT INTO `users`
--     (`username`, `password`, `is_admin`, `create_time`,)
-- VALUES
--     ('Packersfan6', '$2y$10$dHxzgTusmEdqFruaHdsroeY0oI7AEH55AMr4ssPr91S4K84Hi3i/e', TRUE, now())
-- ;