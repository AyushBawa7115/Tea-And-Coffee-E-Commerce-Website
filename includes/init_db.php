<?php
require_once 'db_connect.php';

try {
    // Read and execute the SQL file
    $sql = file_get_contents('create_tables.sql');
    $pdo->exec($sql);
    
    // Insert sample products if they don't exist
    $products = [
        [
            'name' => 'Premium Assam Black Tea',
            'description' => 'Rich and malty black tea from the Assam region of India',
            'price' => 350,
            'rating' => 4.5
        ],
        [
            'name' => 'Ethiopian Yirgacheffe Coffee',
            'description' => 'Bright, fruity coffee with notes of citrus and chocolate',
            'price' => 439,
            'rating' => 4.8
        ],
        // Add more products as needed
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO products (name, description, price, rating) VALUES (?, ?, ?, ?)");
    
    foreach ($products as $product) {
        $stmt->execute([
            $product['name'],
            $product['description'],
            $product['price'],
            $product['rating']
        ]);
    }
    
    echo "Database initialized successfully!";
} catch (PDOException $e) {
    echo "Error initializing database: " . $e->getMessage();
} 