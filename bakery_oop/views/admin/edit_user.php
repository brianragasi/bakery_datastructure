<?php
include '../../classes/AdminUser.php'; 

session_start(); 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$userId = $_GET['id']; 
$adminUser = new AdminUser();
$userToEdit = $adminUser->getUserDetails($userId); 

// Display error message 
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>"; 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Edit User</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <h2>Edit User</h2>
    <?php if ($userToEdit): ?>
    <form method="post" action="../../actions/admin-user-actions.php"> 
        <input type="hidden" name="user_id" value="<?php echo $userToEdit['id']; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $userToEdit['name']; ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $userToEdit['email']; ?>" required><br><br>

        <label for="password">New Password (Optional):</label>
        <input type="password" id="password" name="password"><br><br>

        <label for="loyalty_points">Loyalty Points:</label>
        <input type="number" id="loyalty_points" name="loyalty_points" value="<?php echo $userToEdit['loyalty_points']; ?>" required><br><br>

        <button type="submit" name="update_user" class="btn">Update User</button>
    </form>
    <?php else: ?> 
        <p>User not found.</p>
    <?php endif; ?>
    <a href="manage_users.php">Back to Manage Users</a>
</body>
</html>