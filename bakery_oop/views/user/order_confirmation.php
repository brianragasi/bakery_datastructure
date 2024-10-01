<?php
include '../../classes/Order.php';
include '../../classes/Product.php'; // Include Product class to fetch product names

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

// Get the order ID from the URL parameter
if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id']; 

    // Fetch order details from the database (updated query)
    $order = new Order();
    $sql = "SELECT o.*, p.name AS product_name, p.price AS product_price, oi.quantity AS item_quantity
            FROM orders o
            INNER JOIN order_items oi ON o.id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.id
            WHERE o.id = '$orderId'";

    // Use the executeQuery() method to run the query
    $result = $order->executeQuery($sql); 

    if ($result->num_rows > 0) {
        $orderDetails = $result->fetch_all(MYSQLI_ASSOC);
        $total_price = 0;
    } else {
        echo "Error: Order details not found.";
        exit();
    }

} else {
    echo "Error: Order ID not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Order Confirmation</title>
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
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="../../actions/actions.logout.php">Logout</a></li>
                <?php } else { ?>
                    <li><a href="../login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } ?>
                <li><a href="cart.php">Cart (0)</a></li> 
            </ul>
        </nav>
    </header>

    <section class="confirmation">
        <h2>Thank You For Your Order!</h2>
        <p>Your order has been placed successfully.</p>
        <p>For inquiries, please contact us at <a href="mailto:info@bakeeasebakery.com">info@bakeeasebakery.com</a>.</p>

        <h3>Order Details</h3>
        <p>Order ID: <?php echo $orderId; ?></p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $item) : ?>
                    <tr>
                        <td><?php echo $item['product_name']; ?></td>
                        <td><?php echo $item['item_quantity']; ?></td> 
                        <td>$<?php echo $item['product_price']; ?></td>
                        <td>$<?php echo number_format($item['item_quantity'] * $item['product_price'], 2); ?></td> 
                    </tr>
                    <?php $total_price += $item['item_quantity'] * $item['product_price']; ?> 
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total:</td>
                    <td>$<?php echo number_format($total_price, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <a href="index.php" class="btn">Back to Home</a>
    </section>

    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>