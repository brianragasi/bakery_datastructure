<?php
include '../../classes/Database.php';
include '../../classes/Product.php';
include_once '../../classes/AdminProduct.php'; // Include AdminProduct

$product = new Product();
$adminProduct = new AdminProduct(); // Instantiate AdminProduct

$searchQuery = isset($_GET['search']) ? $_GET['search'] : "";
$products = $product->getProducts(); // Use $product to get products

$imageBaseUrl = 'http://localhost/bakery_oop/assets/images/';

if (!$products) {
    $errorMessage = "Error fetching products: " . $product->getError();
}

// Handle success, error, and other messages
if (isset($_GET['success'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>";
} elseif (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
} elseif (isset($_GET['message'])) { // Add this back for feature/unfeature messages
    echo "<p>" . htmlspecialchars($_GET['message']) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Manage Products</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        <!-- Your header content -->
    </header>

    <section class="manage-products">
        <h2>Manage Products</h2>

        <form method="get" action="">
            <input type="text" name="search" placeholder="Search by name or description" value="<?php echo $searchQuery; ?>">
            <button type="submit">Search</button>
        </form> <br> <br>

        <a href="add_product.php" class="btn">Add Product</a> <br><br>

        <?php if (isset($errorMessage)): ?>
    
        <?php endif; ?>

        <?php if ($products): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Featured</th>
                        <th>Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td>$<?= $product['price'] ?></td>
                            <td>
                                <?php if (!empty($product['image'])): ?>
                                    <img src="<?= $imageBaseUrl . urlencode(basename($product['image'])) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 100px;">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?= $product['featured'] ? 'Yes' : 'No' ?></td> 
                            <td>
                                <a href="edit_product.php?id=<?= $product['id'] ?>">Edit</a> |
                                <a href="../../actions/admin-product-actions.php?delete_product=<?= $product['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a> 
                                <a href="../../actions/admin-product-actions.php?toggle_featured=<?= $product['id'] ?>">
                                    <?= $product['featured'] ? 'Unfeature' : 'Feature' ?>
                                </a>
                          </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found.</p> 
        <?php endif; ?>

        <a href="admin_dashboard.php">Back to Dashboard</a>
    </section>

    <footer>
       
    </footer>
</body>
</html>