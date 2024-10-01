<?php
include '../../classes/AdminProduct.php';

session_start(); 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../login.php"); 
    exit();
}

$adminProduct = new AdminProduct();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $product = $adminProduct->getProduct($productId); 

    if (!$product) {
        echo "Product not found.";
        exit; 
    }
} else {
    header("Location: manage_products.php"); 
    exit();
}

// Handle error messages
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>"; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Edit Product</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        </header> 

    <section class="edit-product">
        <h2>Edit Product</h2>
        <form method="post" action="../../actions/admin-product-actions.php" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $product['name'] ?>" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?= $product['description'] ?></textarea><br><br> 

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= $product['price'] ?>" required><br><br>

            <label for="quantity">Quantity:</label> 
            <input type="number" id="quantity" name="quantity" min="0" value="<?= $product['quantity'] ?>" required><br><br>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br><br> 

            <button type="submit" name="update_product" class="btn">Update Product</button>
        </form>
    </section>

    <footer>
        </footer> 
</body>
</html>