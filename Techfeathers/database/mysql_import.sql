-- MySQL import file for Techfeathers
-- Run with: mysql -u username -p < database/mysql_import.sql

DROP DATABASE IF EXISTS poultry;
CREATE DATABASE poultry CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE poultry;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'manager',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_code VARCHAR(100) NOT NULL UNIQUE,
    breed VARCHAR(255) NOT NULL,
    started_at DATE NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    status VARCHAR(50) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE eggs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    collected_at DATE NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE feed_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    quantity_kg DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit_cost DECIMAL(10,2) NOT NULL DEFAULT 0,
    last_updated DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE finances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('sale','expense') NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    incurred_at DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) NOT NULL,
    category VARCHAR(100) NOT NULL,
    available_quantity INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE mortality_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    recorded_at DATE NOT NULL,
    deaths INT NOT NULL DEFAULT 0,
    reason TEXT,
    notes TEXT,
    FOREIGN KEY (batch_id) REFERENCES batches(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (email, password, name, role) VALUES
('admin@poultry.local', '$2y$10$1r2xXawzZu/oh/YOsSvZ6uFpgeTc6hcMEoGQvxmcYvz6sXcp52N2q', 'Admin User', 'admin');

INSERT INTO batches (batch_code, breed, started_at, quantity, status) VALUES
('B-201', 'Leghorn', DATE_SUB(CURDATE(), INTERVAL 7 DAY), 120, 'active'),
('B-202', 'Rhode Island Red', DATE_SUB(CURDATE(), INTERVAL 10 DAY), 90, 'active');

INSERT INTO eggs (batch_id, collected_at, quantity) VALUES
(1, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 72),
(1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 68),
(1, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 74),
(2, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 65),
(2, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 61),
(2, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 67);

INSERT INTO feed_inventory (name, quantity_kg, unit_cost, last_updated) VALUES
('Layer Mash', 250.00, 25.00, CURDATE()),
('Grower Feed', 150.00, 22.50, CURDATE());

INSERT INTO finances (type, description, amount, incurred_at) VALUES
('sale', 'Egg sales', 420.00, DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
('expense', 'Feed purchase', 190.00, DATE_SUB(CURDATE(), INTERVAL 2 DAY));

INSERT INTO products (name, description, price, category, available_quantity) VALUES
('Fresh Eggs (Dozen)', 'Farm fresh eggs from our healthy chickens', 120.00, 'eggs', 50),
('Organic Feed (25kg)', 'Nutritious feed for poultry', 625.00, 'feed', 20),
('Chicken Manure Fertilizer (50kg)', 'Natural fertilizer from our farm', 150.00, 'fertilizer', 30);

INSERT INTO mortality_records (batch_id, recorded_at, deaths, reason, notes) VALUES
(1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 1, 'Natural causes', 'Minor loss during transfer'),
(2, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 0, 'No issues', 'Healthy flock');
