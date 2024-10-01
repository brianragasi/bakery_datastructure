<?php
include '../../classes/AdminUser.php';

session_start(); 

// Admin login check 
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

$adminUser = new AdminUser();

// Search Functionality 
$searchQuery = isset($_GET['search']) ? $_GET['search'] : ""; 

// Build the SQL query with search functionality
$sql = "SELECT * FROM users 
        WHERE isAdmin = 0 
        AND (name LIKE '%$searchQuery%' OR email LIKE '%$searchQuery%')"; 

$result = $adminUser->executeQuery($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Display success or error messages 
if (isset($_GET['success'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>"; 
} elseif (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>"; 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Manage Users</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <h2>Manage Users</h2>

    <!-- Search Form -->
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search by name or email" value="<?php echo $searchQuery; ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Loyalty Points</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($users)) {
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    echo "<td>" . $user['name'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>" . $user['loyalty_points'] . "</td>"; 
                    echo "<td>";
                    echo "<a href='edit_user.php?id=" . $user['id'] . "'>Edit</a> | "; 
                    echo "<a href='../../actions/admin-user-actions.php?delete_user=" . $user['id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>"; 
                    echo "</td>"; 
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found.</td></tr>"; 
            }
            ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php">Back to Dashboard</a> 
</body>
</html>