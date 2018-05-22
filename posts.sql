CREATE TABLE microposts.posts (
    posts_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    posts_content VARCHAR(255),
    user_id INT ,
    created_at TIMESTAMP NOT NULL DEFAULT 0,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);