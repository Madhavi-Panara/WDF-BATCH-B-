<?php

require "db.php";

$studentPassword=password_hash("student123",PASSWORD_DEFAULT);
$adminPassword=password_hash("admin123",PASSWORD_DEFAULT);

$stmt=$conn->prepare(
    "INSERT INTO users
    (username,email,password,first_name,last_name,student_id,course,year,mobile,role)
    VALUES(?,?,?,?,?,?,?,?,?,?)"
);

$username="student01";
$email="student@gmail.com";
$firstName="Test";
$lastName="Student";
$studentId="STU001";
$course="B.Tech";
$year=2;
$mobile="9876543210";
$role="student";

$stmt->bind_param(
    "ssssssisss",
    $username,
    $email,
    $studentPassword,
    $firstName,
    $lastName,
    $studentId,
    $course,
    $year,
    $mobile,
    $role
);

$stmt->execute();

$username="admin01";
$email="admin@gmail.com";
$firstName="Test";
$lastName="Admin";
$studentId="ADM001";
$course="Administration";
$year=0;
$mobile="9876543211";
$role="admin";

$stmt->bind_param(
    "ssssssisss",
    $username,
    $email,
    $adminPassword,
    $firstName,
    $lastName,
    $studentId,
    $course,
    $year,
    $mobile,
    $role
);

$stmt->execute();

echo "Test users created successfully.";

$stmt->close();
$conn->close();

?>