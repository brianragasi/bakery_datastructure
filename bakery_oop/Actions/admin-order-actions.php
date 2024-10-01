<?php
include "../classes/AdminOrder.php"; 

session_start(); 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) { 
    header("Location: /views/login.php"); 
    exit(); 
}

$adminOrder = new AdminOrder();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status']; 

    if ($adminOrder->updateOrderStatus($orderId, $newStatus)) {
        // Redirect back to manage_orders.php with a success message
        header("Location: ../views/admin/manage_orders.php?success=Order status updated successfully."); 
        exit;
    } else {
        // Redirect back to manage_orders.php (or the order details page) with an error message
        header("Location: ../views/admin/manage_orders.php?error=Error updating order status."); 
        exit;
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_order'])) {
    $orderIdToDelete = $_GET['delete_order'];

    if ($adminOrder->deleteOrder($orderIdToDelete)) {
        // Redirect to manage_orders.php with a success message
        header("Location: ../views/admin/manage_orders.php?success=Order deleted successfully."); 
        exit;
    } else {
        // Redirect to manage_orders.php with an error message
        header("Location: ../views/admin/manage_orders.php?error=Error deleting order."); 
        exit;
    } 
} 
?>