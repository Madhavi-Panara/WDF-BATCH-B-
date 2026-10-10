<?php

require "db.php";

if($_SERVER["REQUEST_METHOD"]==="POST") {

    $firstName=trim($_POST["firstName"] ?? "");
    $lastName=trim($_POST["lastName"] ?? "");
    $email=trim($_POST["email"] ?? "");
    $studentId=trim($_POST["studentId"] ?? "");
    $course=trim($_POST["course"] ?? "");
    $year=trim($_POST["year"] ?? "");
    $mobile=trim($_POST["mobile"] ?? "");
    $password=$_POST["password"] ?? "";
    $confirmPassword=$_POST["confirmPassword"] ?? "";

    $errors=[];

    if(empty($firstName) || !preg_match("/^[A-Za-z ]+$/",$firstName)) {
        $errors[]="Enter a valid first name.";
    }

    if(empty($lastName) || !preg_match("/^[A-Za-z ]+$/",$lastName)) {
        $errors[]="Enter a valid last name.";
    }

    if(empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $errors[]="Enter a valid email address.";
    }

    if(empty($studentId) || !preg_match("/^[A-Za-z0-9]+$/",$studentId)) {
        $errors[]="Enter a valid Student ID.";
    }

    if(empty($course)) {
        $errors[]="Please select a course.";
    }

    if(empty($year)) {
        $errors[]="Please select your year.";
    }

    if(empty($mobile) || !preg_match("/^[0-9]{10}$/",$mobile)) {
        $errors[]="Enter a valid 10-digit mobile number.";
    }

    if(empty($password) || strlen($password)<6) {
        $errors[]="Password must contain at least 6 characters.";
    }

    if($password!==$confirmPassword) {
        $errors[]="Passwords do not match.";
    }

    if(count($errors)>0) {

        echo "<h2>Registration Failed</h2>";

        foreach($errors as $error) {
            echo "<p>".htmlspecialchars($error)."</p>";
        }

        exit;
    }

    $check=$conn->prepare(
        "SELECT id FROM users WHERE email=? OR student_id=?"
    );

    $check->bind_param("ss",$email,$studentId);

    $check->execute();

    $result=$check->get_result();

    if($result->num_rows>0) {

        echo "<h2>Registration Failed</h2>";
        echo "<p>Email or Student ID already exists.</p>";

        $check->close();
        exit;
    }

    $hashedPassword=password_hash($password,PASSWORD_DEFAULT);

    $username=$studentId;

    $stmt=$conn->prepare(
        "INSERT INTO users
        (username,email,password,first_name,last_name,student_id,course,year,mobile)
        VALUES(?,?,?,?,?,?,?,?,?)"
    );

    $stmt->bind_param(
        "sssssssis",
        $username,
        $email,
        $hashedPassword,
        $firstName,
        $lastName,
        $studentId,
        $course,
        $year,
        $mobile
    );

    if($stmt->execute()) {

    header("Location: login.html");
    exit;

}
else {

    echo "<h2>Registration Failed</h2>";
    echo "<p>Unable to create your account.</p>";

}
    $stmt->close();
    $check->close();
    $conn->close();

}
else {

    echo "Invalid request.";

}

?>