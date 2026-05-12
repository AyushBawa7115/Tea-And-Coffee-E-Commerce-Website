<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Debug: Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug: Log the request
error_log("Add to Cart Request: " . print_r($_POST, true));

// Check if user is logged in
if (!isLoggedIn()) {
    // Store current page in session for redirect after login
    $_SESSION['redirect_after_login'] = $_SERVER['HTTP_REFERER'];
    header('Location: login.php');
    exit;
}

$userId = getUserId();
// Debug: Log user ID
error_log("User ID: " . $userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    
    // Debug: Log product details
    error_log("Product ID: " . $productId . ", Quantity: " . $quantity);
    
    if ($productId > 0) {
        try {
            // Debug: Check if product exists
            $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->execute([$productId]);
            $product = $stmt->fetch();
            
            if (!$product) {
                error_log("Product not found in database");
                $_SESSION['message'] = 'Product not found.';
                $_SESSION['message_type'] = 'error';
            } else {
                // Debug: Log before adding to cart
                error_log("Adding product to cart...");
                
                if (addToCart($pdo, $userId, $productId, $quantity)) {
                    // Debug: Log success
                    error_log("Product added to cart successfully");
                    $_SESSION['message'] = 'Product added to cart successfully!';
                    $_SESSION['message_type'] = 'success';
                } else {
                    // Debug: Log failure
                    error_log("Failed to add product to cart");
                    $_SESSION['message'] = 'Failed to add product to cart. Please try again.';
                    $_SESSION['message_type'] = 'error';
                }
            }
        } catch (PDOException $e) {
            // Debug: Log database error
            error_log("Database error: " . $e->getMessage());
            $_SESSION['message'] = 'Database error occurred. Please try again.';
            $_SESSION['message_type'] = 'error';
        }
    } else {
        // Debug: Log invalid product ID
        error_log("Invalid product ID");
        $_SESSION['message'] = 'Invalid product.';
        $_SESSION['message_type'] = 'error';
    }
}

// Debug: Log redirect
error_log("Redirecting to: " . $_SERVER['HTTP_REFERER']);

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit; 