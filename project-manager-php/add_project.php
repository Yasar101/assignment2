<?php
require_once __DIR__ . '/functions.php';
require_login();

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
        $stmt = $pdo->prepare('INSERT INTO projects (title, start_date, end_date, short_description, phase, uid) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$title, $start_date, $end_date ?: null, $short_description, $phase, current_user_id()]);
        header('Location: projects.php');
        exit;
    }
}

include __DIR__ . '/header.php';
?>
<h2>Add Project</h2>
<?php if ($errors): ?><ul><?php foreach ($errors as $e) echo '<li>' . e($e) . '</li>'; ?></ul><?php endif; ?>
<form method="post" action="add_project.php">
  <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
  <label>Title: <input name="title" required></label><br>
  <label>Start Date: <input name="start_date" type="date" required></label><br>
  <label>End Date: <input name="end_date" type="date"></label><br>
  <label>Short Description:<br><textarea name="short_description" required></textarea></label><br>
  <label>Phase:
    <select name="phase">
      <option value="design">Design</option>
      <option value="development">Development</option>
      <option value="testing">Testing</option>
      <option value="deployment">Deployment</option>
      <option value="complete">Complete</option>
    </select>
  </label><br>
  <button type="submit">Create</button>
</form>
<?php include __DIR__ . '/footer.php'; ?>
