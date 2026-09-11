<?php
require 'products.php';

$selectedCategory = $_GET['category'] ?? null;
$searchTerm = trim($_GET['search'] ?? '');
$products = getProducts($selectedCategory, $searchTerm ?: null);
$categories = getCategories();

include 'includes/header.php';
?>

<section class="hero">
  <h1>Crafted with care.</h1>
  <p>A simple, elegant website built from scratch with PHP.</p>
</section>

<section class="shop">
  <h1>Shop</h1>

  <form method="GET" action="index.php" class="search-bar">
    <?php if ($selectedCategory): ?>
      <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategory); ?>">
    <?php endif; ?>
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Search</button>
    <?php if ($searchTerm): ?>
      <a href="index.php<?php echo $selectedCategory ? '?category=' . urlencode($selectedCategory) : ''; ?>" class="clear-search-link">Clear</a>
    <?php endif; ?>
  </form>

  <div class="category-filters">
    <a href="index.php<?php echo $searchTerm ? '?search=' . urlencode($searchTerm) : ''; ?>" class="category-btn <?php echo !$selectedCategory ? 'active' : ''; ?>">All</a>
    <?php foreach ($categories as $cat): ?>
      <?php
        $params = ['category' => $cat];
        if ($searchTerm) $params['search'] = $searchTerm;
      ?>
      <a href="index.php?<?php echo http_build_query($params); ?>" class="category-btn <?php echo $selectedCategory === $cat ? 'active' : ''; ?>">
        <?php echo htmlspecialchars($cat); ?>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($searchTerm): ?>
    <p class="search-results-note">
      <?php echo count($products); ?> result<?php echo count($products) !== 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchTerm); ?>"
    </p>
  <?php endif; ?>

  <div class="product-grid">
    <?php if (empty($products)): ?>
      <p>No products found.</p>
    <?php endif; ?>
    <?php foreach ($products as $id => $product): ?>
      <div class="product-card">
        <a href="product.php?id=<?php echo $id; ?>">
          <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
          <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        </a>
        <p>₦<?php echo number_format($product['price']); ?></p>
        <form method="POST" action="cart.php">
          <input type="hidden" name="product_id" value="<?php echo $id; ?>">
          <button type="submit">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>