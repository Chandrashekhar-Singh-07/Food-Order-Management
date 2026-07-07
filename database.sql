-- ============================================
-- Tadka Express - Food Ordering System
-- Database Schema
-- Import this file in phpMyAdmin (or: mysql -u root -p < database.sql)
-- ============================================

CREATE DATABASE IF NOT EXISTS food_ordering CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE food_ordering;

-- ---------------------------
-- Categories (Starters, Main Course, etc.)
-- ---------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0
);

-- ---------------------------
-- Menu Items
-- ---------------------------
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description VARCHAR(255) DEFAULT '',
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT '',
    spice_level TINYINT DEFAULT 0,   -- 0 none, 1 mild, 2 medium, 3 hot
    is_veg TINYINT(1) DEFAULT 1,
    is_available TINYINT(1) DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- ---------------------------
-- Orders
-- ---------------------------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    payment_method VARCHAR(20) DEFAULT 'COD',
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('Pending','Confirmed','Preparing','Out for Delivery','Delivered','Cancelled') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------
-- Order Items (line items per order)
-- ---------------------------
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT,
    item_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- ---------------------------
-- Admins
-- ---------------------------
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- NOTE: Admin login is NOT inserted here.
-- After importing this file, open admin/setup.php ONCE in your browser
-- to create the default admin account (username: admin | password: admin123).
-- This generates the password hash using YOUR server's PHP, which is safer
-- than shipping a hash generated somewhere else.

-- ---------------------------
-- Sample Categories
-- ---------------------------
INSERT INTO categories (name, sort_order) VALUES
('Starters', 1),
('Main Course', 2),
('Breads', 3),
('Rice & Biryani', 4),
('Beverages & Desserts', 5);

-- ---------------------------
-- Sample Menu Items
-- ---------------------------
INSERT INTO menu_items (category_id, name, description, price, image, spice_level, is_veg) VALUES
(1, 'Paneer Tikka', 'Chargrilled cottage cheese marinated in spiced yogurt', 220.00, 'paneer-tikka.jpg', 2, 1),
(1, 'Chicken 65', 'Deep-fried chicken tossed in curry leaves and red chilli', 240.00, 'chicken-65.jpg', 3, 0),
(1, 'Veg Spring Roll', 'Crispy rolls stuffed with sauteed vegetables', 160.00, 'spring-roll.jpg', 1, 1),
(2, 'Butter Chicken', 'Tandoori chicken simmered in a creamy tomato gravy', 320.00, 'butter-chicken.jpg', 1, 0),
(2, 'Paneer Butter Masala', 'Cottage cheese cubes in rich tomato-butter gravy', 280.00, 'paneer-butter-masala.jpg', 1, 1),
(2, 'Dal Makhani', 'Slow-cooked black lentils finished with cream', 210.00, 'dal-makhani.jpg', 1, 1),
(3, 'Butter Naan', 'Leavened flatbread brushed with butter', 45.00, 'butter-naan.jpg', 0, 1),
(3, 'Garlic Naan', 'Naan topped with fresh garlic and coriander', 55.00, 'garlic-naan.jpg', 0, 1),
(4, 'Chicken Biryani', 'Layered basmati rice cooked with spiced chicken', 260.00, 'chicken-biryani.jpg', 2, 0),
(4, 'Veg Pulao', 'Fragrant basmati rice tossed with garden vegetables', 190.00, 'veg-pulao.jpg', 1, 1),
(5, 'Masala Chai', 'Spiced Indian tea brewed with milk', 40.00, 'masala-chai.jpg', 0, 1),
(5, 'Gulab Jamun', 'Soft milk dumplings soaked in rose-cardamom syrup', 90.00, 'gulab-jamun.jpg', 0, 1);
