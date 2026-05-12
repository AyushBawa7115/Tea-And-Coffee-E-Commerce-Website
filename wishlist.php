<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user not logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'wishlist.php';
    header('Location: login.php');
    exit;
}

$userId = getUserId();

// Fetch wishlist products
try {
    $stmt = $pdo->prepare("
        SELECT 
            w.product_id,
            p.name AS product_name,
            p.price,
            (SELECT pi.image_path FROM product_images pi WHERE pi.product_id = p.product_id LIMIT 1) AS image_path
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        WHERE w.user_id = ?
    ");
    $stmt->execute([$userId]);
    $wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching wishlist: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="animation.css">
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

        /* Wishlist Page Specific Styles */
        *{
            margin: 0;
            padding: 0;
        }
        .wishlist-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .wishlist-title {
            font-size: 28px;
            margin-bottom: 20px;
            color: #3a4d2d;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .wishlist-item {
            background-color: #fff;
            border: 1px solid #d8e3d2;
            border-radius: 12px;
            padding: 15px;
            transition: 0.3s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .wishlist-item:hover {
            transform: scale(1.03);
        }

        .wishlist-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            color: #495a2a;
        }

        .product-price {
            color: #6d8a3c;
            font-weight: bold;
            margin-top: 5px;
        }

        .remove-btn {
            margin-top: 10px;
            display: inline-block;
            background-color: #a24e2d;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .remove-btn:hover {
            background-color: #872f16;
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


    <main class="wishlist-page">
        <div class="wishlist-container">
            <h1 class="wishlist-title">My Wishlist</h1>
            <?php if (empty($wishlistItems)): ?>
                <p>Your wishlist is empty.</p>
            <?php else: ?>
                <div class="wishlist-grid">
                    <?php foreach ($wishlistItems as $item): ?>
                        <div class="wishlist-item">
                            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <h3 class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                            <p class="product-price">INR.<?php echo number_format($item['price'], 2); ?></p>
                            <form method="post" action="remove_wishlist.php" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" class="remove-btn">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

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

    <script src="script.js"></script>
</body>
</html>
