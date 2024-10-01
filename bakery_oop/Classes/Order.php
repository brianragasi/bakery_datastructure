<?php
require_once "Database.php";
require_once "User.php"; // Include the User class to access loyalty point methods

class Order extends Database {

    public function createOrder($userId, $cartItems, $totalPrice, $paymentMethod, $address) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            return "Error: You must be logged in to place an order.";
        }

        $userId = $this->conn->real_escape_string($userId);
        $totalPrice = $this->conn->real_escape_string($totalPrice);
        $paymentMethod = $this->conn->real_escape_string($paymentMethod);
        $address = $this->conn->real_escape_string($address);

        $sql = "INSERT INTO orders (user_id, total_price, payment_method, address) VALUES ('$userId', '$totalPrice', '$paymentMethod', '$address')";

        if ($this->conn->query($sql) === TRUE) {
            $orderId = $this->conn->insert_id;

            foreach ($cartItems as $item) {
                $productId = $this->conn->real_escape_string($item['product_id']);
                $quantity = $this->conn->real_escape_string($item['quantity']);

                $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ('$orderId', '$productId', '$quantity')";
                if (!$this->conn->query($sql)) {
                    error_log("Error inserting order item: " . $this->conn->error);
                    return false;
                }

                $sql = "UPDATE products SET quantity = quantity - '$quantity' WHERE id = '$productId'";
                $this->conn->query($sql);
            }

            // Award loyalty points after successful order creation
            $pointsToAward = floor($totalPrice); // 1 point per $1 spent (adjust as needed)
            $user = new User();
            $user->addLoyaltyPoints($userId, $pointsToAward);

            unset($_SESSION['cart']);
            return $orderId;

        } else {
            return false;
        }
    }

    public function executeQuery($sql) {
        return $this->conn->query($sql);
    }

    public function getOrders() {
        $sql = "SELECT o.*, u.name AS customer_name, p.name AS product_name
                FROM orders o
                INNER JOIN users u ON o.user_id = u.id
                INNER JOIN order_items oi ON o.id = oi.order_id
                INNER JOIN products p ON oi.product_id = p.id
                ORDER BY o.id DESC";
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getOrdersForUser($userId) {
        $userId = $this->conn->real_escape_string($userId);

        $sql = "SELECT o.id, o.total_price, o.payment_method, o.address, o.status, o.order_date,
                       GROUP_CONCAT(p.name SEPARATOR ', ') AS product_names,
                       SUM(oi.quantity) AS total_quantity
                FROM orders o
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE o.user_id = '$userId'
                GROUP BY o.id
                ORDER BY o.order_date DESC"; 

        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getOrder($orderId) {
        $orderId = $this->conn->real_escape_string($orderId);
        $sql = "SELECT o.*, u.name AS customer_name, p.name AS product_name
                FROM orders o
                INNER JOIN users u ON o.user_id = u.id
                INNER JOIN order_items oi ON o.id = oi.order_id
                INNER JOIN products p ON oi.product_id = p.id
                WHERE o.id = '$orderId'";
        $result = $this->conn->query($sql);
        return ($result->num_rows == 1) ? $result->fetch_assoc() : null;
    }

    public function updateOrderStatus($orderId, $newStatus) {
        $orderId = $this->conn->real_escape_string($orderId);
        $newStatus = $this->conn->real_escape_string($newStatus);

        $sql = "UPDATE orders SET status = '$newStatus' WHERE id = '$orderId'";
        return $this->conn->query($sql);
    }

    public function deleteOrder($orderId) {
        $orderId = $this->conn->real_escape_string($orderId);
        $sql = "DELETE FROM orders WHERE id = '$orderId'";
        return $this->conn->query($sql);
    }
}
?>