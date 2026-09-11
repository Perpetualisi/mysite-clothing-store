<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartCount += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Site</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <a href="index.php" class="logo">
      <span class="logo-mark">M</span>
      <span class="logo-text">MySite</span>
    </a>

    <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
      <span></span>
      <span></span>
      <span></span>
    </button>

    <nav id="mainNav">
      <a href="index.php">Home</a>
      <a href="about.php">About</a>
      <a href="blog.php">Journal</a>
      <a href="faq.php">FAQ</a>
      <a href="contact.php">Contact</a>
      <a href="cart.php" class="cart-icon-link">
        🛒
        <?php if ($cartCount > 0): ?>
          <span class="cart-badge"><?php echo $cartCount; ?></span>
        <?php endif; ?>
      </a>
    </nav>
  </header>

  <script>
    document.getElementById('menuToggle').addEventListener('click', function() {
      document.getElementById('mainNav').classList.toggle('open');
      this.classList.toggle('active');
    });
  </script>