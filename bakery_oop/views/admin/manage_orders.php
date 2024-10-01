<?php
include '../../classes/AdminOrder.php';

session_start(); 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) { 
    header("Location: ../login.php"); 
    exit();
}

$adminOrder = new AdminOrder();
$orders = $adminOrder->getOrders();

// Message handling
if (isset($_GET['success'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>"; 
} elseif (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>"; 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Manage Orders</title>
    <link rel="stylesheet" href="../../assets/css/styles.css"> 
</head>
<body>
    <h2>Manage Orders</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Product Name</th> 
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Address</th>
                <th>Status</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($orders)) {
                foreach ($orders as $order) {
                    echo "<tr>";
                    echo "<td>" . $order['id'] . "</td>";
                    echo "<td>" . $order['customer_name'] . "</td>"; 
                    echo "<td>" . $order['product_name'] . "</td>"; // Display product name (from the join)
                    echo "<td>$" . $order['total_price'] . "</td>";
                    echo "<td>" . $order['payment_method'] . "</td>";
                    echo "<td>" . $order['address'] . "</td>";
                    echo "<td>";

                    // Order status update form 
                    echo "<form method='post' action='../../actions/admin-order-actions.php' style='display:inline;'> ";
                    echo "<input type='hidden' name='order_id' value='" . $order['id'] . "'>";
                    echo "<select name='new_status'>"; 
                    echo "<option value='pending' " . ($order['status'] == 'pending' ? 'selected' : '') . ">Pending</option>";
                    echo "<option value='processing' " . ($order['status'] == 'processing' ? 'selected' : '') . ">Processing</option>";
                    echo "<option value='shipped' " . ($order['status'] == 'shipped' ? 'selected' : '') . ">Shipped</option>";
                    echo "<option value='delivered' " . ($order['status'] == 'delivered' ? 'selected' : '') . ">Delivered</option>";
                    echo "<option value='cancelled' " . ($order['status'] == 'cancelled' ? 'selected' : '') . ">Cancelled</option>";
                    echo "</select>";
                    echo "<button type='submit' name='update_order_status'>Update</button>"; 
                    echo "</form>";

                    echo "</td>";
                    echo "<td>";
                    echo "<a href='../../actions/admin-order-actions.php?delete_order=" . $order['id'] . "' onclick='return confirm(\"Are you sure you want to delete this order?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No orders found.</td></tr>"; 
            }
            ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php">Back to Dashboard</a> 
</body>
</html>