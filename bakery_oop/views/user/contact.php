<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BakeEase Bakery - Contact Us</title>
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
        <?php if (isset($_SESSION['user_id'])) : ?>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="../../actions/actions.logout.php">Logout</a></li>
        <?php else : ?>
          <li><a href="../login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        <?php endif; ?>
        <li><a href="cart.php">Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a></li>
      </ul>
    </nav>
  </header>

  <section class="contact">
    <h2>Contact Us</h2>
    <div class="contact-info">
      <p>If you have any questions or inquiries, please feel free to reach out to us.</p>
      <p><strong>Email:</strong> info@bakeeasebakery.com</p>
      <p><strong>Phone:</strong> (123) 456-7890</p>
    </div>
    <form method="post" action="../../actions/contact-form-handler.php">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required><br><br>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br><br>
      <label for="message">Message:</label>
      <textarea id="message" name="message" rows="5" required></textarea><br><br>
      <button type="submit" name="submit" class="btn">Send Message</button>
    </form>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1) {
      echo "<p style='color: green;'>Your message has been sent successfully!</p>";
    } elseif (isset($_GET['error'])) {
      echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>
  </section>

  <footer>
    <p>© 2023 BakeEase Bakery. All rights reserved.</p>
  </footer>
</body>

</html>