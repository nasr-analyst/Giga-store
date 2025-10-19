## 🗃️ Database Schema

This is the schema for the `Giga-Store` database used in this project. The tables are listed in the logical order they should be created.

### 1\. Users

Stores user information for both customers and admins.

```sql
CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE,
    address VARCHAR(255),
    country VARCHAR(50),
    role VARCHAR(20) NOT NULL DEFAULT 'customer' -- (e.g., 'customer' or 'admin')
);
```

---

### 2\. Categories

Stores the main product categories (e.g., 'Phones', 'Laptops').

```sql
CREATE TABLE Categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT
);
```

---

### 3\. Products

Stores individual products and links them to a category.

```sql
CREATE TABLE Products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),

    category_id INT NOT NULL,

    FOREIGN KEY (category_id) REFERENCES Categories(id)
);
```

---

### 4\. Orders

Stores a summary of each completed order (customer info, total amount, and status).

```sql
CREATE TABLE Orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL, -- Allows for 'guest' checkouts

    -- Snapshot of customer data at the time of purchase
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,

    total_amount DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(50) NOT NULL DEFAULT 'Pending', -- ('Pending', 'Shipped', 'Cancelled')
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES Users(id)
);
```

---

### 5\. Order_Details

This is the junction table that links products from the `Products` table to an order in the `Orders` table.

```sql
CREATE TABLE Order_Details (
    id INT PRIMARY KEY AUTO_INCREMENT,

    -- Snapshot of the price and quantity at time of purchase
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,

    order_id INT NOT NULL,
    product_id INT NOT NULL,

    FOREIGN KEY (order_id) REFERENCES Orders(id),
    FOREIGN KEY (product_id) REFERENCES Products(id),

    -- Ensures the same product isn't added twice to the same order
    UNIQUE KEY uk_order_product (order_id, product_id)
);
```

---
