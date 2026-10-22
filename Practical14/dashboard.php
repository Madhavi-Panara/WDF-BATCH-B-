<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'audit.php';
requireAdmin();

$studentCount = (int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$eventCount = (int)$pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$userCount = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$latest = [];
$registrationError = '';
try {
    // Uses the existing singular registration table. Adjust the column names if your table differs.
    $latest = $pdo->query(
        "SELECT r.*, s.name AS student_name, e.event_name
         FROM registration r
         LEFT JOIN students s ON r.student_id = s.student_id
         LEFT JOIN events e ON r.event_id = e.event_id
         ORDER BY r.registration_id DESC LIMIT 5"
    )->fetchAll();
} catch (PDOException $e) {
    $registrationError = 'Could not load latest registrations. Check the registration table and its column names.';
}
writeAuditLog($pdo, (int)$_SESSION['user_id'], 'VIEW_DASHBOARD', 'Admin opened dashboard');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard | StudentHub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div><strong>StudentHub</strong><span> / Admin Dashboard</span></div>
    <div>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> <span class="role-pill"><?= htmlspecialchars($_SESSION['role']) ?></span></div>
</header>
<div class="layout">
    <?php include 'menu.php'; ?>
    <main class="content">
        <h1>Dashboard Overview</h1>
        <p class="muted">A quick summary of StudentHub activity.</p>
        <div class="stats-grid">
            <div class="stat-card"><span>Total Students</span><strong><?= $studentCount ?></strong></div>
            <div class="stat-card"><span>Total Events</span><strong><?= $eventCount ?></strong></div>
            <div class="stat-card"><span>Total Users</span><strong><?= $userCount ?></strong></div>
        </div>
        <section class="panel">
            <h2>Latest Registrations</h2>
            <?php if ($registrationError): ?>
                <div class="alert alert-warning"><?= htmlspecialchars($registrationError) ?></div>
            <?php elseif (!$latest): ?>
                <p class="muted">No registrations found.</p>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Registration ID</th><th>Student</th><th>Event</th></tr></thead>
                    <tbody>
                    <?php foreach ($latest as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($row['registration_id'] ?? $row['id'] ?? '—')) ?></td>
                            <td><?= htmlspecialchars((string)($row['student_name'] ?? $row['student_id'] ?? '—')) ?></td>
                            <td><?= htmlspecialchars((string)($row['event_name'] ?? $row['event_id'] ?? '—')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </section>
    </main>
</div>
<script src="script.js"></script>
</body>
</html>
