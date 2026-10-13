<?php

session_start();

require "db.php";

if($_SERVER["REQUEST_METHOD"]==="POST") {

    $username=trim($_POST["username"] ?? "");
    $password=$_POST["password"] ?? "";

    if(empty($username) || empty($password)) {

        echo "<h2>Login Failed</h2>";
        echo "<p>Username and password are required.</p>";
        exit;

    }

    $stmt=$conn->prepare(
        "SELECT id,username,email,password,role
         FROM users
         WHERE username=?"
    );

    $stmt->bind_param("s",$username);

    $stmt->execute();

    $result=$stmt->get_result();

    if($result->num_rows===1) {

        $user=$result->fetch_assoc();

        if(password_verify($password,$user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"]=$user["id"];
            $_SESSION["username"]=$user["username"];
            $_SESSION["role"]=$user["role"];
            $_SESSION["last_activity"]=time();

            if($user["role"]==="admin") {

                header("Location: admin_dashboard.php");
                exit;

            }
            else {

                header("Location: student_dashboard.php");
                exit;

            }

        }
        else {

            echo "<h2>Login Failed</h2>";
            echo "<p>Invalid username or password.</p>";

        }

    }
    else {

        echo "<h2>Login Failed</h2>";
        echo "<p>Invalid username or password.</p>";

    }

    $stmt->close();
    $conn->close();

}
else {

    echo "Invalid request.";

}

?>