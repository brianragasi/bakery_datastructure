<?php
require_once '../../classes/User.php';
include '../../classes/Order.php'; 

session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = new User();
$order = new Order(); 
$userId = $_SESSION['user_id'];
$userDetails = $user->getUserDetails($userId);
$orders = $order->getOrdersForUser($userId); 
$loyaltyPoints = $user->getLoyaltyPoints($userId); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    if ($user->updateProfile($userId, $name, $email, $password)) {
        echo "Profile updated successfully!";
        $userDetails = $user->getUserDetails($userId); 
    } else {
        echo "Error: There was a problem updating your profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Profile</title>
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

    <section class="profile">
        <h2>User Profile</h2>

        <?php if ($userDetails): ?>
            <div class="profile-details">
                <p><strong>Name:</strong> <?= $userDetails['name'] ?></p>
                <p><strong>Email:</strong> <?= $userDetails['email'] ?></p>
            </div>

            <div class="profile-update">
                <h3>Update Your Profile</h3>
                <form method="post" action="">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?= $userDetails['name'] ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $userDetails['email'] ?>" required>
                    <label for="password">New Password (optional):</label>
                    <input type="password" id="password" name="password">
                    <button type="submit" name="update_profile" class="btn">Update Profile</button>
                </form>
            </div>

            <div class="profile-section">
                <h3>Order History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product Names</th>
                            <th>Total Quantity</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= $order['product_names'] ?></td>
                                    <td><?= $order['total_quantity'] ?></td>
                                    <td>$<?= $order['total_price'] ?></td>
                                    <td><?= $order['payment_method'] ?></td>
                                    <td><?= $order['address'] ?></td>
                                    <td><?= $order['status'] ?></td>
                                    <td><?= $order['order_date'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="profile-section">
                <h3>Loyalty Points</h3>
                <p>Your current loyalty points: <?= $loyaltyPoints ?></p> 
            </div>

        <?php else: ?>
            <p>User details not found.</p>
        <?php endif; ?>

    </section>

    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>