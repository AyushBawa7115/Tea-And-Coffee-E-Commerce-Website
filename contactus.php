<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - TeaCoffee</title>
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
        /* Contact Page Specific Styles */
        .contact-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #C8E6C9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .founder-image {
            width: 270px;
            height: 250px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .founder-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .contact-info {
            flex-grow: 1;
        }

        .contact-info h2 {
            color: #2c6b48; /* Example primary color from your theme */
            margin-bottom: 15px;
        }

        .contact-info p {
            line-height: 3.8;
            margin-bottom: 10px;
            color: #333; /* Adjust text color if needed */
        }

        .contact-info a {
            color: #007bff; /* Standard link color, adjust if needed */
            text-decoration: none;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        /* Ensure consistent link color with your theme */
        .contact-info a[href^="tel:"] {
            color: #2c6b48; /* Match your primary color for phone */
        }

     
        /* Consider adding more styles here to match your header, footer, etc. */
        /* For example, if your header has a specific background, replicate it here if needed */
        /* If your links have a specific color, use that for the 'a' tags */
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

    <div class="contact-container">
        <div class="founder-image">
            <img src="founder.jpg" alt="Ayush Bawa - Founder">
        </div>
        <div class="contact-info">
            <h2>Contact Us</h2>
            <p>We'd love to hear from you! Please feel free to reach out using the information below.</p>
            <p><strong>Founder:</strong> Mr. Ayush Bawa</p>
            <p><strong>Company:</strong> Steamy Cup</p>
            <p><strong>Phone:</strong> <a href="tel: +919877415174 , +1 (438) 348 2766">+919877415174 , +1 (438) 348 2766</a></p>
            <p><strong>Email:</strong> <a href="mailto:Ayushbawa7115@gmail.com">ayushbawa7115@gmail.com</a></p>
         
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
        
   

    // 2. Simple Email Link Protection (Basic Obfuscation - Optional)
    document.addEventListener('DOMContentLoaded', function() {
        const emailLink = document.querySelector('a[href^="mailto:"]');
        if (emailLink) {
            const rawEmail = emailLink.getAttribute('href').substring(7); // Remove "mailto:"
            let encodedEmail = '';
            for (let i = 0; i < rawEmail.length; i++) {
                encodedEmail += '&#' + rawEmail.charCodeAt(i) + ';';
            }
            emailLink.removeAttribute('href');
            emailLink.innerHTML = encodedEmail;

            // Optional: Add back the mailto link on hover (for usability)
            emailLink.addEventListener('mouseover', function() {
                this.innerHTML = rawEmail;
                this.setAttribute('href', 'mailto:' + rawEmail);
            });

            emailLink.addEventListener('mouseout', function() {
                this.removeAttribute('href');
                let encodedEmailOnOut = '';
                const currentEmail = this.innerHTML;
                for (let i = 0; i < currentEmail.length; i++) {
                    encodedEmailOnOut += '&#' + currentEmail.charCodeAt(i) + ';';
                }
                this.innerHTML = encodedEmailOnOut;
            });
        }
    });

</script>    
</body>
</html> 