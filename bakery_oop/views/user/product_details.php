<?php
include '../../classes/Product.php';
require_once '../../classes/User.php'; 

session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $productObj = new Product();
    $product = $productObj->getProduct($productId);

    if ($product) {
        $imageBaseUrl = 'http://localhost/bakery_oop/assets/images/';
        $reviews = $productObj->getReviewsForProduct($productId); 
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>BakeEase Bakery - <?= $product['name'] ?></title>
            <link rel="stylesheet" href="../../assets/css/styles.css">
            <style>
                .product-details .product-image img {
                    width: 500px; 
                    height: 400px; 
                    object-fit: cover;
                }
            </style>
        </head>
        <body>
            <header>
                <div class="logo">
                    <h1>BakeEase Bakery</h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="products.php">Products</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="../../actions/actions.logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="../../views/login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                        <li><a href="cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a></li>
                    </ul>
                    <button class="nav-toggle">Menu</button>
                </nav>
            </header>

            <section class="product-details">
                <button onclick="goBack()">Back</button> <div class="product-content">
                    <div class="product-image">
                        <img src="<?= $imageBaseUrl . trim($product['image'], '/') ?>" alt="<?= $product['name'] ?>"> 
                    </div>

                    <div class="product-info">
                        <h2><?= $product['name'] ?></h2>
                        <p><?= $product['description'] ?></p>
                        <p class="price">Price: $<?= $product['price'] ?></p>
                        <p class="availability">Availability: 
                            <?php if ($product['quantity'] > 0): ?>
                                In Stock (<?= $product['quantity'] ?> available)
                            <?php else: ?>
                                Out of Stock
                            <?php endif; ?>
                        </p>

                        <?php if ($product['quantity'] > 0): ?>
                            <form method="post" action="../../actions/cart-actions.php">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1">
                                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <p class="out-of-stock">Out of Stock</p>
                        <?php endif; ?>
                    </div> 
                </div>

                <div class="reviews"> 
                    <h3>Reviews</h3>

                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="review">
                                <p><strong><?= htmlspecialchars($review['user_name']) ?></strong> - Rating: <?= $review['rating'] ?>/5</p>
                                <p><?= htmlspecialchars($review['review']) ?></p> 
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No reviews yet. Be the first to leave a review!</p>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): 
                        $userObj = new User(); 
                        $hasPurchased = $productObj->hasPurchasedProduct($_SESSION['user_id'], $productId);

                        if ($hasPurchased): 
                            ?>
                            <h4>Write a Review</h4>
                            <form method="post" action="../../actions/review-handler.php">
                                <input type="hidden" name="product_id" value="<?= $productId ?>">
                                <label for="rating">Rating (1-5):</label>
                                <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>
                                <label for="review">Review:</label>
                                <textarea name="review" id="review" required></textarea><br><br>
                                <button type="submit" name="submit_review">Submit Review</button>
                            </form>
                        <?php else: ?>
                            <p>You need to purchase this product to leave a review.</p> 
                        <?php endif; ?> 
                    <?php else: ?> 
                        <p>Please <a href="../login.php">log in</a> to write a review.</p>
                    <?php endif; ?> 
                </div>

            </section>

            <!-- ... (Your footer content) ... -->
            <script src="../../assets/js/script.js"></script>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    header("Location: products.php");
    exit();
}
?>