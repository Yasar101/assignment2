<?php
require_once __DIR__ . '/functions.php';
require_login();

// List projects for current user
$uid = current_user_id();
$stmt = $pdo->prepare('SELECT * FROM projects WHERE uid = ? ORDER BY start_date DESC');
$stmt->execute([$uid]);
$projects = $stmt->fetchAll();

include __DIR__ . '/header.php';
?>
<h2 class="zoom-title">
  
    <span>MY PROJECTS</span>
</h2>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>My Projects</h2>
  <a class="btn btn-primary" href="add_project.php"><i class="bi bi-plus"></i> Add Project</a>
</div>

<div class="row g-4">
<?php foreach ($projects as $p): ?>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h5><?php echo e($p['title']); ?></h5>
        <p class="text-muted small">Start: <?php echo e($p['start_date']); ?> | <span class="badge bg-secondary"><?php echo e($p['phase']); ?></span></p>
        <p><?php echo e(substr($p['short_description'],0,200)); ?></p>
        <a class="btn btn-sm btn-outline-secondary" href="edit_project.php?pid=<?php echo e($p['pid']); ?>"><i class="bi bi-pencil"></i> Edit</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
