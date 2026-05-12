<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    $_SESSION['message'] = 'Please log in to manage your wishlist.';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php');
    exit;
}

$userId = getUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $result = $stmt->execute([$userId, $productId]);
        
        if ($result && $stmt->rowCount() > 0) {
            $_SESSION['message'] = 'Product removed from wishlist successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Product not found in wishlist.';
            $_SESSION['message_type'] = 'error';
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Error removing product from wishlist. Please try again.';
        $_SESSION['message_type'] = 'error';
        error_log("Error removing from wishlist: " . $e->getMessage());
    }
}

header('Location: wishlist.php');
exit;
 