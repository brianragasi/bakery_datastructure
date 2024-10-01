<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Add Product</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <header>
        </header> 

    <section class="add-product">
        <h2>Add New Product</h2>
        <form method="post" action="../../actions/admin-product-actions.php" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br><br>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required><br><br>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="0" required><br><br>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*"><br><br>

            <button type="submit" name="add_product" class="btn">Add Product</button>
            
        </form>
    </section>

    <footer>
        </footer> 
</body>
</html>