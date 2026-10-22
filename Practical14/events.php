<?php
require_once 'db.php'; require_once 'auth.php'; requireLogin();
$rows = $pdo->query("SELECT event_id, event_name, event_date, category, location, status FROM events ORDER BY event_id DESC LIMIT 100")->fetchAll();
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Events</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<header class="topbar"><strong>StudentHub / Events</strong><span><?= htmlspecialchars($_SESSION['username']) ?></span></header><div class="layout"><?php include 'menu.php'; ?><main class="content"><h1>Events</h1><div class="panel table-responsive"><table class="table"><thead><tr><th>ID</th><th>Name</th><th>Date</th><th>Category</th><th>Location</th><th>Status</th></tr></thead><tbody><?php foreach ($rows as $r): ?><tr><?php foreach ($r as $v): ?><td><?= htmlspecialchars((string)$v) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div></main></div></body></html>
