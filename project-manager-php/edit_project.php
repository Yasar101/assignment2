<?php
require_once __DIR__ . '/functions.php';
require_login();
$pid = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
$stmt = $pdo->prepare('SELECT * FROM projects WHERE pid = ? LIMIT 1');
$stmt->execute([$pid]);
$project = $stmt->fetch();
if (!$project) { http_response_code(404); echo 'Not found'; exit; }
if ($project['uid'] != current_user_id()) { http_response_code(403); echo 'Forbidden'; exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf_token'] ?? '')) { $errors[] = 'Invalid CSRF token.'; }
    $title = trim($_POST['title'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? null;
    $short_description = trim($_POST['short_description'] ?? '');
    $phase = $_POST['phase'] ?? 'design';

    if (strlen($title) < 3) $errors[] = 'Title min 3 chars';
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date)) $errors[] = 'Invalid start date';
    if ($end_date && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) $errors[] = 'Invalid end date';
    if (strlen($short_description) < 10) $errors[] = 'Description min 10 chars';
    $allowed = ['design','development','testing','deployment','complete'];
    if (!in_array($phase, $allowed)) $errors[] = 'Invalid phase';

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE projects SET title = ?, start_date = ?, end_date = ?, short_description = ?, phase = ? WHERE pid = ?');
        $stmt->execute([$title, $start_date, $end_date ?: null, $short_description, $phase, $pid]);
        header('Location: projects.php');
        exit;
    }
}

include __DIR__ . '/header.php';
?>
<h2>Edit Project</h2>
<?php if ($errors): ?><ul><?php foreach ($errors as $e) echo '<li>' . e($e) . '</li>'; ?></ul><?php endif; ?>
<form method="post" action="edit_project.php?pid=<?php echo e($pid); ?>">
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>Title: <input name="title" value="<?php echo e($project['title']); ?>" required></label><br>
  <label>Start Date: <input name="start_date" type="date" value="<?php echo e($project['start_date']); ?>" required></label><br>
  <label>End Date: <input name="end_date" type="date" value="<?php echo e($project['end_date']); ?>"></label><br>
  <label>Short Description:<br><textarea name="short_description" required><?php echo e($project['short_description']); ?></textarea></label><br>
  <label>Phase:
    <select name="phase">
      <option value="design" <?php if($project['phase']=='design') echo 'selected'; ?>>Design</option>
      <option value="development" <?php if($project['phase']=='development') echo 'selected'; ?>>Development</option>
      <option value="testing" <?php if($project['phase']=='testing') echo 'selected'; ?>>Testing</option>
      <option value="deployment" <?php if($project['phase']=='deployment') echo 'selected'; ?>>Deployment</option>
      <option value="complete" <?php if($project['phase']=='complete') echo 'selected'; ?>>Complete</option>
    </select>
  </label><br>
  <button type="submit">Update</button>
</form>
<?php include __DIR__ . '/footer.php'; ?>
