<?php

session_start();

if(!isset($_SESSION["user_id"])) {

    header("Location: login.html");
    exit;

}

if($_SESSION["role"]!=="student") {

    header("Location: admin_dashboard.php");
    exit;

}

$timeout=300;

if(isset($_SESSION["last_activity"]) &&
   time()-$_SESSION["last_activity"]>$timeout) {

    session_unset();
    session_destroy();

    header("Location: login.html?timeout=1");
    exit;

}

$_SESSION["last_activity"]=time();

$username=$_SESSION["username"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Student Dashboard</title>

</head>

<body>

    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

    <p>You have successfully logged in.</p>

    <p>Role: Student</p>

    <a href="logout.php">Logout</a>

</body>

</html>