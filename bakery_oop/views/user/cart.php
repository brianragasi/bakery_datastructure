<?php
include '../../classes/Cart.php';

session_start();
$cart = new Cart();
$cartItems = $cart->getCartItems();
$cartTotal = $cart->getCartTotal();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Cart</title>
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
                    <li><a href="../../views/login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
                <li><a href="cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a></li>
            </ul>
        </nav>
    </header>

    <h2>Your Cart</h2>

    <div class="cart-content">
        <?php if (!empty($cartItems)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td>$<?= $item['price'] ?></td>
                            <td>
                                <form method="post" action="../../actions/cart-actions.php">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 50px;">
                                    <button type="submit" name="update_quantity">Update</button>
                                </form>
                            </td>
                            <td>$<?= $item['subtotal'] ?></td>
                            <td>
                                <form method="post" action="../../actions/cart-actions.php">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <button type="submit" name="remove_item">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>Total: $<?= $cartTotal ?></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            <?php else: ?>
                <p>Please <a href="../login.php?redirect_to=<?= urlencode($_SERVER['REQUEST_URI']) ?>">login</a> or <a href="register.php?redirect_to=<?= urlencode($_SERVER['REQUEST_URI']) ?>">register</a> to checkout.</p>
            <?php endif; ?>

        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <a href="products.php">Continue Shopping</a>

    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>