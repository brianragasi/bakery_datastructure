<?php
include '../../classes/Product.php'; 

session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$productObj = new Product();
$featuredProducts = $productObj->getFeaturedProducts();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Get product details, including available quantity
    $product = $productObj->getProduct($productId);

    // Correct the condition to check for stock
    if ($product && $product['quantity'] > 0 && $product['quantity'] >= $quantity) { 
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // Product is out of stock or insufficient quantity
        $message = "Error: Not enough of this product in stock.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
        exit;
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
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
                    <li><a href="../login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li><a href="cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a></li>
            </ul>
            <button class="nav-toggle">Menu</button> 
        </nav>
    </header>

    <div class="main-content">
        <section class="hero">
            <h2>Welcome to BakeEase Bakery!</h2>
            <p>Indulge in the aroma of freshly baked goods and treat yourself to our delectable creations.</p>
            <a href="products.php" class="btn" onclick="updateNavigationStack(this.href); return true;">Explore Our Products</a>
        </section>

        <section class="product-gallery">
        <button onclick="goBack()">Back</button> 
            <h2>Our Featured Products</h2>

            <?php if (isset($_GET['message'])): ?>
                <p class="error-message"><?= htmlspecialchars($_GET['message']) ?></p> 
            <?php endif; ?>

            <div class="products">
                <?php
                if (!empty($featuredProducts)) {
                    foreach ($featuredProducts as $product) {
                        ?>
                        <div class='product-card'>
                            <a href="product_details.php?id=<?= $product['id'] ?>" onclick="updateNavigationStack(this.href); return true;">
                                <img src='<?= $product['image'] ?>' alt='<?= $product['name'] ?>'>
                                <h3><?= $product['name'] ?></h3>
                            </a>
                            <p><?= $product['description'] ?></p>
                            <p>$<?= $product['price'] ?></p>

                            <p>Availability: 
                                <?php if ($product['quantity'] > 0): ?>
                                    In Stock (<?= $product['quantity'] ?> available)
                                <?php else: ?>
                                    Out of Stock
                                <?php endif; ?>
                            </p> 

                            <form method='post' action=''> 
                                <input type='hidden' name='product_id' value='<?= $product['id'] ?>'>
                                <input type='number' name='quantity' value='1' min='1' 
                                       <?php if ($product['quantity'] == 0): ?>disabled<?php endif; ?>>
                                <button type='submit' name='add_to_cart' class='btn' 
                                        <?php if ($product['quantity'] == 0): ?>disabled<?php endif; ?>>Add to Cart</button>
                            </form>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No products available.</p>";
                }
                ?>
            </div>
        </section>
    </div> 

    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
    <script src="../../assets/js/script.js"></script> 
    <script> 
        function updateNavigationStack(url) {
            navStack.push(url); 
            return true;
        }
    </script>
</body>
</html>