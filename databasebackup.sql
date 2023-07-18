CREATE TABLE IF NOT EXISTS `bi_interactions` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int,
    `post_id` int,
    `interaction_type` text,
    PRIMARY KEY (`id`)
);



CREATE TABLE IF NOT EXISTS `bi_users`(
    `id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `is_admin` boolean,
    `create_time` DATETIME,
    `last_login_time` DATETIME,
    PRIMARY KEY (`id`),
    CONSTRAINT username_unique UNIQUE (`username`)
);
