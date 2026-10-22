<?php
require_once 'db.php';
require_once 'audit.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header("Location: index.php?error=invalid");
    exit;
}

$stmt = $pdo->prepare("SELECT id, username, password, role, status FROM users WHERE username = :username LIMIT 1");
$stmt->execute([':username' => $username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    header("Location: index.php?error=invalid");
    exit;
}
if (($user['status'] ?? 'active') !== 'active') {
    header("Location: index.php?error=inactive");
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

writeAuditLog($pdo, (int)$user['id'], 'LOGIN', 'User logged in');
header("Location: dashboard.php");
exit;
?>
