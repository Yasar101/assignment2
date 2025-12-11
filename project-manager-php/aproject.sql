
CREATE DATABASE IF NOT EXISTS project_manager;
USE project_manager;

-- ------------------------------------------------------
-- Users Table
-- ------------------------------------------------------

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------
-- Projects Table
-- ------------------------------------------------------

DROP TABLE IF EXISTS projects;

CREATE TABLE projects (
    pid INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(191) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    short_description TEXT,
    phase ENUM('design', 'development', 'testing', 'deployment', 'complete') NOT NULL,
    uid INT NOT NULL,
    FOREIGN KEY (uid) REFERENCES users(uid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ------------------------------------------------------
-- OPTIONAL: Sample Data
-- ------------------------------------------------------

INSERT INTO users (username, password, email) VALUES
('salwa', '$2y$10$EXAMPLEHASH12345678901234567890123456789012', 'salwa@example.com'),
('john', '$2y$10$EXAMPLEHASHabcdefghijk987654321abcde987654', 'john@example.com');

INSERT INTO projects (title, start_date, end_date, short_description, phase, uid) VALUES
('Inventory System', '2024-01-10', '2024-03-01', 'A basic inventory management tool.', 'development', 1),
('School Portal', '2024-02-05', NULL, 'Portal for school automation.', 'design', 2);