# Order System
## Requirements
- PHP 7.0+
- MySQL

## Setup
1. Clone project
2. Create database `order_queue` and run the following table SQL(Folloing i have also given in database folder also):
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    items JSON NOT NULL,
    pickup_time DATETIME NOT NULL,
    VIP BOOLEAN DEFAULT FALSE,
    status ENUM('active','completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
3. Update `src/config.php` with your DB credentials.
4. Test endpoints with Postman I have given postman collection

POST /orders
GET /orders/active
POST /orders/{id}/complete
