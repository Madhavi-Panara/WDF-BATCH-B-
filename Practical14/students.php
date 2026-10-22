<?php
require_once 'db.php'; require_once 'auth.php'; requireLogin();
$rows = $pdo->query("SELECT * FROM students ORDER BY student_id DESC LIMIT 100")->fetchAll();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Students</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><strong>StudentHub / Students</strong><span><?= htmlspecialchars($_SESSION['username']) ?></span></header><div class="layout"><?php include 'menu.php'; ?><main class="content"><h1>Students</h1><div class="panel table-responsive"><table class="table"><thead><tr><?php if ($rows): foreach (array_keys($rows[0]) as $key): ?><th><?= htmlspecialchars($key) ?></th><?php endforeach; else: ?><th>Data</th><?php endif; ?></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><?php foreach ($row as $value): ?><td><?= htmlspecialchars((string)$value) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div></main></div></body></html>
