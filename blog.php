<?php
require 'posts.php';
$posts = getPosts();
include 'includes/header.php';
?>

<section class="blog-hero">
  <h1>Journal</h1>
  <p>Stories, style notes, and a few things we've learned along the way.</p>
</section>

<section class="blog">
  <div class="blog-list">
    <?php foreach ($posts as $id => $post): ?>
      <a href="post.php?id=<?php echo $id; ?>" class="blog-card">
        <?php if (!empty($post['image'])): ?>
          <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-card-img">
        <?php endif; ?>
        <div class="blog-card-body">
          <p class="blog-date"><?php echo htmlspecialchars($post['date']); ?></p>
          <h3><?php echo htmlspecialchars($post['title']); ?></h3>
          <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>