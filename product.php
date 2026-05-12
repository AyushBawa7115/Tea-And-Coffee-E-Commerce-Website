<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';


// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 1;

// Get product details from database
try {
    // Get basic product info
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
        
        // Get product details
        $stmt = $pdo->prepare("SELECT * FROM product_details WHERE product_id = ?");
        $stmt->execute([$productId]);
        $details = $stmt->fetch();
        
        if ($details) {
            $product['details'] = $details['details'];
            $product['instructions'] = $details['instructions'];
        } else {
            $product['details'] = '<p>No detailed information available for this product.</p>';
            $product['instructions'] = '<p>No brewing instructions available for this product.</p>';
        }
    } else {
        // Product not found, redirect to home page
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    // Database error, use sample data
    $product = [
        'product_id' => $productId,
        'name' => 'Premium Assam Black Tea',
        'description' => 'Rich and malty black tea from the Assam region of India',
        'price' => 350,
        'rating' => 4.5,
        'images' => [
            'images/product1-1.jpg',
            'images/product1-2.jpg',
            'images/product1-3.jpg',
            'images/product1-4.jpg'
        ],
        'details' => '
            <p>Our Premium Assam Black Tea is sourced from the finest tea gardens in the Assam region of India. Known for its rich, malty flavor and bright color, this tea is perfect for those who enjoy a robust cup of tea.</p>
            <p>This tea is harvested during the second flush, which occurs in late spring and early summer, producing a tea with a full-bodied taste and a sweet, malty character.</p>
            <h4>Origin</h4>
            <p>Assam, India</p>
            <h4>Flavor Profile</h4>
            <p>Malty, rich, with subtle notes of caramel and a smooth finish</p>
            <h4>Caffeine Content</h4>
            <p>High</p>
            <h4>Ingredients</h4>
            <p>100% Assam black tea leaves</p>
            <h4>Package Contents</h4>
            <p>100g loose leaf tea in a resealable pouch</p>
        ',
        'instructions' => '
            <h4>Brewing Instructions</h4>
            <ol>
                <li>Heat fresh, filtered water to a rolling boil (212°F).</li>
                <li>Use 1 teaspoon (2g) of tea per 8oz cup.</li>
                <li>Steep for 3-5 minutes.</li>
                <li>For a stronger brew, use more tea or steep longer.</li>
            </ol>
            <h4>Storage</h4>
            <p>Store in a cool, dry place away from direct sunlight, moisture, and strong odors.</p>
            <h4>Shelf Life</h4>
            <p>Best consumed within 18 months of the production date for optimal flavor.</p>
        '
    ];
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isLoggedIn()) {
        // Store current page in session for redirect after login
        $_SESSION['redirect_after_login'] = "product.php?id=$productId";
        header('Location: login.php');
        exit;
    }
    
    $userId = getUserId();
    
    if (isset($_POST['action'])) {
        // Add to cart
        if ($_POST['action'] === 'add_to_cart') {
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            
            if (addToCart($pdo, $userId, $productId, $quantity)) {
                $message = 'Product added to cart successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to add product to cart. Please try again.';
                $messageType = 'error';
            }
        }
        
        // Add to wishlist
        if ($_POST['action'] === 'add_to_wishlist') {
            if (addToWishlist($pdo, $userId, $productId)) {
                $message = 'Product added to wishlist successfully!';
                $messageType = 'success';
            } else {
                $message = 'Failed to add product to wishlist. Please try again.';
                $messageType = 'error';
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
    <title><?php echo $product['name']; ?> - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
           body{ margin: 0;
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
</style>
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
            <div class="breadcrumb">
                <a href="index.php">Home</a> &gt;
                <a href="products.php">Products</a> &gt;
                <span><?php echo $product['name']; ?></span>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <div class="product-detail">
                <div class="product-images">
                    <div class="main-image">
                        <img id="main-product-image" src="<?php echo $product['images'][0]; ?>" alt="<?php echo $product['name']; ?>">
                    </div>
                    <div class="thumbnail-images">
                        <?php foreach ($product['images'] as $index => $image): ?>
                            <div class="thumbnail" onclick="changeMainImage('<?php echo $image; ?>')">
                                <img src="<?php echo $image; ?>" alt="<?php echo $product['name']; ?> <?php echo $index + 1; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="product-info-detail">
                    <h1><?php echo $product['name']; ?></h1> 
                    <div class="product-rating">
                        <?php
                        // Display stars based on rating
                        $rating = $product['rating'];
                        $fullStars = floor($rating);
                        $halfStar = $rating - $fullStars >= 0.5;
                        
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $fullStars) {
                                echo '<i class="fas fa-star"></i>';
                            } elseif ($i == $fullStars + 1 && $halfStar) {
                                echo '<i class="fas fa-star-half-alt"></i>';
                            } else {
                                echo '<i class="far fa-star"></i>';
                            }
                        }
                        ?>
                        <span>(<?php echo $product['rating']; ?> rating)</span>
                    </div>
                    <div class="product-price">
                        <span>INR.<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <p class="product-description"><?php echo $product['description']; ?></p>
                    
                    <form method="post" action="product.php?id=<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="action" value="add_to_cart">
                        <div class="product-quantity">
                            <label for="quantity">Quantity:</label>
                            <select id="quantity" name="quantity">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="product-actions">
                            <button type="submit" class="btn add-to-cart-btn">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                    </form>
                    
                    <form method="post" action="product.php?id=<?php echo $product['product_id']; ?>" style="display: inline;">
                        <input type="hidden" name="action" value="add_to_wishlist">
                        <button type="submit" class="btn wishlist-btn">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                    </form>
                    </div>

                    <div class="product-tabs">
                        <div class="tabs">
                            <button class="tab-btn active" data-tab="details">Product Details</button>
                            <button class="tab-btn" data-tab="instructions"> Instructions</button>
                        </div>
                        <div class="tab-content">
                            <div id="details" class="tab-pane active">
                                <?php echo $product['details']; ?>
                            </div>
                            <div id="instructions" class="tab-pane">
                                <?php echo $product['instructions']; ?>
                            </div>
                        </div>
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
 <script>
        function changeMainImage(imageSrc) {
            document.getElementById('main-product-image').src = imageSrc;
        }

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));

                    // Add active class to clicked button and corresponding pane
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
    <script src="js/script.js"></script>
    
</body>
</html>
