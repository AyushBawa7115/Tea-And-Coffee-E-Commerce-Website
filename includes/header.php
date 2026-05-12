<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="index.php">
                    <img src="images/logo.png" alt="TeaCoffee Logo">
                    <span>TeaCoffee</span>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#tea">Tea</a></li>
                    <li><a href="index.php#coffee">Coffee</a></li>
                    <li><a href="index.php#accessories">Accessories</a></li>
                    <li><a href="index.php#gifts">Gift Sets</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search products...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="cart">
                    <a href="cart.php">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">
                            <?php 
                            if (isLoggedIn() && function_exists('getCartCount')) {
                                echo getCartCount($pdo, getUserId());
                            } else {
                                echo "0";
                            }
                            ?>
                        </span>
                    </a>
                </div>
                <div class="user-account">
                    <?php if (isLoggedIn()): ?>
                        <a href="account.php">My Account</a>
                    <?php else: ?>
                        <a href="login.php">Sign In</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mobile-menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </div>
</header>