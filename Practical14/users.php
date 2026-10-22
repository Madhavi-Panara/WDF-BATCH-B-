<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'audit.php';
requireAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    $id = filter_input(INPUT_POST, 'toggle_id', FILTER_VALIDATE_INT);
    if ($id && $id !== (int)$_SESSION['user_id']) {
        $stmt = $pdo->prepare("UPDATE users SET status = IF(status = 'active', 'inactive', 'active') WHERE id = :id");
        $stmt->execute([':id' => $id]);
        writeAuditLog($pdo, (int)$_SESSION['user_id'], 'TOGGLE_USER_STATUS', 'Changed status for user ID ' . $id);
        $message = 'User status updated.';
    }
}
$users = $pdo->query("SELECT id, username, role, status FROM users ORDER BY id")->fetchAll();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Users</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><strong>StudentHub / Users</strong><span><?= htmlspecialchars($_SESSION['username']) ?></span></header>
<div class="layout"><?php include 'menu.php'; ?><main class="content"><h1>Manage Users</h1>
<?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
<div class="panel table-responsive"><table class="table"><thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php foreach ($users as $u): ?><tr><td><?= (int)$u['id'] ?></td><td><?= htmlspecialchars($u['username']) ?></td><td><?= htmlspecialchars($u['role']) ?></td><td><?= htmlspecialchars($u['status']) ?></td><td>
<?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?><form method="post"><input type="hidden" name="toggle_id" value="<?= (int)$u['id'] ?>"><button class="btn btn-sm btn-outline-primary">Toggle status</button></form><?php else: ?>Current user<?php endif; ?>
</td></tr><?php endforeach; ?></tbody></table></div></main></div></body></html>
