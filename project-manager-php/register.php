<?php
require_once __DIR__ . '/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
        if (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[A-Za-z]/', $password)) {
    $errors[] = 'Password must be at least 8 characters and include letters and numbers.';
}

        if (empty($errors)) {
            // Check duplicates
            $stmt = $pdo->prepare('SELECT uid FROM users WHERE username = ? OR email = ? LIMIT 1');
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $errors[] = 'Username or email already used.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
                $stmt->execute([$username, $hash, $email]);
                $uid = $pdo->lastInsertId();
                session_regenerate_id(true);
                $_SESSION['user'] = ['uid' => $uid, 'username' => $username, 'email' => $email];
                header('Location: projects.php');
                exit;
            }
        }
    }
}

include __DIR__ . '/header.php';
?>

<h2 class="zoom-title">
  
    <span>REGISTER</span>
</h2>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="card-title">Create an account</h4>
        <?php if ($errors): ?>
          <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e) echo '<li>' . e($e) . '</li>'; ?></ul>
          </div>
        <?php endif; ?>
        <form method="post" action="register.php" id="registerForm" novalidate>
          <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input class="form-control" name="username" required minlength="3">
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" name="email" type="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" name="password" type="password" required minlength="8" aria-describedby="passwordHelp">
            <div id="passwordHelp" class="form-text">Use 8+ characters, include letters & numbers.</div>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary" type="submit">Register</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
