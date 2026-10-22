<?php
require_once 'db.php'; require_once 'auth.php'; requireLogin();
$rows = [];
$error = '';
try {
    $rows = $pdo->query(
        "SELECT r.*, s.name AS student_name, e.event_name
         FROM registration r
         LEFT JOIN students s ON r.student_id = s.student_id
         LEFT JOIN events e ON r.event_id = e.event_id
         ORDER BY r.registration_id DESC LIMIT 100"
    )->fetchAll();
} catch (PDOException $e) {
    $error = 'Check your registration table and column names in phpMyAdmin.';
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Registrations</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><strong>StudentHub / Registrations</strong><span><?= htmlspecialchars($_SESSION['username']) ?></span></header><div class="layout"><?php include 'menu.php'; ?><main class="content"><h1>Registrations</h1><?php if ($error): ?><div class="alert alert-warning"><?= htmlspecialchars($error) ?></div><?php endif; ?><div class="panel table-responsive"><table class="table"><thead><tr><?php if ($rows): foreach (array_keys($rows[0]) as $key): ?><th><?= htmlspecialchars($key) ?></th><?php endforeach; else: ?><th>Registration data</th><?php endif; ?></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><?php foreach ($row as $v): ?><td><?= htmlspecialchars((string)$v) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div></main></div></body></html>
