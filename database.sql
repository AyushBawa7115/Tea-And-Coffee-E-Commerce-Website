-- Create database
CREATE DATABASE teacoffee;
USE teacoffee;

--Users table
CREATE TABLE users (
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
);

-- Products table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(3, 1) DEFAULT 0,
    category VARCHAR(50) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Product images table
CREATE TABLE product_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Product details table
CREATE TABLE product_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    details TEXT NOT NULL,
    instructions TEXT NOT NULL,
    origin VARCHAR(100),
    ingredients TEXT,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, product_id)
);

-- Wishlist table
CREATE TABLE wishlist (
    wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, product_id)
);

-- Orders table
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address TEXT NOT NULL,
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

INSERT INTO products (name, description, price, rating, category, stock) VALUES
('Premium Assam Black Tea', 'Rich and malty black tea from the Assam region of India', 350, 4.5, 'tea', 100),
('Ethiopian Yirgacheffe Coffee', 'Bright, fruity coffee with notes of citrus and chocolate', 439, 4.8, 'coffee', 75),
('Japanese Matcha Green Tea', 'Ceremonial grade matcha powder for traditional preparation', 539, 4.7, 'tea', 50),
('Colombian Supremo Coffee', 'Well-balanced coffee with caramel sweetness and nutty undertones', 450, 4.6, 'coffee', 80),
('Darjeeling First Flush Tea', 'Light and floral black tea from the foothills of the Himalayas', 499, 4.9, 'tea', 60),
('Earl Grey Tea', 'Classic black tea infused with bergamot oil', 249, 4.3, 'tea', 120),
('Chamomile Herbal Tea', 'Soothing herbal infusion with apple-like flavor', 249, 4.4, 'tea', 90),
('Jasmine Green Tea', 'Green tea scented with jasmine blossoms', 350, 4.6, 'tea', 85),
('Rooibos Red Tea', 'Caffeine-free herbal tea from South Africa', 280, 4.2, 'tea', 70),
('French Roast Coffee', 'Dark roast coffee with smoky, intense flavor', 370, 4.5, 'coffee', 95),
('Kona Coffee', 'Premium coffee grown on the slopes of Hualalai in Hawaii', 1150, 4.9, 'coffee', 40),
('Espresso Blend', 'Bold, rich blend perfect for espresso machines', 444, 4.7, 'coffee', 65),
('Decaf Breakfast Blend', 'Smooth, caffeine-free medium roast coffee', 410, 4.1, 'coffee', 55);
('Deluxe Tea Gift Box', 'A beautiful gift box containing 5 premium loose leaf teas, perfect for any tea lover.', 750, 4.5, 'gifts', 50),
('Coffee Lovers Gift Set', 'A curated selection of 3 specialty coffees with a stylish mug and brewing guide.', 850, 4.9, 'gifts', 50),
('Tea & Biscuits Gift Basket', 'A charming gift basket with assorted teas and gourmet biscuits.', 600, 4.7, 'gifts', 50),
('Matcha Ceremony Set', 'Traditional Japanese matcha set with bowl, whisk, and premium matcha powder.', 739, 4.1, 'gifts', 50),
('Coffee Subscription Box', 'A 3-month subscription of premium coffee delivered monthly.', 1030, 4.5, 'accessories', 50),
('Ceramic Pour-Over Coffee Dripper', 'Elegant ceramic pour-over coffee maker for a perfect brew every time.', 450, 4.9, 'accessories', 50),
('Glass Teapot with Infuser', 'Beautiful heat-resistant glass teapot with removable stainless steel infuser.', 999, 4.7, 'accessories', 50),
('Electric Gooseneck Kettle', 'Precision electric kettle with temperature control for perfect brewing.', 1300, 4.5, 'accessories', 50),
('Stainless Steel Tea Infuser', 'High-quality mesh tea infuser for loose leaf tea.', 800, 4.5, 'accessories', 50),
('Coffee Bean Grinder', 'Adjustable burr grinder for the perfect coffee grind.', 1150, 4.5, 'accessories', 50);



INSERT INTO product_images (product_id, image_path, is_primary) VALUES
-- Product 1
(1, 'images/Assam.webp', TRUE),
(1, 'images/Assam1.jpeg', FALSE),
(1, 'images/Assam2.webp', FALSE),
(1, 'images/Assam3.jpeg', FALSE),

-- Product 2
(2, 'images/yirga.jpeg', TRUE),
(2, 'images/yirga1.jpg', FALSE),
(2, 'images/yirga2.webp', FALSE),
(2, 'images/yirga3.jpeg', FALSE),

-- Product 3
(3, 'images/green-tea.jpg', TRUE),
(3, 'images/japan.webp', FALSE),
(3, 'images/japan2.jpg', FALSE),
(3, 'images/japan3.jpg', FALSE),

-- Product 4
(4, 'images/colobium.webp', TRUE),
(4, 'images/colobium1.webp', FALSE),
(4, 'images/colobium2.webp', FALSE),
(4, 'images/colobium3.webp', FALSE),

-- Product 5
(5, 'images/darjleeng.jpg', TRUE),
(5, 'images/darjleeng1.webp', FALSE),
(5, 'images/darjleeng2.webp', FALSE),
(5, 'images/darjleeng3.jpg', FALSE),

-- Product 6
(6, 'images/earl.avif', TRUE),
(6, 'images/earl1.webp', FALSE),
(6, 'images/earl2.jpeg', FALSE),
(6, 'images/earl3.jpg', FALSE),

-- Product 7
(7, 'images/herbal1.jpeg', TRUE),
(7, 'images/herbal2.jpeg', FALSE),
(7, 'images/herbal3.webp', FALSE),
(7, 'images/herbal-tea.jpg', FALSE),

-- Product 8
(8, 'images/jasmeen.jpeg', TRUE),
(8, 'images/jasmeen1.jpg', FALSE),
(8, 'images/jasmeen2.jpeg', FALSE),
(8, 'images/jasmeen3.jpeg', FALSE),

-- Product 9
(9, 'images/red.jpg', TRUE),
(9, 'images/red1.jpg', FALSE),
(9, 'images/red2.jpg', FALSE),
(9, 'images/red3.jpeg', FALSE),

-- Product 10
(10, 'images/roast.webp', TRUE),
(10, 'images/roast1.jpeg', FALSE),
(10, 'images/roast2.avif', FALSE),
(10, 'images/roast3.avif', FALSE),

-- Product 11
(11, 'images/kona.webp', TRUE),
(11, 'images/kona1.jpg', FALSE),
(11, 'images/kona2.webp', FALSE),
(11, 'images/kona3.jpg', FALSE),

-- Product 12
(12, 'images/espresso.webp', TRUE),
(12, 'images/espresso1.webp', FALSE),
(12, 'images/espresso2.webp', FALSE),
(12, 'images/espresso3.webp', FALSE),

-- Product 13
(13, 'images/breakfastblend.jpg', TRUE),
(13, 'images/breakfastblend1.jpg', FALSE),
(13, 'images/breakfastblend2.jpg', FALSE),
(13, 'images/breakfastblend3.jpg', FALSE);

-- Product 14
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(14, 'images/deluxea.webp', TRUE),
(14, 'images/deluxe1.jpg', FALSE),
(14, 'images/deluxe2.webp', FALSE),
(14, 'images/deluxe3.webp', FALSE);

-- Product 15
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(15, 'images/coffeelover.jpg', TRUE),
(15, 'images/coffeelover1.webp', FALSE),
(15, 'images/coffeelover2.jpg', FALSE),
(15, 'images/coffeelover3.jpg', FALSE);

-- Product 16
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(16, 'images/basket.jpeg', TRUE),
(16, 'images/basket1.webp', FALSE),
(16, 'images/basket2.jpg', FALSE),
(16, 'images/basket3.jpg', FALSE);

-- Product 17
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(17, 'images/matchaset.jpg', TRUE),
(17, 'images/matchaset1.avif', FALSE),
(17, 'images/matchaset2.webp', FALSE),
(17, 'images/matchaset3.webp', FALSE);

-- Product 18
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(18, 'images/coffeesub.jpg', TRUE),
(18, 'images/coffeesub1.jpeg', FALSE),
(18, 'images/coffeesub2.webp', FALSE),
(18, 'images/coffeesub3.jpg', FALSE);

-- Product 19
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(19, 'images/coffeedipper.webp', TRUE),
(19, 'images/coffeedipper1.jpg', FALSE),
(19, 'images/coffeedipper2.webp', FALSE),
(19, 'images/coffeedipper3.webp', FALSE);

-- Product 20
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(20, 'images/glassinfuser.webp', TRUE),
(20, 'images/glassinfuser1.webp', FALSE),
(20, 'images/glassinfuser2.jpg', FALSE),
(20, 'images/glassinfuser3.jpg', FALSE);

-- Product 21
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(21, 'images/kettle.webp', TRUE),
(21, 'images/kettle1.jpg', FALSE),
(21, 'images/kettle2.webp', FALSE),
(21, 'images/kettle3.webp', FALSE);

-- Product 22
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(22, 'images/steelinfuser.jpg', TRUE),
(22, 'images/steelinfuser2.jpg', FALSE),
(22, 'images/steelinfuser.jpg', FALSE),
(22, 'images/steelinfuser1.avif', FALSE);

-- Product 23
INSERT INTO product_images (product_id, image_path, is_primary) VALUES
(23, 'images/bean.webp', TRUE),
(23, 'images/bean1.jpeg', FALSE),
(23, 'images/bean2.webp', FALSE),
(23, 'images/bean3.jpg', FALSE);

-- Product details for gifts and accessories
INSERT INTO product_details (product_id, details, instructions, origin, ingredients) VALUES
-- Deluxe Tea Gift Box (14)
(14, 'A premium collection of 5 hand-selected loose leaf teas, beautifully packaged in an elegant gift box.', 
'1. Each tea comes in a resealable pouch to maintain freshness
2. Store in a cool, dry place away from direct sunlight
3. Use 1 teaspoon per cup, steep for 3-5 minutes
4. Perfect for gifting or personal enjoyment
5. Includes brewing guide and tasting notes', 
'Various regions', 
'Contains: Assam Black Tea, Darjeeling First Flush, Earl Grey, Jasmine Green Tea, Rooibos Red Tea'),

-- Coffee Lovers Gift Set (15)
(15, 'A curated selection of 3 specialty coffees with a stylish mug and brewing guide.', 
'1. Each coffee is vacuum-sealed for maximum freshness
2. Grind beans just before brewing for best flavor
3. Use 2 tablespoons of ground coffee per 6 oz of water
4. Includes detailed brewing instructions for each coffee
5. Perfect for coffee enthusiasts', 
'Various regions', 
'Contains: Ethiopian Yirgacheffe, Colombian Supremo, French Roast'),

-- Tea & Biscuits Gift Basket (16)
(16, 'A charming gift basket with assorted teas and gourmet biscuits.', 
'1. Store teas in airtight containers
2. Keep biscuits in a cool, dry place
3. Best consumed within 3 months of purchase
4. Perfect for afternoon tea gatherings
5. Includes serving suggestions', 
'Various regions', 
'Contains: Earl Grey Tea, Chamomile Tea, Assorted Gourmet Biscuits'),

-- Matcha Ceremony Set (17)
(17, 'Traditional Japanese matcha set with bowl, whisk, and premium matcha powder.', 
'1. Sift 1-2 teaspoons of matcha powder into bowl
2. Add 2-3 oz of hot water (175°F/80°C)
3. Whisk in a "W" or "M" motion until frothy
4. Clean whisk immediately after use
5. Store matcha in airtight container in refrigerator', 
'Japan', 
'Ceremonial grade matcha powder'),

-- Coffee Subscription Box (18)
(18, 'A 3-month subscription of premium coffee delivered monthly.', 
'1. Each delivery includes freshly roasted coffee
2. Store beans in airtight container away from light
3. Grind beans just before brewing
4. Follow brewing guide included with each delivery
5. Contact customer service for subscription management', 
'Various regions', 
'Premium Arabica coffee beans'),

-- Ceramic Pour-Over Coffee Dripper (19)
(19, 'Elegant ceramic pour-over coffee maker for a perfect brew every time.', 
'1. Place filter in dripper and rinse with hot water
2. Add ground coffee (medium-fine grind)
3. Pour hot water (195-205°F) in circular motion
4. Allow coffee to drip through completely
5. Clean with warm water after each use', 
'Japan', 
'High-quality ceramic material'),

-- Glass Teapot with Infuser (20)
(20, 'Beautiful heat-resistant glass teapot with removable stainless steel infuser.', 
'1. Fill infuser with loose leaf tea
2. Add hot water (temperature varies by tea type)
3. Steep for recommended time
4. Remove infuser when desired strength is reached
5. Hand wash with mild detergent', 
'China', 
'Borosilicate glass, stainless steel'),

-- Electric Gooseneck Kettle (21)
(21, 'Precision electric kettle with temperature control for perfect brewing.', 
'1. Fill with clean, filtered water
2. Select desired temperature (varies by tea/coffee type)
3. Wait for water to reach temperature
4. Pour slowly for controlled extraction
5. Clean regularly to prevent mineral buildup', 
'China', 
'Stainless steel, electronic components'),

-- Stainless Steel Tea Infuser (22)
(22, 'High-quality mesh tea infuser for loose leaf tea.', 
'1. Fill infuser with loose leaf tea
2. Place in cup or teapot
3. Add hot water and steep
4. Remove infuser when desired strength is reached
5. Rinse and dry after each use', 
'China', 
'Food-grade stainless steel'),

-- Coffee Bean Grinder (23)
(23, 'Adjustable burr grinder for the perfect coffee grind.', 
'1. Select grind size based on brewing method
2. Add coffee beans to hopper
3. Grind only what you need for immediate use
4. Clean regularly to maintain performance
5. Store in dry place when not in use', 
'Germany', 
'Stainless steel burrs, motor components');
