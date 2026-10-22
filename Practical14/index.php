<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>StudentHub Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
<div class="login-card">
    <h1>StudentHub</h1>
    <p class="muted">Admin Dashboard Login</p>
    <?php if ($error === 'invalid'): ?><div class="alert alert-danger">Invalid username or password.</div><?php endif; ?>
    <?php if ($error === 'inactive'): ?><div class="alert alert-warning">This account is inactive.</div><?php endif; ?>
    <form action="login.php" method="post">
        <label class="form-label" for="username">Username</label>
        <input class="form-control mb-3" id="username" name="username" required maxlength="50" autocomplete="username">
        <label class="form-label" for="password">Password</label>
        <input class="form-control mb-4" id="password" name="password" type="password" required autocomplete="current-password">
        <button class="btn btn-primary w-100" type="submit">Sign in</button>
    </form>
</div>
</body>
</html>
