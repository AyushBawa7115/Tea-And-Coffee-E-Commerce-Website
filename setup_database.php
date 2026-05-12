<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = ''; // Change this if your MySQL has a password
$dbname = 'teacoffee';

try {
    // Create connection without database name first
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "Database created or already exists.<br>";
    
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            user_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            address TEXT,
            city VARCHAR(50),
            state VARCHAR(50),
            zip_code VARCHAR(20),
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Users table created.<br>";
    
    // Create products table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            product_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            rating DECIMAL(3, 1) DEFAULT 0,
            category VARCHAR(50) NOT NULL,
            stock INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Products table created.<br>";
    
    // Create product_images table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS product_images (
            image_id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            is_primary BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
        )
    ");
    echo "Product images table created.<br>";
    
    // Create product_details table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS product_details (
            detail_id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            details TEXT NOT NULL,
            instructions TEXT NOT NULL,
            origin VARCHAR(100),
            ingredients TEXT,
            FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
        )
    ");
    echo "Product details table created.<br>";
    
    // Create cart table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cart (
            cart_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL DEFAULT 1,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
            UNIQUE KEY (user_id, product_id)
        )
    ");
    echo "Cart table created.<br>";
    
    // Create wishlist table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS wishlist (
            wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            product_id INT NOT NULL,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
            UNIQUE KEY (user_id, product_id)
        )
    ");
    echo "Wishlist table created.<br>";
    
    // Create orders table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            order_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            shipping_address TEXT NOT NULL,
            order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
            payment_method VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        )
    ");
    echo "Orders table created.<br>";
    
    // Create order_items table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            item_id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
        )
    ");
    echo "Order items table created.<br>";
    
    // Insert sample products
    $checkProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($checkProducts == 0) {
        $pdo->exec("
            INSERT INTO products (name, description, price, rating, category, stock) VALUES
            ('Premium Assam Black Tea', 'Rich and malty black tea from the Assam region of India', 12.99, 4.5, 'tea', 100),
            ('Ethiopian Yirgacheffe Coffee', 'Bright, fruity coffee with notes of citrus and chocolate', 15.99, 4.8, 'coffee', 75),
            ('Japanese Matcha Green Tea', 'Ceremonial grade matcha powder for traditional preparation', 24.99, 4.7, 'tea', 50),
            ('Colombian Supremo Coffee', 'Well-balanced coffee with caramel sweetness and nutty undertones', 14.99, 4.6, 'coffee', 80),
            ('Darjeeling First Flush Tea', 'Light and floral black tea from the foothills of the Himalayas', 18.99, 4.9, 'tea', 60),
            ('Earl Grey Tea', 'Classic black tea infused with bergamot oil', 10.99, 4.3, 'tea', 120),
            ('Chamomile Herbal Tea', 'Soothing herbal infusion with apple-like flavor', 9.99, 4.4, 'tea', 90),
            ('Jasmine Green Tea', 'Green tea scented with jasmine blossoms', 11.99, 4.6, 'tea', 85),
            ('Rooibos Red Tea', 'Caffeine-free herbal tea from South Africa', 8.99, 4.2, 'tea', 70),
            ('French Roast Coffee', 'Dark roast coffee with smoky, intense flavor', 13.99, 4.5, 'coffee', 95),
            ('Kona Coffee', 'Premium coffee grown on the slopes of Hualalai in Hawaii', 29.99, 4.9, 'coffee', 40),
            ('Espresso Blend', 'Bold, rich blend perfect for espresso machines', 16.99, 4.7, 'coffee', 65),
            ('Decaf Breakfast Blend', 'Smooth, caffeine-free medium roast coffee', 12.99, 4.1, 'coffee', 55)
        ");
        echo "Sample products inserted.<br>";
        
        // Insert sample product images
        $pdo->exec("
            INSERT INTO product_images (product_id, image_path, is_primary) VALUES
            (1, 'images/product1.jpg', 1),
            (1, 'images/product1-2.jpg', 0),
            (1, 'images/product1-3.jpg', 0),
            (1, 'images/product1-4.jpg', 0),
            (2, 'images/product2.jpg', 1),
            (3, 'images/product3.jpg', 1),
            (4, 'images/product4.jpg', 1),
            (5, 'images/product5.jpg', 1),
            (6, 'images/tea1.jpg', 1),
            (7, 'images/tea2.jpg', 1),
            (8, 'images/tea3.jpg', 1),
            (9, 'images/tea4.jpg', 1),
            (10, 'images/coffee1.jpg', 1),
            (11, 'images/coffee2.jpg', 1),
            (12, 'images/coffee3.jpg', 1),
            (13, 'images/coffee4.jpg', 1)
        ");
        echo "Sample product images inserted.<br>";
        
        // Insert sample product details
        $pdo->exec("
            INSERT INTO product_details (product_id, details, instructions, origin, ingredients) VALUES
            (1, '<p>Our Premium Assam Black Tea is sourced from the finest tea gardens in the Assam region of India. Known for its rich, malty flavor and bright color, this tea is perfect for those who enjoy a robust cup of tea.</p><p>This tea is harvested during the second flush, which occurs in late spring and early summer, producing a tea with a full-bodied taste and a sweet, malty character.</p><h4>Origin</h4><p>Assam, India</p><h4>Flavor Profile</h4><p>Malty, rich, with subtle notes of caramel and a smooth finish</p><h4>Caffeine Content</h4><p>High</p><h4>Ingredients</h4><p>100% Assam black tea leaves</p><h4>Package Contents</h4><p>100g loose leaf tea in a resealable pouch</p>', '<h4>Brewing Instructions</h4><ol><li>Heat fresh, filtered water to a rolling boil (212°F).</li><li>Use 1 teaspoon (2g) of tea per 8oz cup.</li><li>Steep for 3-5 minutes.</li><li>For a stronger brew, use more tea or steep longer.</li></ol><h4>Storage</h4><p>Store in a cool, dry place away from direct sunlight, moisture, and strong odors.</p><h4>Shelf Life</h4><p>Best consumed within 18 months of the production date for optimal flavor.</p>', 'Assam, India', '100% Assam black tea leaves')
        ");
        echo "Sample product details inserted.<br>";
    } else {
        echo "Sample data already exists.<br>";
    }
    
    echo "<br><strong>Database setup completed successfully!</strong>";
    echo "<br><a href='index.php'>Go to homepage</a>";
    
} catch(PDOException $e) {
    echo "<br><strong>Error:</strong> " . $e->getMessage();
}
?>