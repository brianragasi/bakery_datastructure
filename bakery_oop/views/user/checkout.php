<?php
include '../../classes/Cart.php';
include '../../classes/Order.php';


session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user = new User();
$cart = new Cart();
$cartItems = $cart->getCartItems();
$cartTotal = $cart->getCartTotal();
$points = $user->getLoyaltyPoints($_SESSION['user_id']);
$redemptionRate = 0.01;
$maxPoints = floor($cartTotal / $redemptionRate);

// Check if points are being redeemed (form submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['redeem_points'])) {
    $pointsToRedeem = min($_POST['redeem_points'], $points, $maxPoints);
    $discount = $pointsToRedeem * $redemptionRate;
    $finalTotal = $cartTotal - $discount;
} else {
    // If the form hasn't been submitted yet, no points are redeemed
    $pointsToRedeem = 0;
    $discount = 0;
    $finalTotal = $cartTotal;
}

// Handle order placement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $userId = $_SESSION['user_id'];
    $paymentMethod = $_POST['payment_method'];
    $address = $_POST['address'];

    $user->deductLoyaltyPoints($userId, $pointsToRedeem);

    $order = new Order();
    $orderId = $order->createOrder($userId, $cartItems, $finalTotal, $paymentMethod, $address);

    if ($orderId) {
        header("Location: order_confirmation.php?order_id=$orderId");
        exit;
    } else {
        echo "Error: There was a problem placing your order. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Checkout</title>
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
                    <li><a href="login.php">Login</a></li> 
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li><a href="cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a></li>
            </ul>
        </nav>
    </header>

    <section class="checkout">
        <h2>Checkout</h2>

        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= $item['name']; ?></td>
                        <td>$<?= $item['price']; ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>$<?= number_format($item['subtotal'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Total: $<?= number_format($cartTotal, 2); ?></strong></p>

        <?php if ($points > 0): ?>
            <form method="post">
                <label for="redeem_points">Redeem Loyalty Points (<?= $points ?> available, 1 point = $<?= $redemptionRate ?>):</label>
                <input type="number" id="redeem_points" name="redeem_points" value="<?= $pointsToRedeem ?>" min="0" max="<?= $maxPoints ?>">
                <button type="submit">Apply</button>
            </form>

            <?php if (isset($_POST['redeem_points'])): ?> 
                <p>Discount: $<?= number_format($discount, 2); ?></p>
                <p><strong>Final Total: $<?= number_format($finalTotal, 2); ?></strong></p>
            <?php endif; ?> 
        <?php endif; ?>

        <h3>Shipping Details</h3>
        <form method="post" action="">
            <label for="address">Delivery Address:</label>
            <textarea id="address" name="address" required></textarea><br><br>

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cod">Cash on Delivery</option>
                <option value="credit_card">Credit Card</option>
            </select><br><br>

            <button type="submit" name="place_order" class="btn">Place Order</button>
        </form>

    </section>

    <footer>
       </footer>
</body>
</html>