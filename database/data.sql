

-- Use the correct database first
USE gigastore_db;

-- Insert sample data into Categories table
INSERT INTO Categories (name, description) VALUES
('home', 'Home and Living Products'),
('mobile', 'Mobile Phones and Accessories'),
('music', 'Musical Instruments and Gear'),
('other', 'Other Items');


-- Insert sample data into Products table
INSERT INTO Products (name, description, price, image_url, category_id) VALUES
('Phone Holder', 'No available description', 29.90, 'assets/images/holder.jpg', 2),
('Headsound', 'No available description', 12.00, 'assets/images/headphones.jpg', 3),
('Adudu Cleaner', 'No available description', 29.90, 'assets/images/adudu.jpg', 1),
('CCTV Camera', 'No available description', 50.00, 'assets/images/cctv camera.jpg', 1),
('Usp hub', 'No available description', 9.90, 'assets/images/hub.jpg', 4),
('Mobile cover', 'No available description', 34.10, 'assets/images/cover.jpg', 2),
('Wireless Earbuds', 'No available description', 25.00, 'assets/images/earpods.jpg', 3),
('Smart Watch', 'No available description', 59.99, 'assets/images/smart watch.jpg', 4),
('Bluetooth Speaker', 'No available description', 19.90, 'assets/images/Bluetooth Speaker.jpg', 3),
('Power Bank', 'No available description', 15.50, 'assets/images/power.webp', 2),
('Macbook ar', 'No available description', 8.50, 'assets/images/MacBook_ar.png', 4),
('Laptop Stand', 'No available description', 22.00, 'assets/images/stand.jpg', 4),
('TWS Bujug', 'No available description', 29.90, 'assets/images/bujug.jpg', 4),
('Headsound Baptis', 'No available description', 12.00, 'assets/images/baptis.jpg', 3),
('Adudu Cleaner', 'No available description', 29.90, 'assets/images/cleaner.jpg', 1),
('Wireless Mouse', 'No available description', 14.50, 'assets/images/mouse.avif', 4),
('Smart Lamp', 'No available description', 18.00, 'assets/images/lamp.jpg', 1),
('Mini Projector', 'No available description', 59.00, 'assets/images/projector.jpg', 4);
