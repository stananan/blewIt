-- The order of these tables being created is important due to the foreign key restraints.

CREATE TABLE bi_users (
    id BIGINT(20) NOT NULL,
    username CHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    password CHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    creation_time BIGINT(20) NOT NULL DEFAULT 0,
    last_login_time BIGINT(20) NOT NULL DEFAULT 0,
    
    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE bi_communities (
    id BIGINT(20) NOT NULL,
    name CHAR(30) NOT NULL,
    description VARCHAR(300) NOT NULL,
    
    PRIMARY KEY (id),
    UNIQUE KEY (name)
);

CREATE TABLE bi_posts (
    id BIGINT(20) NOT NULL,
    author_id BIGINT(20) NOT NULL,
    content VARCHAR(1024) NOT NULL,
    creation_time BIGINT(20) NOT NULL,
    reply_id BIGINT(20),
    community_id BIGINT(20) NOT NULL,
    
    PRIMARY KEY (id),
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (reply_id) REFERENCES bi_posts(id),
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

-- INSERT INTO `users`
--     (`username`, `password`, `is_admin`, `create_time`,)
-- VALUES
--     ('Packersfan6', '$2y$10$dHxzgTusmEdqFruaHdsroeY0oI7AEH55AMr4ssPr91S4K84Hi3i/e', TRUE, now())
-- ;