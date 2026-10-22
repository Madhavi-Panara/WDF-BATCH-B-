<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'audit.php';
requireLogin();
writeAuditLog($pdo, (int)$_SESSION['user_id'], 'LOGOUT', 'User logged out');
$_SESSION = [];
session_destroy();
header("Location: index.php");
exit;
?>
