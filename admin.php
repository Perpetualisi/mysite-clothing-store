<?php
require 'includes/require-admin.php';
require 'products.php';

// Handle delete
if (isset($_GET['delete'])) {
    deleteProduct($_GET['delete']);
    header('Location: admin.php');
    exit;
}

// Handle add/edit form submission
$editing = null;
if (isset($_GET['edit'])) {
    $editing = getProductById($_GET['edit']);
    $editingId = $_GET['edit'];
}

$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $image = trim($_POST['existing_image'] ?? '');

    // Handle a new file upload, if one was provided
    if (!empty($_FILES['image_file']['name'])) {
        $file = $_FILES['image_file'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadError = 'Upload failed. Try a smaller file.';
        } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
            $uploadError = 'Only JPG, PNG, or WEBP images are allowed.';
        } elseif ($file['size'] > $maxSize) {
            $uploadError = 'Image must be under 2MB.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $safeName = uniqid('product_', true) . '.' . $ext;
            $destination = __DIR__ . '/uploads/' . $safeName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $image = 'uploads/' . $safeName;
            } else {
                $uploadError = 'Could not save the uploaded image.';
            }
        }
    }

    if ($name && $price && empty($uploadError)) {
        if (!empty($_POST['id'])) {
            updateProduct($_POST['id'], $name, $price, $image, $description, $category);
        } else {
            addProduct($name, $price, $image, $description, $category);
        }
        header('Location: admin.php');
        exit;
    }
}

$products = getProducts();
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <section class="admin-dashboard">
    <div class="admin-header">
      <h1>Manage Products</h1>
      <div>
        <a href="admin-orders.php" class="back-link" style="margin-right: 1rem;">View Orders</a>
        <a href="admin-logout.php" class="back-link">Log Out</a>
      </div>
    </div>

    <div class="admin-form-box">
      <h3><?php echo $editing ? 'Edit Product' : 'Add New Product'; ?></h3>

      <?php if ($uploadError): ?>
        <p class="errors"><?php echo htmlspecialchars($uploadError); ?></p>
      <?php endif; ?>

      <form method="POST" action="admin.php" enctype="multipart/form-data" class="contact-form">
        <?php if ($editing): ?>
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($editingId); ?>">
          <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editing['image'] ?? ''); ?>">
        <?php endif; ?>
        <label>
          Name
          <input type="text" name="name" value="<?php echo htmlspecialchars($editing['name'] ?? ''); ?>">
        </label>
        <label>
          Price (₦)
          <input type="number" name="price" value="<?php echo htmlspecialchars($editing['price'] ?? ''); ?>">
        </label>
        <label>
          Category
          <input type="text" name="category" list="category-suggestions" value="<?php echo htmlspecialchars($editing['category'] ?? ''); ?>" placeholder="e.g. Tops, Outerwear, Accessories">
          <datalist id="category-suggestions">
            <?php foreach ($categories as $cat): ?>
              <option value="<?php echo htmlspecialchars($cat); ?>">
            <?php endforeach; ?>
          </datalist>
        </label>
        <label>
          Product Image
          <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp">
        </label>
        <?php if ($editing && !empty($editing['image'])): ?>
          <p style="font-size:0.85rem;color:#5a4c3f;">
            Current image: <img src="<?php echo htmlspecialchars($editing['image']); ?>" style="width:40px;vertical-align:middle;border-radius:4px;">
            (leave blank to keep it)
          </p>
        <?php endif; ?>
        <label>
          Description
          <textarea name="description" rows="3"><?php echo htmlspecialchars($editing['description'] ?? ''); ?></textarea>
        </label>
        <button type="submit"><?php echo $editing ? 'Save Changes' : 'Add Product'; ?></button>
      </form>
    </div>

    <div class="admin-product-list">
      <?php foreach ($products as $id => $product): ?>
        <div class="admin-product-row">
          <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="">
          <div class="admin-product-info">
            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
            <span>₦<?php echo number_format($product['price']); ?> · <?php echo htmlspecialchars($product['category']); ?></span>
          </div>
          <a href="admin.php?edit=<?php echo $id; ?>">Edit</a>
          <a href="admin.php?delete=<?php echo $id; ?>" class="remove-link" onclick="return confirm('Delete this product?');">Delete</a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</body>
</html>