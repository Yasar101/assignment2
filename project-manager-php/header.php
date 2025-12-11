<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Project Manager</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="styles.css">
</head>

<body class="bg-light">

<header class="bg-white shadow-sm">
  <div class="container d-flex align-items-center justify-content-between py-3">

    <h1 class="rainbow-title" || "reveal-title>
  <a href="index.php" class="text-decoration-none text-dark">
    <span " class="typing">Project Manager</span>
  </a>
</h1>

    <nav>
      <a class="btn btn-sm btn-outline-primary me-2" href="index.php">
        <i class="bi bi-list"></i> All Projects
      </a>

      <?php if (!isset($_SESSION['uid'])): ?>
        <a class="btn btn-sm btn-outline-success me-2" href="login.php">
          <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
        <a class="btn btn-sm btn-primary" href="register.php">
          <i class="bi bi-person-plus"></i> Register
        </a>

      <?php else: ?>
        <a class="btn btn-sm btn-outline-secondary me-2" href="dashboard.php">
          <i class="bi bi-folder2-open"></i> My Projects
        </a>

        <form method="post" action="logout.php" style="display:inline;">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <button type="submit" class="btn btn-sm btn-danger">
            <i class="bi bi-box-arrow-right"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)
          </button>
        </form>
      <?php endif; ?>
    </nav>

  </div>
</header>

<main class="container py-4">
