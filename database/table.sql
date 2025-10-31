CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    items JSON NOT NULL,
    pickup_time DATETIME NOT NULL,
    VIP BOOLEAN DEFAULT FALSE,
    status ENUM('active','completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
