<div class="product-card">
    <a href="product.php?id=<?php echo $product['id']; ?>">
        <div class="product-image">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        </div>
        <div class="product-info">
            <h3 class="product-name"><?php echo $product['name']; ?></h3>
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
                <span>(<?php echo $product['rating']; ?>)</span>
            </div>
            <p class="product-description"><?php echo $product['description']; ?></p>
            <div class="product-footer">
                <span class="product-price">INR.<?php echo number_format($product['price'], 2); ?></span>
                <form method="post" action="add-to-cart.php" style="display: inline;">
                    <input type="hidden" name="action" value="add_to_cart">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    
                  </form>
            </div>
        </div>
    </a>
</div>