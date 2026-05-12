<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store current page in session for redirect after login
    $_SESSION['redirect_after_login'] = 'cart.php';
    header('Location: login.php');
    exit;
}

$userId = getUserId();
$cartItems = getCartItems($pdo, $userId);
$cartTotal = getCartTotal($pdo, $userId);

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Update quantity
        if ($_POST['action'] === 'update' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
            $cartId = $_POST['cart_id'];
            $quantity = max(1, intval($_POST['quantity']));
            
            if (updateCartQuantity($pdo, $cartId, $quantity)) {
                header('Location: cart.php');
                exit;
            }
        }
        
        // Remove item
        if ($_POST['action'] === 'remove' && isset($_POST['cart_id'])) {
            $cartId = $_POST['cart_id'];
            
            if (removeFromCart($pdo, $cartId)) {
                header('Location: cart.php');
                exit;
            }
        }
        
        // Move to wishlist
        if ($_POST['action'] === 'move_to_wishlist' && isset($_POST['cart_id']) && isset($_POST['product_id'])) {
            $cartId = $_POST['cart_id'];
            $productId = $_POST['product_id'];
            
            // Add to wishlist
            if (addToWishlist($pdo, $userId, $productId)) {
                // Remove from cart
                if (removeFromCart($pdo, $cartId)) {
                    header('Location: cart.php?moved=1');
                    exit;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Header Styles */
        body{
            margin: 0;
            padding: 0;
        }
   .container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

a {
    text-decoration: none;
    color: #2C6B48;  /* Forest Green */
}

.btn {
    display: inline-block;
    background-color: #2C6B48;  /* Forest Green */
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #4A7C49;  /* Moss Green */
}

header {
    background-color: #556B2F; /* Muted Olive Green */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

header .logo a {
    color: #F5F5DC; /* Light Beige for the logo text */
}

header nav ul li a {
    color: #F5F5DC; /* Light Beige for navigation links */
}

header .search-bar input {
    border: 1px solid #F5F5DC; /* Light Beige border for the search bar */
}

header .search-bar button {
    color: #F5F5DC; /* Light Beige for search button text */
}

/* Cart Icon Color */
header .cart i {
    color: #A9D08E; /* Soft Light Green for the cart icon */
}

header .cart i:hover {
    color: #F5F5DC; /* Light Beige when hovering over the cart icon */
}

/* Cart Count Color */
header .cart-count {
    background-color: #A9D08E; /* Soft Light Green for the cart count bubble */
}

/* Sign-In Link Color */
header .user-account a {
    color: #A9D08E; /* Soft Light Green for the sign-in link */
}

header .user-account a:hover {
    color: #F5F5DC; /* Light Beige when hovering over the sign-in link */
}

.user-name {
    color: #F5F5DC;
    font-weight: bold;
    margin-right: 5px;
}

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        header .search-bar {
            margin-left: 20px;
            white-space: nowrap;
        }

        header .user-account {
            white-space: nowrap;
        }

        .mobile-menu-toggle {
            display: none;
        }

        /* Cart Page Specific Styles */
        body {
            margin:0;
        }
        .cart-container {
            background-color: #C8E6C9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 40px 0;
        }
        
        .cart-title {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .cart-empty {
            text-align: center;
            padding: 50px 0;
        }
        
        .cart-empty i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .cart-empty p {
            margin-bottom: 20px;
            color: #777;
        }
        
        .cart-item {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }
        
        .cart-item-image {
            width: 120px;
            height: 120px;
            margin-right: 20px;
        }
        
        .cart-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .cart-item-details {
            flex: 1;
        }
        
        .cart-item-name {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .cart-item-price {
            font-weight: bold;
            color: #4a6741;
            margin-bottom: 10px;
        }
        
        .cart-item-actions {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        
        .quantity-control button {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .quantity-control input {
            width: 50px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
            border-left: none;
            border-right: none;
        }
        
        .cart-item-remove, .cart-item-wishlist {
            background: none;
            border: none;
            color: #777;
            cursor: pointer;
            margin-right: 15px;
            display: flex;
            align-items: center;
        }
        
        .cart-item-remove:hover, .cart-item-wishlist:hover {
            color: #4a6741;
        }
        
        .cart-item-remove i, .cart-item-wishlist i {
            margin-right: 5px;
        }
        
        .cart-summary {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .cart-summary-total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .continue-shopping {
            display: flex;
            align-items: center;
        }
        
        .continue-shopping i {
            margin-right: 5px;
        }
        
        .checkout-btn {
            padding: 12px 30px;
            font-size: 16px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        a {
            text-decoration: none;
            color: #2C6B48;  /* Forest Green */
        }

        .btn {
            display: inline-block;
            background-color: #2C6B48;  /* Forest Green */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #4A7C49;  /* Moss Green */
        }

        header {
            background-color: #556B2F; /* Muted Olive Green */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        header .logo a {
            color: #F5F5DC; /* Light Beige for the logo text */
        }

        header nav ul li a {
            color: #F5F5DC; /* Light Beige for navigation links */
        }

        header .search-bar input {
            border: 1px solid #F5F5DC; /* Light Beige border for the search bar */
        }

        header .search-bar button {
            color: #F5F5DC; /* Light Beige for search button text */
        }

        /* Cart Icon Color */
        header .cart i {
            color: #A9D08E; /* Soft Light Green for the cart icon */
        }

        header .cart i:hover {
            color: #F5F5DC; /* Light Beige when hovering over the cart icon */
        }

        /* Cart Count Color */
        header .cart-count {
            background-color: #A9D08E; /* Soft Light Green for the cart count bubble */
        }

        /* Sign-In Link Color */
        header .user-account a {
            color: #A9D08E; /* Soft Light Green for the sign-in link */
        }

        header .user-account a:hover {
            color: #F5F5DC; /* Light Beige when hovering over the sign-in link */
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <img src="logo3.png" alt="TeaCoffee Logo">
                        <span>TeaCoffee</span>
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php#tea">Tea</a></li>
                        <li><a href="index.php#coffee">Coffee</a></li>
                        <li><a href="index.php#accessories">Accessories</a></li>
                        <li><a href="index.php#gifts">Gift&nbsp;Sets</a></li>
                    </ul>
                </nav>
                <div class="header-right">
                    <div class="search-bar">
                        <form action="search.php" method="get">
                            <input type="text" name="q" placeholder="Search products..." required>
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <div class="cart">
                        <a href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo isLoggedIn() ? getCartCount($pdo, getUserId()) : '0'; ?></span>
                        </a>
                    </div>
                    <div class="user-account">
                        <?php if (isLoggedIn()): ?>
                            <?php 
                            $userId = getUserId();
                            $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
                            $stmt->execute([$userId]);
                            $user = $stmt->fetch();
                            ?>
                            <span class="user-name"><?php echo htmlspecialchars($user['username']); ?></span> | 
                            <a href="account.php">My Account</a> | <a href="wishlist.php">Wishlist</a>
                        <?php else: ?>
                            <a href="login.php">Sign In</a> | <a href="wishlist.php">Wishlist</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>

    <main class="cart-page">
        <div class="container">
            <div class="cart-container">
                <h1 class="cart-title">Shopping Cart</h1>
                
                <?php if (isset($_GET['moved']) && $_GET['moved'] == 1): ?>
                    <div class="alert alert-success">
                        <p>Item moved to wishlist successfully.</p>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($cartItems)): ?>
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart"></i>
                        <h2>Your cart is empty</h2>
                        <p>Looks like you haven't added any products to your cart yet.</p>
                        <a href="index.php" class="btn">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                            </div>
                            <div class="cart-item-details">
                                <h3 class="cart-item-name"><?php echo $item['name']; ?></h3>
                                <div class="cart-item-price">INR.<?php echo number_format($item['price'], 2); ?></div>
                                <p class="cart-item-description"><?php echo $item['description']; ?></p>
                                
                                <div class="cart-item-actions">
                                    <form method="post" class="quantity-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <div class="quantity-control">
                                            <button type="button" class="quantity-btn minus" onclick="decrementQuantity(this.parentNode)">-</button>
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99" readonly>
                                            <button type="button" class="quantity-btn plus" onclick="incrementQuantity(this.parentNode)">+</button>
                                        </div>
                                    </form>
                                    
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="move_to_wishlist">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <button type="submit" class="cart-item-wishlist">
                                            <i class="far fa-heart"></i> Save for later
                                        </button>
                                    </form>
                                    
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <button type="submit" class="cart-item-remove">
                                            <i class="far fa-trash-alt"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="cart-item-subtotal">
                                <strong>INR.<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="cart-summary">
                        <div class="cart-summary-row">
                            <span>Subtotal</span>
                            <span>INR.<?php echo number_format($cartTotal, 2); ?></span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <div class="cart-summary-row">
                            <span>Tax</span>
                            <span>INR.<?php echo number_format($cartTotal * 0.1, 2); ?></span>
                        </div>
                        <div class="cart-summary-row cart-summary-total">
                            <span>Total</span>
                            <span>INR.<?php echo number_format($cartTotal * 1.1, 2); ?></span>
                        </div>
                    </div>
                    
                    <div class="cart-actions">
                        <a href="index.php" class="continue-shopping">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <a href="checkout.php" class="btn checkout-btn">Proceed to Checkout</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include 'headline-ticker.php'; ?>
    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Shop</h3>
                    <ul>
                    <li><a href="index.php#tea">Tea</a></li>
                        <li><a href="index.php#coffee">Coffee</a></li>
                        <li><a href="index.php#accessories">Accessories</a></li>
                        <li><a href="index.php#gifts">Gift Sets</a></li>
                     </ul>
                </div>
                <div class="footer-column">
                    <h3>Company</h3>
                    <ul>
                        <li><a href="aboutus.php">About Us</a></li>
                     
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="contactus.php">Contact Us</a></li>
                        <li><a href="FAQ.php">FAQs</a></li>
              
                    </ul>
                </div>
                <div class="footer-column newsletter">
                    <h3>Newsletter</h3>
                    <p>Subscribe to our newsletter for the latest products and offers.</p>
                    <form action="subscribe.php" method="post">
                        <input type="email" name="email" placeholder="Email" required>
                        <button type="submit" class="btn">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 TeaCoffee. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function incrementQuantity(container) {
            const input = container.querySelector('input');
            const currentValue = parseInt(input.value);
            if (currentValue < 99) {
                input.value = currentValue + 1;
                container.closest('form').submit();
            }
        }
        
        function decrementQuantity(container) {
            const input = container.querySelector('input');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                container.closest('form').submit();
            }
        }
    </script>
    <script src="js/script.js"></script>
</body>
</html>