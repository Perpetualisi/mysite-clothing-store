<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <section class="admin-login">
    <h1>Admin Login</h1>
    <?php if ($error): ?>
      <p class="errors"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="admin-login.php" class="contact-form">
      <label>
        Username
        <input type="text" name="username">
      </label>
      <label>
        Password
        <input type="password" name="password">
      </label>
      <button type="submit">Log In</button>
    </form>
  </section>
</body>
</html>