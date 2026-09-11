<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($message === '') {
        $errors[] = 'Message is required.';
    }

    if (empty($errors)) {
        // Store the name temporarily so the confirmation page can greet them
        $_SESSION['contact_success_name'] = $name;
        header('Location: contact.php?sent=1');
        exit;
    }
}

$success = isset($_GET['sent']) && !empty($_SESSION['contact_success_name']);
$successName = $success ? $_SESSION['contact_success_name'] : '';

if ($success) {
    unset($_SESSION['contact_success_name']); // clear it so it doesn't persist forever
}

include 'includes/header.php';
?>

<section class="contact">
  <h1>Get in touch</h1>

  <?php if ($success): ?>
    <p class="success">Thanks, <?php echo htmlspecialchars($successName); ?> — your message was received.</p>
  <?php else: ?>

    <?php if (!empty($errors)): ?>
      <ul class="errors">
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <form method="POST" action="contact.php" class="contact-form">
      <label>
        Name
        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
      </label>
      <label>
        Email
        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
      </label>
      <label>
        Message
        <textarea name="message" rows="5"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
      </label>
      <button type="submit">Send Message</button>
    </form>

  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>