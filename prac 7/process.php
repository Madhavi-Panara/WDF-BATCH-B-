<?php

if($_SERVER["REQUEST_METHOD"]==="POST") {

    $event=trim($_POST["event"] ?? "");
    $name=trim($_POST["name"] ?? "");
    $email=trim($_POST["email"] ?? "");
    $mobile=trim($_POST["mobile"] ?? "");
    $course=trim($_POST["course"] ?? "");
    $year=trim($_POST["year"] ?? "");
    $message=trim($_POST["message"] ?? "");
    $terms=trim($_POST["terms"] ?? "");

    $name=htmlspecialchars($name);
    $email=htmlspecialchars($email);
    $mobile=htmlspecialchars($mobile);
    $message=htmlspecialchars($message);

    $errors=[];


    if(empty($event)) {
        $errors[]="Please select an event.";
    }


    if(empty($name)) {
        $errors[]="Name is required.";
    }
    elseif(!preg_match("/^[A-Za-z ]+$/",$name)) {
        $errors[]="Name should contain only letters.";
    }


    if(empty($email)) {
        $errors[]="Email is required.";
    }
    elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $errors[]="Enter a valid email address.";
    }


    if(empty($mobile)) {
        $errors[]="Mobile number is required.";
    }
    elseif(!preg_match("/^[0-9]{10}$/",$mobile)) {
        $errors[]="Mobile number must contain 10 digits.";
    }


    if(empty($course)) {
        $errors[]="Please select a course.";
    }


    if(empty($year)) {
        $errors[]="Please select your year.";
    }


    if(empty($terms)) {
        $errors[]="Please confirm the information.";
    }


    if(count($errors)>0) {

        echo "<h2>Form Submission Failed</h2>";

        foreach($errors as $error) {
            echo "<p>".$error."</p>";
        }

    }
  else {

    $file=__DIR__."/registrations.csv";

    $isNewFile=!file_exists($file) || filesize($file)===0;

    $handle=fopen($file,"a");

    if($handle===false) {

        echo "<h2>Error</h2>";
        echo "<p>Unable to save registration.</p>";
        exit;

    }

    if($isNewFile) {

        fputcsv($handle,[
            "Event",
            "Name",
            "Email",
            "Mobile",
            "Course",
            "Year",
            "Message"
        ]);

    }

    fputcsv($handle,[
        $event,
        $name,
        $email,
        $mobile,
        $course,
        $year,
        $message
    ]);

    fclose($handle);

    echo "<h2>Registration Successful!</h2>";
    echo "<p>Your registration has been saved successfully.</p>";
    echo "<p>Thank you for registering for the event.</p>";
    echo "<a href='event.html'>Back to Registration Form</a>";

}
}
else{

     echo "Invalid request.";

}
?>