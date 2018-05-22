CREATE TABLE microposts.follow (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    user_id VARCHAR(255),
    follow_id VARCHAR(255),
    UNIQUE (user_id,follow_id)
);