<?php
require 'posts.php';

$id = $_GET['id'] ?? null;
$post = $id ? getPostById($id) : null;

include 'includes/header.php';
?>

<section class="post-page">
  <?php if ($post): ?>
    <?php if (!empty($post['image'])): ?>
      <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-featured-img">
    <?php endif; ?>
    <p class="blog-date"><?php echo htmlspecialchars($post['date']); ?></p>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p class="post-content"><?php echo htmlspecialchars($post['content']); ?></p>
    <a href="blog.php" class="back-link">← Back to Journal</a>
  <?php else: ?>
    <p>Post not found. <a href="blog.php">Back to Journal</a></p>
  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>