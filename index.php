<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeaCoffee - Premium Tea and Coffee Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="animation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <style>
        body{
            margin: 0;
            padding: 0;
            background-color: #C8E6C9; /* Light Beige */
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
        <?php if (isset($_SESSION['message'])): ?>
            <div class="container">
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                    <p><?php echo $_SESSION['message']; ?></p>
                </div>
            </div>
            <?php 
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>
        
        <!-- Hero Slider Section -->
        <section class="hero-slider">
    <div class="slider-container">
        <div class="slider-wrapper">
            <div class="slide">
                <img src="images/black-coffee.jpg" alt="Premium Tea Collection">
                <div class="slide-content">
                    <h2>Premium Tea Collection</h2>
                    <p>Discover our handpicked selection of the finest teas from around the world</p>
                    <a href="#tea" class="btn">Shop Tea</a>
                </div>
            </div>
            <div class="slide">
                <img src="images/latte.jpg" alt="Artisan Coffee Beans">
                <div class="slide-content">
                    <h2>Artisan Coffee Beans</h2>
                    <p>Ethically sourced, freshly roasted coffee beans for the perfect brew</p>
                    <a href="#coffee" class="btn">Shop Coffee</a>
                </div>
            </div>
            <div class="slide">
                <img src="images/ginger-tea.png" alt="Exclusive Gift Sets">
                <div class="slide-content">
                    <h2>Exclusive Gift Sets</h2>
                    <p>Curated gift sets for tea and coffee lovers</p>
                    <a href="#gifts" class="btn">Shop Gifts</a>
                </div>
            </div>
            <div class="slide">
                <img src="images/herbal-tea.jpg" alt="Brewing Accessories">
                <div class="slide-content">
                    <h2>Brewing Accessories</h2>
                    <p>Everything you need to brew the perfect cup</p>
                    <a href="#accessories" class="btn">Shop Accessories</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="script.js"></script>

        <!-- Featured Products Section -->
        <section class="products-section">
            <div class="container">
                <div class="section-header">
                    <h2>Featured Products</h2>
                    
                </div>
                <div class="products-grid">
                    <?php
                    // In a real application, you would fetch this data from a database
                    $featuredProducts = [
                        [
                            'id' => 1,
                            'name' => 'Premium Assam Black Tea',
                            'description' => 'Rich and malty black tea from the Assam region of India',
                            'price' => 350,
                            'rating' => 4.5,
                            'image' => 'images/Assam.webp'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Ethiopian Yirgacheffe Coffee',
                            'description' => 'Bright, fruity coffee with notes of citrus and chocolate',
                            'price' => 439,
                            'rating' => 4.8,
                            'image' => 'images/yirga.jpeg'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Japanese Matcha Green Tea',
                            'description' => 'Ceremonial grade matcha powder for traditional preparation',
                            'price' => 539,
                            'rating' => 4.7,
                            'image' => 'images/green-tea.jpg'
                        ],
                        [
                            'id' => 4,
                            'name' => 'Colombian Supremo Coffee',
                            'description' => 'Well-balanced coffee with caramel sweetness and nutty undertones',
                            'price' => 450,
                            'rating' => 4.6,
                            'image' => 'images/colobium.webp'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Darjeeling First Flush Tea',
                            'description' => 'Light and floral black tea from the foothills of the Himalayas',
                            'price' => 499,
                            'rating' => 4.9,
                            'image' => 'images/darjleeng.jpg'
                        ]
                    ];

                    foreach ($featuredProducts as $product) {
                        include 'product-card.php';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Tea Collection Section -->
        <section id="tea" class="products-section">
            <div class="container">
                <div class="section-header">
                    <h2>Tea Collection</h2>
                  
                </div>
                <div class="products-grid">
                    <?php
                    // In a real application, you would fetch this data from a database
                    $teaProducts = [
                        [
                            'id' => 6,
                            'name' => 'Earl Grey Tea',
                            'description' => 'Classic black tea infused with bergamot oil',
                            'price' => 249,
                            'rating' => 4.3,
                            'image' => 'images/earl.avif'
                        ],
                        [
                            'id' => 7,
                            'name' => 'Chamomile Herbal Tea',
                            'description' => 'Soothing herbal infusion with apple-like flavor',
                            'price' => 249,
                            'rating' => 4.4,
                            'image' => 'images/herbal-tea.jpg'
                        ],
                        [
                            'id' => 8,
                            'name' => 'Jasmine Green Tea',
                            'description' => 'Green tea scented with jasmine blossoms',
                            'price' => 350,
                            'rating' => 4.6,
                            'image' => 'images/jasmeen1.jpg'
                        ],
                        [
                            'id' => 9,
                            'name' => 'Rooibos Red Tea',
                            'description' => 'Caffeine-free herbal tea from South Africa',
                            'price' => 280,
                            'rating' => 4.2,
                            'image' => 'images/red.jpg'
                        ]
                    ];

                    foreach ($teaProducts as $product) {
                        include 'product-card.php';
                    }
                    ?>
                </div>
            </div>
        </section>

        <!-- Coffee Collection Section -->
        <section id="coffee" class="products-section">
            <div class="container">
                <div class="section-header">
                    <h2>Coffee Collection</h2>
                 
                </div>
                <div class="products-grid">
                    <?php
                    // In a real application, you would fetch this data from a database
                    $coffeeProducts = [
                        [
                            'id' => 10,
                            'name' => 'French Roast Coffee',
                            'description' => 'Dark roast coffee with smoky, intense flavor',
                            'price' => 370,
                            'rating' => 4.5,
                            'image' => 'images/roast.webp'
                        ],
                        [
                            'id' => 11,
                            'name' => 'Kona Coffee',
                            'description' => 'Premium coffee grown on the slopes of Hualalai in Hawaii',
                            'price' => 1150,
                            'rating' => 4.9,
                            'image' => 'images/kona3.jpg'
                        ],
                        [
                            'id' => 12,
                            'name' => 'Espresso Blend',
                            'description' => 'Bold, rich blend perfect for espresso machines',
                            'price' => 444,
                            'rating' => 4.7,
                            'image' => 'images/espresso.webp'
                        ],
                        [
                            'id' => 13,
                            'name' => 'Decaf Breakfast Blend',
                            'description' => 'Smooth, caffeine-free medium roast coffee',
                            'price' => 410,
                            'rating' => 4.1,
                            'image' => 'images/breakfastblend.jpg'
                        ]
                    ];

                    foreach ($coffeeProducts as $product) {
                        include 'product-card.php';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
    
        <!-- gifts Collection Section -->
        <section id="gifts" class="products-section">
            <div class="container">
                <div class="section-header">
                    <h2>Gifts Collection</h2>
                 
                </div>
                <div class="products-grid">
                    <?php
                    // In a real application, you would fetch this data from a database
                    $coffeeProducts = [
                        [
                            'id' => 14,
                            'name' => 'Deluxe Tea Gift Box',
                            'description' => 'A beautiful gift box containing 5 premium loose leaf teas, perfect for any tea lover.',
                            'price' => 750,
                            'rating' => 4.5,
                            'image' => 'images/deluxe1.jpg'
                        ],
                        [
                            'id' => 15,
                            'name' => 'Coffee Lovers Gift Set',
                            'description' => 'A curated selection of 3 specialty coffees with a stylish mug and brewing guide.',
                            'price' => 850,
                            'rating' => 4.9,
                            'image' => 'images/coffeelover1.webp'
                        ],
                        [
                            'id' => 16,
                            'name' => 'Tea & Biscuits Gift Basket',
                            'description' => 'A charming gift basket with assorted teas and gourmet biscuits.',
                            'price' => 600,
                            'rating' => 4.7,
                            'image' => 'images/basket1.webp'
                        ],
                        [
                            'id' => 17,
                            'name' => 'Matcha Ceremony Set',
                            'description' => 'Traditional Japanese matcha set with bowl, whisk, and premium matcha powder.',
                            'price' => 739,
                            'rating' => 4.1,
                            'image' => 'images/matchaset2.webp'
                        ]
                    ];

                    foreach ($coffeeProducts as $product) {
                        include 'product-card.php';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
    
        <!-- accessories Collection Section -->
        <section id="accessories" class="products-section">
            <div class="container">
                <div class="section-header">
                    <h2>Accessories Collection</h2>
                 
                </div>
                <div class="products-grid">
                    <?php
                    // In a real application, you would fetch this data from a database
                    $coffeeProducts = [
                        [
                            'id' => 18,
                            'name' => 'Coffee Subscription Box',
                            'description' => 'A 3-month subscription of premium coffee delivered monthly.',
                            'price' => 1030,
                            'rating' => 4.5,
                            'image' => 'images/coffeesub2.webp'
                        ],
                        [
                            'id' => 19,
                            'name' => 'Ceramic Pour-Over Coffee Dripper',
                            'description' => 'Elegant ceramic pour-over coffee maker for a perfect brew every time.',
                            'price' => 450,
                            'rating' => 4.9,
                            'image' => 'images/coffeedipper2.webp'
                        ],
                        [
                            'id' => 20,
                            'name' => 'Glass Teapot with Infuser',
                            'description' => 'Beautiful heat-resistant glass teapot with removable stainless steel infuser.',
                            'price' => 999,
                            'rating' => 4.7,
                            'image' => 'images/glassinfuser2.jpg'
                        ],
                        [
                            'id' => 21,
                            'name' => 'Electric Gooseneck Kettle',
                            'description' => 'Precision electric kettle with temperature control for perfect brewing.',
                            'price' => 1300,
                            'rating' => 4.5,
                            'image' => 'images/kettle2.webp'
                        ],
                        [
                            'id' => 22,
                            'name' => 'Stainless Steel Tea Infuser',
                            'description' => 'High-quality mesh tea infuser for loose leaf tea.',
                            'price' => 800,
                            'rating' => 4.5,
                            'image' => 'images/steelinfuser.jpg'
                        ],
                        [
                            'id' => 23,
                            'name' => 'Coffee Bean Grinder',
                            'description' => 'Adjustable burr grinder for the perfect coffee grind.',
                            'price' => 1150,
                            'rating' => 4.5,
                            'image' => 'images/bean.webp'
                        ],
                    ];

                    foreach ($coffeeProducts as $product) {
                        include 'product-card.php';
                    }
                    ?>
                </div>
            </div>
        </section>
   
     <?php include 'headline-ticker.php'; ?>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Shop</h3>
                    <ul>
                        <li><a href="#tea">Tea</a></li>
                        <li><a href="#coffee">Coffee</a></li>
                        <li><a href="#accessories">Accessories</a></li>
                        <li><a href="#gifts">Gift Sets</a></li>
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
    <div id="offerPopup" style="display:none;">
        <h3>Limited Time Offer!</h3>
        <p>Get 20% off our Kona Coffee for the next 24 hours!</p>
        <a href="index.php#tea" style="display:inline-block; background-color:#007bff; color:#fff; padding:10px 15px; text-decoration:none; border-radius:5px;">Shop Now</a>
        <button id="closePopup" style="position:absolute; top:5px; right:5px; border:none; background:none; cursor:pointer;">&times;</button>
    </div>

    <div id="overlay" style="display:none;"></div>

    <script>
      setTimeout(function() {
        document.getElementById('offerPopup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
      }, 90000); // 90000 milliseconds = 1.30 minutes

      document.getElementById('closePopup').addEventListener('click', function() {
        document.getElementById('offerPopup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      });

      document.getElementById('overlay').addEventListener('click', function() {
        document.getElementById('offerPopup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
      });
    </script> <style>
        /* Your existing CSS */
      
        #offerPopup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #fff;
  border-radius: 12px; /* More rounded corners */
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Softer shadow */
  padding: 30px; /* More padding for spaciousness */
  z-index: 1000;
  text-align: center;
  font-family: sans-serif; /* A clean, readable font */
}

#offerPopup h3 {
  color: #2e7d32; /* A nice green, like the example */
  font-size: 1.5em;
  margin-top: 0;
  margin-bottom: 10px;
  display: flex; /* To align the leaf icon */
  align-items: center;
  justify-content: center;
}

#offerPopup h3::before {
  content: "\2741"; /* Unicode for a leaf symbol */
  font-size: 1.2em;
  color: #64dd17; /* A brighter green for the leaf */
  margin-right: 8px;
}

#offerPopup p {
  font-size: 1.1em;
  color: #333;
  margin-bottom: 20px;
}

#offerPopup a { /* Style the "Grab the Deal" button */
  display: inline-block;
  background-color: #2e7d32; /* Matching button color */
  color: #fff;
  padding: 12px 25px;
  text-decoration: none;
  border-radius: 8px;
  font-weight: bold;
  transition: background-color 0.3s ease; /* Smooth hover effect */
}

#offerPopup a:hover {
  background-color: #1b5e20; /* Darker green on hover */
}

#closePopup {
  position: absolute;
  top: 10px;
  right: 10px;
  border: none;
  background: none;
  cursor: pointer;
  font-size: 1.5em;
  color: #777;
  opacity: 0.7;
  transition: opacity 0.3s ease;
}

#closePopup:hover {
  opacity: 1;
  color: #333;
}

#overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 999;
}
    </style>
</body>
</html>