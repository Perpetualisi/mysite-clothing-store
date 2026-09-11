<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'products.php';

// Initialize cart if it doesn't exist yet
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle clearing the whole cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit;
}

// Handle adding an item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
}

// Handle removing a single item
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

$products = getProducts();
$cartItems = [];
$total = 0;

foreach ($_SESSION['cart'] as $id => $quantity) {
    if (isset($products[$id])) {
        $subtotal = $products[$id]['price'] * $quantity;
        $cartItems[] = [
            'id' => $id,
            'name' => $products[$id]['name'],
            'price' => $products[$id]['price'],
            'image' => $products[$id]['image'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
        $total += $subtotal;
    }
}

include 'includes/header.php';
?>

<section class="cart">
  <h1>Your Cart</h1>

  <?php if (empty($cartItems)): ?>
    <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
  <?php else: ?>
    <div class="cart-items">
      <?php foreach ($cartItems as $item): ?>
        <div class="cart-item">
          <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
          <div class="cart-item-info">
            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
            <p>₦<?php echo number_format($item['price']); ?> × <?php echo $item['quantity']; ?></p>
          </div>
          <p class="cart-item-subtotal">₦<?php echo number_format($item['subtotal']); ?></p>
          <a href="cart.php?remove=<?php echo $item['id']; ?>" class="remove-link">Remove</a>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="cart-total">
      <p>Total: ₦<?php echo number_format($total); ?></p>
      <div class="cart-actions">
        <a href="cart.php?clear=1" class="clear-cart-link" onclick="return confirm('Clear your entire cart?');">Clear Cart</a>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
      </div>
    </div>
  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>