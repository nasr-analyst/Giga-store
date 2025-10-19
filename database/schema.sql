-- ---------------------------------
-- 1. Create the Database
-- ---------------------------------
CREATE DATABASE IF NOT EXISTS gigastore_db;
USE gigastore_db;

-- ---------------------------------
-- 2. Create independent tables
-- ---------------------------------

-- Stores user accounts (customers and admins)
CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE,
    address VARCHAR(255),
    country VARCHAR(50),
    role VARCHAR(20) NOT NULL DEFAULT 'customer' -- e.g., 'customer' or 'admin'
) ENGINE=InnoDB;

-- Stores product categories (e.g., 'Laptops', 'Phones')
CREATE TABLE Categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB;

-- ---------------------------------
-- 3. Create dependent tables
-- ---------------------------------

-- Stores individual products
CREATE TABLE Products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    
    -- Foreign key linking to the Categories table
    category_id INT NOT NULL,
    
    FOREIGN KEY (category_id) 
        REFERENCES Categories(id)
        ON DELETE RESTRICT -- Prevents deleting a category if products are linked to it
) ENGINE=InnoDB;

-- Stores the summary for each order (customer info, total)
CREATE TABLE Orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    
    -- Foreign key linking to the Users table (can be NULL for guest checkouts)
    user_id INT NULL, 

    -- Snapshot of customer data at the time of purchase
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    
    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(50) NOT NULL DEFAULT 'Pending', -- e.g., 'Pending', 'Shipped'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) 
        REFERENCES Users(id)
        ON DELETE SET NULL -- If a user is deleted, the order remains (user_id becomes NULL)
) ENGINE=InnoDB;

-- ---------------------------------
-- 4. Create the final junction table
-- ---------------------------------

-- Stores the specific items belonging to each order
CREATE TABLE Order_Details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    
    -- Product details at the time of purchase
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL, -- Price snapshot
    
    -- Foreign keys linking to Orders and Products
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    
    FOREIGN KEY (order_id) 
        REFERENCES Orders(id)
        ON DELETE CASCADE, -- If an order is deleted, its items are also deleted
        
    FOREIGN KEY (product_id) 
        REFERENCES Products(id)
        ON DELETE RESTRICT, -- Prevents deleting a product if it's part of an order
    
    -- Ensures the same product isn't added twice to the same order
    UNIQUE KEY uk_order_product (order_id, product_id)
) ENGINE=InnoDB;