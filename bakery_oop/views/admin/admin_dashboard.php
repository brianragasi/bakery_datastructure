<?php
include '../../classes/AdminUser.php';
include '../../classes/Product.php';
include '../../classes/Order.php'; 

session_start(); 

// Admin login check 
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: ../login.php");
    exit();
}

$adminUser = new AdminUser(); 
$product = new Product(); 
$order = new Order();

// Fetch data for the dashboard
$totalUsers = count($adminUser->displayUsers());  
$totalProducts = count($product->getProducts()); 
$totalOrders = count($order->getOrders()); 

?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>

    <div class="dashboard-section">
        <h3>Users</h3>
        <p>Total Users: <?php echo $totalUsers; ?></p> 
        <a href="manage_users.php">Manage Users</a> 
    </div>

    <div class="dashboard-section">
        <h3>Products</h3>
        <p>Total Products: <?php echo $totalProducts; ?></p> 
        <a href="manage_products.php">Manage Products</a> 
    </div>

    <div class="dashboard-section">
        <h3>Orders</h3>
        <p>Total Orders: <?php echo $totalOrders; ?></p>
        <a href="manage_orders.php">Manage Orders</a>
    </div>

    <a href="../../actions/logout_admin.php">Logout</a> 
</body>
</html>