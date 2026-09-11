<?php
require 'includes/require-admin.php';
require_once __DIR__ . '/db.php';

$stmt = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC');
$orders = $stmt->fetchAll();

$itemsStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <section class="admin-dashboard">
    <div class="admin-header">
      <h1>Orders</h1>
      <div>
        <a href="admin.php" class="back-link" style="margin-right: 1rem;">Manage Products</a>
        <a href="admin-logout.php" class="back-link">Log Out</a>
      </div>
    </div>

    <?php if (empty($orders)): ?>
      <p>No orders yet.</p>
    <?php else: ?>
      <?php foreach ($orders as $order): ?>
        <?php
          $itemsStmt->execute([$order['id']]);
          $items = $itemsStmt->fetchAll();
        ?>
        <div class="admin-form-box" style="margin-bottom: 1.5rem;">
          <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
            <strong>Order #<?php echo $order['id']; ?> — <?php echo htmlspecialchars($order['name']); ?></strong>
            <span><?php echo htmlspecialchars($order['created_at']); ?></span>
          </div>
          <p style="margin: 0.3rem 0; color:#5a4c3f;">
            <?php echo htmlspecialchars($order['email']); ?> · <?php echo htmlspecialchars($order['phone']); ?>
          </p>
          <p style="margin: 0.3rem 0; color:#5a4c3f;">
            <?php echo htmlspecialchars($order['address']); ?>
          </p>
          <ul style="margin: 0.8rem 0; padding-left: 1.2rem;">
            <?php foreach ($items as $item): ?>
              <li><?php echo htmlspecialchars($item['product_name']); ?> × <?php echo $item['quantity']; ?> — ₦<?php echo number_format($item['price'] * $item['quantity']); ?></li>
            <?php endforeach; ?>
          </ul>
          <strong>Total: ₦<?php echo number_format($order['total']); ?></strong>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</body>
</html>