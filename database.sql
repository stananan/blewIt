-- The order of these tables being created is important due to the foreign key restraints.

CREATE TABLE bi_users (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    username CHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    password CHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    creation_time BIGINT(20) NOT NULL DEFAULT 0,
    last_login_time BIGINT(20) NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE bi_communities (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) NOT NULL DEFAULT 0,
    name CHAR(30) NOT NULL,
    description VARCHAR(300) NOT NULL,
    
    PRIMARY KEY (id),
    UNIQUE KEY (name)
);

CREATE TABLE bi_posts (
    id BIGINT(20) NOT NULL AUTO_INCREMENT,
    author_id BIGINT(20) NOT NULL,
    content VARCHAR(1024) NOT NULL,
    creation_time BIGINT(20) NOT NULL,
    reply_id BIGINT(20),
    community_id BIGINT(20) NOT NULL,
    
    PRIMARY KEY (id),
    FOREIGN KEY (author_id) REFERENCES bi_users(id),
    
    FOREIGN KEY (community_id) REFERENCES bi_communities(id)
);


CREATE TABLE bi_interactions (
    user_id BIGINT(20) NOT NULL,
    post_id BIGINT(20) NOT NULL,
    interaction_type BIGINT(20) NOT NULL,
    
    PRIMARY KEY (user_id, post_id),
    FOREIGN KEY (user_id) REFERENCES bi_users(id),
    FOREIGN KEY (post_id) REFERENCES bi_posts(id)
);

-- INSERT INTO `bi_users` 
--     (`username`, `password`)
-- VALUES 
--     ('hello', '$2y$10$udY7AW1lEKowEw9kFqeNVOwstPMAFMOc7/NuNoCWSEw7z1Aht23Jq')
-- ;


INSERT INTO `bi_communities` (`id`, `name`, `description`)
VALUES
    (1, 'News', 'Latest news and current events'),
    (2, 'Technology', 'Advancements in tech and science'),
    (3, 'Sports', 'Sports-related discussions'),
    (4, 'Gaming', 'Connect with gamers and discuss games'),
    (5, 'Movies', 'Films and TV shows discussions'),
    (6, 'Music', 'Music from various genres'),
    (7, 'Books', 'Literature and book recommendations'),
    (8, 'Food', 'Recipes, cooking tips, and food'),
    (9, 'Travel', 'Travel experiences and recommendations'),
    (10, 'Fashion', 'Fashion trends and styles'),
    (11, 'Fitness', 'Fitness tips and health advice'),
    (12, 'Art', 'Different forms of art'),
    (13, 'Science', 'Scientific discoveries and theories'),
    (14, 'DIY', 'Do-it-yourself projects and tutorials'),
    (15, 'Photography', 'Photography techniques and photos'),
    (16, 'Writing', 'Writing, storytelling, and literature'),
    (17, 'Health', 'Health-related discussions'),
    (18, 'Nature', 'Appreciating the wonders of nature'),
    (19, 'History', 'Historical events and figures'),
    (20, 'Politics', 'Political discussions and debates'),
    (21, 'Relationships', 'Relationship advice and discussions'),
    (22, 'Finance', 'Personal finance and investments'),
    (23, 'Humor', 'Jokes, memes, and funny content'),
    (24, 'Education', 'Educational topics and resources'),
    (25, 'Lifestyle', 'Various aspects of lifestyle')
;