<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'products.php';
require_once __DIR__ . '/db.php';

// Handle the "already placed" confirmation view
$orderPlaced = isset($_GET['placed']) && !empty($_SESSION['last_order']);

if ($orderPlaced) {
    $orderName = $_SESSION['last_order']['name'];
    $orderTotal = $_SESSION['last_order']['total'];
    unset($_SESSION['last_order']); // clear after showing once
}

// If there's no cart and no just-placed order to show, send them back to shop
if (!$orderPlaced && empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

$products = getProducts();
$cartItems = [];
$total = 0;

if (!$orderPlaced) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        if (isset($products[$id])) {
            $subtotal = $products[$id]['price'] * $quantity;
            $cartItems[] = [
                'name' => $products[$id]['name'],
                'price' => $products[$id]['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
            $total += $subtotal;
        }
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($address === '') {
        $errors[] = 'Delivery address is required.';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\s-]{7,15}$/', $phone)) {
        $errors[] = 'Please enter a valid phone number.';
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Insert the order itself
            $stmt = $pdo->prepare(
                'INSERT INTO orders (name, email, phone, address, total) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$name, $email, $phone, $address, $total]);
            $orderId = $pdo->lastInsertId();

            // Insert each item in the order
            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)'
            );
            foreach ($cartItems as $item) {
                $itemStmt->execute([$orderId, $item['name'], $item['price'], $item['quantity']]);
            }

            $pdo->commit();

            // Save details temporarily so the confirmation page can show them
            $_SESSION['last_order'] = [
                'name' => $name,
                'total' => $total
            ];
            $_SESSION['cart'] = []; // clear the cart, order is placed

            header('Location: checkout.php?placed=1');
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Something went wrong placing your order. Please try again.';
        }
    }
}

include 'includes/header.php';
?>

<section class="checkout">
  <?php if ($orderPlaced): ?>
    <h1>Order Placed!</h1>
    <p class="success">Thanks, <?php echo htmlspecialchars($orderName); ?> — your order of ₦<?php echo number_format($orderTotal); ?> has been received. We'll be in touch to confirm delivery.</p>
    <a href="index.php" class="checkout-btn">Continue Shopping</a>

  <?php else: ?>
    <h1>Checkout</h1>

    <div class="checkout-summary">
      <?php foreach ($cartItems as $item): ?>
        <div class="summary-line">
          <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></span>
          <span>₦<?php echo number_format($item['subtotal']); ?></span>
        </div>
      <?php endforeach; ?>
      <div class="summary-line total-line">
        <span>Total</span>
        <span>₦<?php echo number_format($total); ?></span>
      </div>
    </div>

    <?php if (!empty($errors)): ?>
      <ul class="errors">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="POST" action="checkout.php" class="contact-form">
      <label>
        Full Name
        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
      </label>
      <label>
        Email
        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
      </label>
      <label>
        Phone
        <input type="text" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
      </label>
      <label>
        Delivery Address
        <textarea name="address" rows="3"><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
      </label>
      <button type="submit">Place Order</button>
    </form>
  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>