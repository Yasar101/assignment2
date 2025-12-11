<?php
require_once __DIR__ . '/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login_attempts_exceeded()) {
        $errors[] = 'Too many login attempts. Try later.';
    } elseif (!csrf_check($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $errors[] = 'Missing credentials.';
            record_login_attempt(false);
        } else {
            $stmt = $pdo->prepare('SELECT uid, username, password, email FROM users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if (!$user || !password_verify($password, $user['password'])) {
                $errors[] = 'Invalid credentials.';
                record_login_attempt(false);
            } else {
                // Success
                record_login_attempt(true);
                session_regenerate_id(true);
                $_SESSION['user'] = ['uid' => $user['uid'], 'username' => $user['username'], 'email' => $user['email']];
                header('Location: projects.php');
                exit;
            }
        }
    }
}

include __DIR__ . '/header.php';
?>
<h2 class="zoom-title">
  
    <span>LOGIN</span>
</h2>
<br>
<?php if ($errors): ?>
  <ul><?php foreach ($errors as $e) echo '<li>' . e($e) . '</li>'; ?></ul>
<?php endif; ?>
<form method="post" action="login.php">
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>Username: <input name="username" required></label><br><br>
  <label>Password: <input name="password" type="password" required></label><br>
  <br> <button type="submit">Login</button>
</form>
<?php include __DIR__ . '/footer.php'; ?>
