<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* --- About Us Page Specific Styles --- */
        .about-container {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #C8E6C9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            line-height: 1.6;
            color: #333;
        }

        .about-container h2 {
            color: #2c6b48; /* Example primary color */
            margin-bottom: 15px;
        }

        .company-details {
            margin-bottom: 30px;
        }

        .testimonials-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .testimonial {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #fff;
        }

        .testimonial p.quote {
            font-style: italic;
            margin-bottom: 10px;
            color: #555;
        }

        .testimonial p.author {
            text-align: right;
            font-weight: bold;
            color: #2c6b48; /* Example primary color */
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
        /* Consider adding more styles here to match your header, footer, etc. */
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
    
    <div class="about-container">
        <h2>About Steamy Cup</h2>

        <div class="company-details">
            <p>Welcome to Steamy Cup! We are passionate about providing high-quality artisanal teas and coffees, curating unique gift experiences for tea and coffee lovers.</p>

            <p>Founded by Mr.Ayush Bawa in 2023, our journey began with a simple idea: providing tea and coffee to every corner, especiallly in small villages. Driven by  a commitment to quality, a love for community, a focus on sustainable practices, we strive to offer exceptional products, create memorable moments for our customers, build a thriving community of enthusiasts.</p>

            <p>At Steamy Cup, we believe in sourcing the finest ingredients, handcrafting our products with care, providing personalized customer service. We are dedicated to continuous improvement, ethical sourcing, customer satisfaction.</p>

            <p>Our team is comprised of dedicated experts, passionate individuals who share a love for tea and coffee. We are all united by the goal of bringing the best tea and coffee experiences to you.</p>

            <p>Thank you for being a part of the Steamy Cup community!</p>
        </div>

        <div class="testimonials-section">
            <h2>What Our Customers Are Saying</h2>

            <div class="testimonial">
                <p class="quote">"I absolutely love the Kona Coffee. The quality is outstanding, and the service is exceptional. It's become my go-to for Quality of your products!"</p>
                <p class="author">- Gurkirat Singh Romana, Faridkot,Punjab</p>
            </div>

            <div class="testimonial">
                <p class="quote">"The Jasmine Green Tea exceeded my expectations. It was beautifully packaged, incredibly flavorful, exactly what I was looking for. I highly recommend Steamy Cup!"</p>
                <p class="author">- Amreen Kaur, Bathinda, Punjab</p>
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
        // You can add any JavaScript specific to this page here if needed
         // 2. Simple Testimonial Carousel (Optional - If you have many testimonials)
    document.addEventListener('DOMContentLoaded', function() {
        const testimonials = document.querySelectorAll('.testimonial');
        let currentIndex = 0;

        function showTestimonial(index) {
            testimonials.forEach((testimonial, i) => {
                testimonial.style.display = (i === index) ? 'block' : 'none';
            });
        }

        function nextTestimonial() {
            currentIndex = (currentIndex + 1) % testimonials.length;
            showTestimonial(currentIndex);
        }

        function prevTestimonial() {
            currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
            showTestimonial(currentIndex);
        }

        if (testimonials.length > 1) {
            showTestimonial(currentIndex); // Show the first one initially

            // You would need to add HTML buttons or controls to trigger nextTestimonial() and prevTestimonial()
            // Example HTML:
            
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Next';
            nextBtn.addEventListener('click', nextTestimonial);

            const prevBtn = document.createElement('button');
            prevBtn.textContent = 'Previous';
            prevBtn.addEventListener('click', prevTestimonial);

            const testimonialsSection = document.querySelector('.testimonials-section');
            if (testimonialsSection) {
                testimonialsSection.appendChild(prevBtn);
                testimonialsSection.appendChild(nextBtn);
                // You'd also need CSS to style these buttons
            }
            
        }
    });

    // 3. Basic Element Fade-In on Scroll (Optional - For visual interest)
    document.addEventListener('DOMContentLoaded', function() {
        const elementsToFade = document.querySelectorAll('.company-details p, .testimonial');

        function checkFade() {
            elementsToFade.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                if (elementTop < windowHeight * 0.8) { // Adjust the 0.8 threshold as needed
                    element.classList.add('fade-in');
                }
            });
        }

        window.addEventListener('scroll', checkFade);
        checkFade(); // Initial check on load
    });
</script>

<style>
/* Optional CSS for Fade-In Effect */
.fade-in {
    opacity: 1 !important;
    transform: translateY(0) !important;
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.company-details p, .testimonial {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
    
}
</style>
    </script>
</body>
</html>