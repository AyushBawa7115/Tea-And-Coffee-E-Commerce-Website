<?php
require_once 'db_connect.php';
require_once 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'wishlist.php';
    header('Location: login.php');
    exit;
}

$userId = getUserId();
$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$action = $_POST['action'] ?? '';

if ($productId > 0) {
    if ($action === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        $_SESSION['message'] = 'Product removed from wishlist.';
    } elseif ($action === 'add_to_cart') {
        if (addToCart($pdo, $userId, $productId, 1)) {
            $pdo->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?")->execute([$userId, $productId]);
            $_SESSION['message'] = 'Product moved to cart.';
        } else {
            $_SESSION['message'] = 'Failed to move product to cart.';
        }
    }
}

header('Location: ../wishlist.php');
exit;
