<?php
require_once __DIR__ . '/functions.php';
require 'db.php'; 
// Handle search params
$search = $_GET['q'] ?? '';
$date = $_GET['date'] ?? '';

$sql = "SELECT p.pid, p.title, p.start_date, p.short_description, p.phase, u.email
        FROM projects p JOIN users u ON p.uid = u.uid";
$params = [];
$conds = [];
if ($search !== '') { $conds[] = 'p.title LIKE ?'; $params[] = '%' . $search . '%'; }
if ($date !== '') { $conds[] = 'p.start_date = ?'; $params[] = $date; }
if (count($conds)) { $sql .= ' WHERE ' . implode(' AND ', $conds); }
$sql .= ' ORDER BY p.start_date DESC LIMIT 100';
 $stmt= $pdo->prepare($sql);
$stmt->execute($params);
$projects = $stmt->fetchAll();

include __DIR__ . '/header.php';
?>
<h2 class="zoom-title">
  
    <span>ALL PROJECTS</span>
</h2>


<div class="row mb-4">
  <div class="col-md-8">
    <form class="row g-2" method="get" action="index.php" novalidate>
      <div class="col-sm-6">
        <input class="form-control" name="q" placeholder="Search title" value="<?php echo e($search); ?>">
      </div>
      <div class="col-sm-4">
        <input class="form-control" name="date" type="date" value="<?php echo e($date); ?>">
      </div>
      <div class="col-sm-2 d-grid">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
      </div>
    </form>
  </div>
</div>

<div class="row g-4">
<?php if (empty($projects)): ?>
  <div class="col-12"><div class="alert alert-info">No projects found.</div></div>
<?php endif; ?>
<?php foreach ($projects as $p): ?>
  <div class="col-md-6 col-lg-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title"><a class="stretched-link text-decoration-none" href="view_project.php?pid=<?php echo e($p['pid']); ?>"><?php echo e($p['title']); ?></a></h5>
        <p class="card-text text-muted small mb-2">Start: <?php echo e($p['start_date']); ?> | <span class="badge bg-secondary"><?php echo e(ucfirst($p['phase'])); ?></span></p>
        <p class="card-text"><?php echo e(substr($p['short_description'],0,160)); ?><?php echo (strlen($p['short_description'])>160 ? '...' : ''); ?></p>
        <div class="mt-auto">
          <a class="btn btn-sm btn-outline-primary" href="view_project.php?pid=<?php echo e($p['pid']); ?>"><i class="bi bi-arrow-right-circle"></i> Details</a>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/footer.php'; ?>