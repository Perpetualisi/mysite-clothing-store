<?php
require 'products.php';

$id = $_GET['id'] ?? null;
$product = $id ? getProductById($id) : null;

include 'includes/header.php';
?>

<section class="product-page">
  <?php if ($product): ?>
    <div class="product-detail">
      <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
      <div class="product-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="price">₦<?php echo number_format($product['price']); ?></p>
        <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
        <form method="POST" action="cart.php">
          <input type="hidden" name="product_id" value="<?php echo $id; ?>">
          <button type="submit">Add to Cart</button>
        </form>
      </div>
    </div>
  <?php else: ?>
    <p>Product not found. <a href="index.php">Back to shop</a></p>
  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>