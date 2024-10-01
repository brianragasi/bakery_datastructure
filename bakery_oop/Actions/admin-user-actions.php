<?php
include "../classes/AdminUser.php"; 

session_start(); 
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) { 
    header("Location: ../views/login.php"); 
    exit();
}

$adminUser = new AdminUser();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_user'])) { 
    $userIdToDelete = $_GET['delete_user']; 

    $deleteResult = $adminUser->deleteUser($userIdToDelete);

    if ($deleteResult === true) {
        header("Location: ../views/admin/manage_users.php?success=User deleted successfully.");
        exit;
    } else {
        $errorMessage = is_string($deleteResult) ? $deleteResult : "Error deleting user.";
        header("Location: ../views/admin/manage_users.php?error=" . urlencode($errorMessage));
        exit;
    }
} 
elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) { 
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    $loyaltyPoints = $_POST['loyalty_points'];

    if ($adminUser->updateUser($userId, $name, $email, $password, $loyaltyPoints)) {
        header("Location: ../views/admin/manage_users.php?success=User updated successfully.");
        exit;
    } else {
        $error_message = "Error updating user.";
        header("Location: ../views/admin/edit_user.php?id=$userId&error=" . urlencode($error_message));
        exit;
    }
}
?>