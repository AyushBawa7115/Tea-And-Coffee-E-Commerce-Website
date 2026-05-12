<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
      
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

        .faq-item .answer {
    padding-left: 15px;
    color: #555;
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.4s ease-in-out, padding-top 0.4s ease-in-out;
}

.faq-item .answer.open {
    max-height: 500px; /* Adjust as needed */
    padding-top: 10px;
}

    .faqs-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #C8E6C9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        line-height: 1.6;
        color: #333;
    }

    .faqs-container h2 {
        color: #2c6b48; /* Example primary color */
        margin-bottom: 20px;
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out 0.2s;
    }

    .faqs-container h2.loaded {
        opacity: 1;
        transform: translateY(0);
    }

    .faq-item {
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        opacity: 0;
        transform: translateY(10px);
        transition: opacity 0.5s ease-out, transform 0.5s ease-out 0.3s;
    }

    .faq-item.loaded {
        opacity: 1;
        transform: translateY(0);
    }

    .faq-item h3 {
        color: #333;
        margin-top: 0;
        margin-bottom: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-item h3::after {
        content: '+';
        font-size: 1.2em;
        color: #555;
        transition: transform 0.3s ease-in-out;
    }

    .faq-item h3.open::after {
        content: '-';
        transform: rotate(45deg);
    }

    .faq-item .answer {
        display: none; /* Initially hidden */
        padding-left: 15px;
        color: #555;
        overflow: hidden; /* Keep this for the slide-down effect */
        max-height: 0; /* Animate from 0 to a larger value */
        transition: max-height 0.4s ease-in-out, padding-top 0.4s ease-in-out; /* Include padding-top for smoother transition */
    }

    .faq-item .answer.open {
        display: block; /* Ensure it's block when open */
        max-height: 500px; /* Adjust as needed for longer answers */
        padding-top: 10px; /* Add padding when visible */
    }

    /* You might need to adjust these colors to perfectly match your existing theme */
    body {
        font-family: sans-serif; /* Default font, adjust if your theme uses a specific font */
        background-color: #f4f4f4; /* Light background, adjust if your theme has a different background */
        color: #333; /* Default text color, adjust if your theme has a different text color */
        margin: 0;
        padding: 0;
    }

    /* Consider adding more styles here to match your header, footer, etc. */
</style>

   
</head>
<body>
<header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <img  src="logo3.png" alt="TeaCoffee Logo" >
                        <span>Steamy Cup </span>
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#tea">Tea</a></li>
                        <li><a href="#coffee">Coffee</a></li>
                        <li><a href="#accessories">Accessories</a></li>
                        <li><a href="#gifts">Gift Sets</a></li>
                    </ul>
                </nav>
                <div class="header-right">
                    <div class="search-bar">
                        <input  type="text" placeholder="Search products...">
                        <button><i class="fas fa-search"></i></button>
                    </div>
                    <div class="cart">
                        <a href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count">0</span>
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

    <div class="faqs-container">
        <h2 class="loaded">Frequently Asked Questions</h2>

        <div class="faq-item loaded">
            <h3 onclick="toggleAnswer(this)">What are your shipping options?</h3>
            <div class="answer">
                <p>We offer several shipping options, including standard and expedited delivery. Shipping costs and delivery times vary depending on your location. You can see the available options and their costs during the checkout process.</p>
            </div>
        </div>

       

        <div class="faq-item loaded">
            <h3 onclick="toggleAnswer(this)">Do you offer international shipping?</h3>
            <div class="answer">
                <p>Yes, we do offer international shipping to select countries. Please check our shipping information page or the checkout process to see if we ship to your location and to view the applicable shipping rates and estimated delivery times.</p>
            </div>
        </div>

        <div class="faq-item loaded">
            <h3 onclick="toggleAnswer(this)">How can I contact customer support?</h3>
            <div class="answer">
                <p>You can contact our customer support team via email at <a href="mailto:Ayushbawa7115@gmail.com" style="color: #007bff; text-decoration: none;">Ayushbawa7115@gmail.com</a> or by phone at <a href="tel:[Your Support Phone]" style="color: #2c6b48; text-decoration: none;">[Your Support Phone]</a>. Our support hours are Monday to Friday, 9 AM to 5 PM IST.</p>
            </div>
        </div>

        </div>
        <?php include 'headline-ticker.php'; ?>
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
        function toggleAnswer(question) {
            question.classList.toggle('open');
            const answer = question.nextElementSibling;
            answer.classList.toggle('open');
        }

        // Simple fade-in animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const elementsToAnimate = document.querySelectorAll('.faqs-container h2, .faq-item');
            elementsToAnimate.forEach(element => {
                setTimeout(() => {
                    element.classList.add('loaded');
                }, 100); // Slight delay for sequential effect
            });
        });
    </script>
</body>