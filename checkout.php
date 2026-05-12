<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    // Store current page in session for redirect after login
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

$userId = getUserId();
$cartItems = getCartItems($pdo, $userId);
$cartTotal = getCartTotal($pdo, $userId);

// If cart is empty, redirect to cart page
if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Get user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$errors = [];
$orderComplete = false;
$orderId = null;

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $zipCode = trim($_POST['zip_code'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? '';
    
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }
    
    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }
    
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    
    if (empty($city)) {
        $errors[] = "City is required";
    }
    
    if (empty($state)) {
        $errors[] = "State is required";
    }
    
    if (empty($zipCode)) {
        $errors[] = "ZIP code is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($paymentMethod)) {
        $errors[] = "Payment method is required";
    }
    
    // Process order if no errors
    if (empty($errors)) {
        // Format shipping address
        $shippingAddress = "$firstName $lastName\n$address\n$city, $state $zipCode\n$phone";
        
        // Create order
        $orderId = createOrder($pdo, $userId, $cartTotal * 1.1, $shippingAddress, $paymentMethod);
        
        if ($orderId) {
            $orderComplete = true;
            
            // Update user information if it's empty
            if (empty($user['address']) || empty($user['city']) || empty($user['state']) || empty($user['zip_code']) || empty($user['phone'])) {
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET address = ?, city = ?, state = ?, zip_code = ?, phone = ?
                    WHERE user_id = ?
                ");
                $stmt->execute([$address, $city, $state, $zipCode, $phone, $userId]);
            }
        } else {
            $errors[] = "Failed to process your order. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
        .checkout-container {
            margin: 40px 0;
        }
        
        .checkout-title {
            margin-bottom: 30px;
        }
        
        .checkout-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        
        @media (min-width: 768px) {
            .checkout-content {
                flex-direction: row;
            }
        }
        
        .checkout-form {
            flex: 3;
            background-color: #C8E6C9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        .checkout-summary {
            flex: 2;
            background-color: #C8E6C9;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            align-self: flex-start;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section-title {
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-row {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        @media (min-width: 576px) {
            .form-row {
                flex-direction: row;
            }
        }
        
        .form-group {
            flex: 1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .payment-methods {
            margin-top: 15px;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .payment-method:hover {
            background-color: #f9f9f9;
        }
        
        .payment-method input {
            margin-right: 10px;
        }
        
        .payment-method-info {
            margin-left: 25px;
            margin-top: 5px;
            display: none;
        }
        
        .payment-method-info.active {
            display: block;
        }
        
        .order-summary-title {
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .order-summary-items {
            margin-bottom: 20px;
        }
        
        .order-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .order-item-image {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }
        
        .order-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .order-item-details {
            flex: 1;
        }
        
        .order-item-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .order-item-price {
            color: #4a6741;
        }
        
        .order-item-quantity {
            color: #777;
            font-size: 14px;
        }
        
        .order-summary-totals {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .order-summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .order-summary-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        .place-order-btn {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            margin-top: 20px;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .order-success {
            text-align: center;
            padding: 50px 0;
        }
        
        .order-success i {
            font-size: 48px;
            color: #4a6741;
            margin-bottom: 20px;
        }
        
        .order-success h2 {
            margin-bottom: 15px;
        }
        
        .order-success p {
            margin-bottom: 20px;
            color: #777;
        }
        
        .order-success .order-number {
            font-weight: bold;
            color: #4a6741;
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

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="checkout-container">
                <h1 class="checkout-title">Checkout</h1>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ($orderComplete): ?>
                    <div class="order-success">
                        <i class="fas fa-check-circle"></i>
                        <h2>Thank you for your order!</h2>
                        <p>Your order has been placed successfully.</p>
                        <p>Order Number: <span class="order-number">#<?php echo $orderId; ?></span></p>
                        <p>We've sent a confirmation email with your order details.</p>
                        <a href="index.php" class="btn">Continue Shopping</a>
                    </div>
                <?php else: ?>
                    <div class="checkout-content">
                        <div class="checkout-form">
                            <form method="post" action="checkout.php">
                                <div class="form-section">
                                    <h2 class="form-section-title">Shipping Information</h2>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" id="address" name="address" value="<?php echo $user['address'] ?? ''; ?>" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" id="city" name="city" value="<?php echo $user['city'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <input type="text" id="state" name="state" value="<?php echo $user['state'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="zip_code">ZIP Code</label>
                                            <input type="text" id="zip_code" name="zip_code" value="<?php echo $user['zip_code'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-section">
                                    <h2 class="form-section-title">Payment Method</h2>
                                    <div class="payment-methods">
                                        <div class="payment-method">
                                            <input type="radio" id="credit_card" name="payment_method" value="credit_card" checked>
                                            <label for="credit_card">Credit Card</label>
                                        </div>
                                        <div class="payment-method-info" id="credit_card_info">
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="card_number">Card Number</label>
                                                    <input type="text" id="card_number" placeholder="1234 5678 9012 3456">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label for="expiry_date">Expiry Date</label>
                                                    <input type="text" id="expiry_date" placeholder="MM/YY">
                                                </div>
                                                <div class="form-group">
                                                    <label for="cvv">CVV</label>
                                                    <input type="text" id="cvv" placeholder="123">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="payment-method">
                                            <input type="radio" id="paypal" name="payment_method" value="paypal">
                                            <label for="paypal">PayPal</label>
                                        </div>
                                        
                                        <div class="payment-method">
                                            <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery">
                                            <label for="cash_on_delivery">Cash on Delivery</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn place-order-btn">Place Order</button>
                            </form>
                        </div>
                        
                        <div class="checkout-summary">
                            <h2 class="order-summary-title">Order Summary</h2>
                            <div class="order-summary-items">
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="order-item">
                                        <div class="order-item-image">
                                            <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                                        </div>
                                        <div class="order-item-details">
                                            <div class="order-item-name"><?php echo $item['name']; ?></div>
                                            <div class="order-item-price">INR.<?php echo number_format($item['price'], 2); ?></div>
                                            <div class="order-item-quantity">Qty: <?php echo $item['quantity']; ?></div>
                                        </div>
                                        <div class="order-item-total">
                                            INR.<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-summary-totals">
                                <div class="order-summary-row">
                                    <span>Subtotal</span>
                                    <span>INR.<?php echo number_format($cartTotal, 2); ?></span>
                                </div>
                                <div class="order-summary-row">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <div class="order-summary-row">
                                    <span>Tax (10%)</span>
                                    <span>INR.<?php echo number_format($cartTotal * 0.1, 2); ?></span>
                                </div>
                                <div class="order-summary-row order-summary-total">
                                    <span>Total</span>
                                    <span>INR.<?php echo number_format($cartTotal * 1.1, 2); ?></span>
                                </div>
                            </div>
                        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Show credit card info by default
            document.getElementById('credit_card_info').classList.add('active');
            
            // Payment method selection
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const paymentInfos = document.querySelectorAll('.payment-method-info');
            
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Hide all payment info sections
                    paymentInfos.forEach(info => {
                        info.classList.remove('active');
                    });
                    
                    // Show selected payment info
                    if (this.value === 'credit_card') {
                        document.getElementById('credit_card_info').classList.add('active');
                    }
                });
            });
        });
    </script>
    <script src="js/script.js"></script>
</body>
</html>