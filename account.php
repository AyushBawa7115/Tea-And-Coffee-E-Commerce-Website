<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = getUserId();

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Get user's cart items
$cartItems = getCartItems($pdo, $userId);
$cartTotal = getCartTotal($pdo, $userId);

// Get user's wishlist items
$wishlistItems = getWishlistItems($pdo, $userId);

if (isLoggedIn()) {
    $userId = getUserId();
    $stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if ($user) {
        $username = htmlspecialchars($user['username']);
        $email = htmlspecialchars($user['email']);
        $memberSince = date('F j, Y', strtotime($user['created_at']));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .account-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .account-header {
            background-color: #C8E6C9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .account-header h1 {
            color: #2C6B48;
            margin-bottom: 10px;
        }

        .account-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        .account-sidebar {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .account-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .account-sidebar li {
            margin-bottom: 10px;
        }

        .account-sidebar a {
            display: block;
            padding: 10px;
            color: #2C6B48;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .account-sidebar a:hover {
            background-color: #C8E6C9;
        }

        .account-main {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .section-title {
            color: #2C6B48;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .user-info {
            margin-bottom: 30px;
        }

        .user-info p {
            margin-bottom: 10px;
        }

        .cart-summary {
            margin-bottom: 30px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
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
            font-weight: bold;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #2C6B48;
            font-weight: bold;
        }

        .cart-total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }

        .wishlist-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .wishlist-item {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .wishlist-item-image {
            width: 100%;
            height: 150px;
            margin-bottom: 10px;
        }

        .wishlist-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .wishlist-item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .wishlist-item-price {
            color: #2C6B48;
            font-weight: bold;
        }

        .user-name {
            color: #F5F5DC;
            font-weight: bold;
            margin-right: 5px;
        }
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

        .logout-btn {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #f5c6cb;
            color: #721c24;
        }

        .logout-btn i {
            margin-right: 5px;
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
            <div class="account-container">
                <div class="account-header">
                    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
                    <p>Manage your account, view your orders, and update your preferences.</p>
                </div>

                <div class="account-content">
                    <div class="account-sidebar">
                        <ul>
                            <li><a href="#profile">Profile Information</a></li>
                            <li><a href="cart.php">Shopping Cart</a></li>
                            <li><a href="wishlist.php">Wishlist</a></li>
                            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>

                    <div class="account-main">
                        <section id="profile" class="user-info">
                            <h2 class="section-title">Profile Information</h2>
                            <div class="account-info">
                                <h3>Account Information</h3>
                                <p><strong>Username:</strong> <?php echo $username ?? 'Not available'; ?></p>
                                <p><strong>Email:</strong> <?php echo $email ?? 'Not available'; ?></p>
                                <p><strong>Member Since:</strong> <?php echo $memberSince ?? 'Not available'; ?></p>
                            </div>
                        </section>

                        <section id="cart" class="cart-summary">
                            <h2 class="section-title">Shopping Cart</h2>
                            <?php if (empty($cartItems)): ?>
                                <p>Your cart is empty.</p>
                            <?php else: ?>
                                <?php foreach ($cartItems as $item): ?>
                                    <div class="cart-item">
                                        <div class="cart-item-image">
                                            <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                                        </div>
                                        <div class="cart-item-details">
                                            <div class="cart-item-name"><?php echo $item['name']; ?></div>
                                            <div class="cart-item-price">INR.<?php echo number_format($item['price'], 2); ?></div>
                                            <div>Quantity: <?php echo $item['quantity']; ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="cart-total">
                                    Total: INR.<?php echo number_format($cartTotal, 2); ?>
                                </div>
                                <a href="cart.php" class="btn">View Full Cart</a>
                            <?php endif; ?>
                        </section>

                        <section id="wishlist" class="wishlist-summary">
                            <h2 class="section-title">Wishlist</h2>
                            <?php if (empty($wishlistItems)): ?>
                                <p>Your wishlist is empty.</p>
                            <?php else: ?>
                                <div class="wishlist-items">
                                    <?php foreach ($wishlistItems as $item): ?>
                                        <div class="wishlist-item">
                                            <div class="wishlist-item-image">
                                                <img src="<?php echo $item['image_path']; ?>" alt="<?php echo $item['name']; ?>">
                                            </div>
                                            <div class="wishlist-item-name"><?php echo $item['name']; ?></div>
                                            <div class="wishlist-item-price">INR.<?php echo number_format($item['price'], 2); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <a href="wishlist.php" class="btn">View Full Wishlist</a>
                            <?php endif; ?>
                        </section>
                    </div>
                </div>
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

    <script src="js/script.js"></script>
</body>
</html> 