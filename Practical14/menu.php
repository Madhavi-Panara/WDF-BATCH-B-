<?php
$role = $_SESSION['role'] ?? '';
?>
<nav class="side-menu">
    <a href="dashboard.php">Dashboard</a>
    <?php if ($role === 'admin'): ?>
        <a href="users.php">Manage Users</a>
        <a href="students.php">Students</a>
        <a href="events.php">Events</a>
        <a href="registrations.php">Registrations</a>
        <a href="audit_logs.php">Audit Logs</a>
    <?php elseif ($role === 'staff'): ?>
        <a href="students.php">Students</a>
        <a href="events.php">Events</a>
        <a href="registrations.php">Registrations</a>
    <?php else: ?>
        <a href="events.php">Events</a>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</nav>
