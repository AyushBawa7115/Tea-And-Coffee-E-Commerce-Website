<?php
session_start();

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to get user ID
function getUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Function to get product details by ID
function getProductById($pdo, $productId) {
    $stmt = $pdo->prepare("
        SELECT p.*, GROUP_CONCAT(pi.image_path) as images
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        WHERE p.product_id = ?
        GROUP BY p.product_id
    ");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if ($product) {
        $product['images'] = explode(',', $product['images']);
    }
    
    return $product;
}

// Function to get product details
function getProductDetails($pdo, $productId) {
    $stmt = $pdo->prepare("SELECT * FROM product_details WHERE product_id = ?");
    $stmt->execute([$productId]);
    return $stmt->fetch();
}

// Function to add item to cart
function addToCart($pdo, $userId, $productId, $quantity = 1) {
    try {
        // Debug: Log function parameters
        error_log("addToCart called with: userId=$userId, productId=$productId, quantity=$quantity");
        
        // Check if product already in cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $existingItem = $stmt->fetch();
        
        if ($existingItem) {
            // Debug: Log existing item
            error_log("Existing item found in cart: " . print_r($existingItem, true));
            
            // Update quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
            $result = $stmt->execute([$newQuantity, $existingItem['cart_id']]);
            
            // Debug: Log update result
            error_log("Update cart result: " . ($result ? "success" : "failed"));
            return $result;
        } else {
            // Debug: Log new item
            error_log("Adding new item to cart");
            
            // Add new item
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $result = $stmt->execute([$userId, $productId, $quantity]);
            
            // Debug: Log insert result
            error_log("Insert cart result: " . ($result ? "success" : "failed"));
            return $result;
        }
    } catch (PDOException $e) {
        // Debug: Log exception
        error_log("Exception in addToCart: " . $e->getMessage());
        return false;
    }
}

// Function to get cart items
function getCartItems($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT c.cart_id, c.quantity, p.product_id, p.name, p.price, p.description, pi.image_path
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Function to update cart quantity
function updateCartQuantity($pdo, $cartId, $quantity) {
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
    return $stmt->execute([$quantity, $cartId]);
}

// Function to remove item from cart
function removeFromCart($pdo, $cartId) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ?");
    return $stmt->execute([$cartId]);
}

// Function to get cart total
function getCartTotal($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT SUM(c.quantity * p.price) as total
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

// Function to add item to wishlist
function addToWishlist($pdo, $userId, $productId) {
    try {
        // Check if product already in wishlist
        $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        
        if (!$stmt->fetch()) {
            // Add new item
            $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $stmt->execute([$userId, $productId]);
        }
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to get wishlist items
function getWishlistItems($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT w.wishlist_id, p.product_id, p.name, p.price, p.description, pi.image_path
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE w.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Function to remove item from wishlist
function removeFromWishlist($pdo, $wishlistId) {
    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE wishlist_id = ?");
    return $stmt->execute([$wishlistId]);
}

// Function to create a new order
function createOrder($pdo, $userId, $totalAmount, $shippingAddress, $paymentMethod) {
    try {
        $pdo->beginTransaction();
        
        // Create order
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total_amount, shipping_address, payment_method)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $totalAmount, $shippingAddress, $paymentMethod]);
        $orderId = $pdo->lastInsertId();
        
        // Get cart items
        $cartItems = getCartItems($pdo, $userId);
        
        // Add items to order
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($cartItems as $item) {
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            ]);
        }
        
        // Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $pdo->commit();
        return $orderId;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

// Function to get user orders
function getUserOrders($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT * FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

// Function to get order details
function getOrderDetails($pdo, $orderId) {
    $stmt = $pdo->prepare("
        SELECT oi.*, p.name, p.description, pi.image_path
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$orderId]);
    return $stmt->fetchAll();
}

// Function to get cart count
function getCartCount($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT SUM(quantity) as count
        FROM cart
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}

// Function to get wishlist count
function getWishlistCount($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count
        FROM wishlist
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['count'] ?? 0;
}
?>