<?php
require_once __DIR__ . '/functions.php';
$pid = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
$stmt = $pdo->prepare('SELECT p.*, u.email FROM projects p JOIN users u ON p.uid = u.uid WHERE p.pid = ? LIMIT 1');
$stmt->execute([$pid]);
$project = $stmt->fetch();
if (!$project) { http_response_code(404); echo 'Not found'; exit; }
include __DIR__ . '/header.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <h3 class="card-title"><?php echo e($project['title']); ?></h3>
    <p class="text-muted small">Owner: <?php echo e($project['email']); ?></p>
    <p>
      <strong>Start:</strong> <?php echo e($project['start_date']); ?> |
      <strong>End:</strong> <?php echo e($project['end_date'] ?: 'N/A'); ?> |
      <strong>Phase:</strong>
      <?php
        $phase = $project['phase'];
        $map = ['design'=>'secondary','development'=>'primary','testing'=>'warning','deployment'=>'info','complete'=>'success'];
        $cls = $map[$phase] ?? 'secondary';
      ?>
      <span class="badge bg-<?php echo e($cls); ?>"><?php echo e(ucfirst($phase)); ?></span>
    </p>
    <hr>
    <p><?php echo nl2br(e($project['short_description'])); ?></p>
  </div>
</div>
<?php include __DIR__ . '/footer.php'; ?>