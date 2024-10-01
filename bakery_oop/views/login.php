<?php
session_start();

if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>BakeEase Bakery</h1>
        </div>
        <nav>
            <ul>
                <li><a href="user/index.php">Home</a></li>  
                <li><a href="user/products.php">Products</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="../../actions/actions.logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>  
                    <li><a href="user/register.php">Register</a></li> 
                <?php endif; ?>
                <li><a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li> 
            </ul>
        </nav>
    </header>

    <section class="login">
        <h2>Login</h2>
        <form method="post" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login" class="btn">Login</button>
        </form>
    </section>

    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>

<?php
if (isset($_POST['login'])) {
    include '../classes/User.php';
    $user = new User();

    $email = $_POST['email'];
    $password = $_POST['password'];
    $loginResult = $user->login($email, $password);

    if (is_string($loginResult)) {
        
        header("Location: login.php?error=" . urlencode($loginResult)); // Redirect back to login with error
        exit;
    } else {
        
    }
}
?>
</body>
</html>