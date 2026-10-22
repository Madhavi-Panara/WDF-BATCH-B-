<?php
require_once 'db.php'; require_once 'auth.php'; requireAdmin();
$logs = $pdo->query("SELECT a.id, u.username, a.action, a.details, a.ip_address, a.created_at FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.id DESC LIMIT 200")->fetchAll();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Audit Logs</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><strong>StudentHub / Audit Logs</strong><span><?= htmlspecialchars($_SESSION['username']) ?></span></header><div class="layout"><?php include 'menu.php'; ?><main class="content"><h1>Audit Logs</h1><div class="panel table-responsive"><table class="table"><thead><tr><th>ID</th><th>User</th><th>Action</th><th>Details</th><th>IP</th><th>Time</th></tr></thead><tbody><?php foreach ($logs as $log): ?><tr><?php foreach ($log as $v): ?><td><?= htmlspecialchars((string)($v ?? '—')) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div></main></div></body></html>
