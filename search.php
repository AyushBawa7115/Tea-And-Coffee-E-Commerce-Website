<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($searchQuery)) {
    $stmt = $pdo->prepare("
        SELECT p.*, pi.image_path 
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE p.name LIKE ? 
        OR p.description LIKE ? 
        OR p.category LIKE ?
        ORDER BY p.name ASC
    ");
    
    $searchParam = "%$searchQuery%";
    $stmt->execute([$searchParam, $searchParam, $searchParam]);
    $results = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - TeaCoffee</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            color: #4B3C31;
            background-color: #C8E6C9;
        }

        .search-results {
            padding: 40px 0;
            min-height: calc(100vh - 400px);
        }
        
        .search-results h1 {
            margin-bottom: 20px;
            color: #2C6B48;
            font-size: 2em;
        }
        
        .search-results .results-count {
            margin-bottom: 30px;
            color: #4B3C31;
            font-size: 1.1em;
        }
        
        .search-results .no-results {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .search-results .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            color: #2C6B48;
            margin: 10px 0;
            font-size: 1.2em;
        }

        .product-card .price {
            color: #4B3C31;
            font-weight: bold;
            font-size: 1.1em;
            margin: 10px 0;
        }

        .product-card .btn {
            display: inline-block;
            background-color: #2C6B48;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .product-card .btn:hover {
            background-color: #4A7C49;
        }

        .categories {
            margin-top: 20px;
        }

        .categories .btn {
            margin: 0 10px;
            background-color: #2C6B48;
        }

        .categories .btn:hover {
            background-color: #4A7C49;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main class="search-results">
        <div class="container">
            <h1>Search Results</h1>
            
            <?php if (!empty($searchQuery)): ?>
                <div class="results-count">
                    <?php if (count($results) > 0): ?>
                        Found <?php echo count($results); ?> results for "<?php echo htmlspecialchars($searchQuery); ?>"
                    <?php else: ?>
                        No results found for "<?php echo htmlspecialchars($searchQuery); ?>"
                    <?php endif; ?>
                </div>
                
                <?php if (count($results) > 0): ?>
                    <div class="products-grid">
                        <?php foreach ($results as $product): ?>
                            <div class="product-card">
                                <?php if (!empty($product['image_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <img src="images/default-product.jpg" alt="No image available">
                                <?php endif; ?>
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price">INR <?php echo number_format($product['price'], 2); ?></p>
                                <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p>Try searching with different keywords or browse our categories:</p>
                        <div class="categories">
                            <a href="index.php#tea" class="btn">Tea</a>
                            <a href="index.php#coffee" class="btn">Coffee</a>
                            <a href="index.php#accessories" class="btn">Accessories</a>
                            <a href="index.php#gifts" class="btn">Gift Sets</a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>Please enter a search term to find products.</p>
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

    <script src="js/script.js"></script>
</body>
</html> 