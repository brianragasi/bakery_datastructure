<?php
session_start();

if (isset($_GET['error'])) {
    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
}
if (isset($_GET['success'])) {
    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Register</title>
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
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<li><a href="profile.php">Profile</a></li>';
                echo '<li><a href="../../actions/actions.logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="../login.php">Login</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
            }
            ?>
           <li><a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li>
        </ul>
    </nav>
  </header>
    <section class="register">
        <h2>Register</h2>
        <form method="post" action="">  
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <button type="submit" name="register" class="btn">Register</button>
        </form>
    </section>
    <footer>
    </footer>
<?php
if (isset($_POST['register'])) {
    include '../../classes/User.php';
    $user = new User();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $registrationResult = $user->register($name, $email, $password);

    if (strpos($registrationResult, 'Error') === 0) {
        header("Location: register.php?error=" . urlencode($registrationResult));
    } else {
        header("Location: register.php?success=" . urlencode($registrationResult));
    }
    exit;
}
?>
</body>
</html>