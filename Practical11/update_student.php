
<?php
require "db.php";

$id=(int)($_POST["student_iid"] ?? 0);
$name=trim($_POST["name"] ?? "");
$email=trim($_POST["email"] ?? "");
$course=trim($_POST["course"] ?? "");
$year=(int)($_POST["year"] ?? 0);

if($id<=0 || $name==="" || !filter_var($email,FILTER_VALIDATE_EMAIL) || !in_array($course,["B.Tech","BCA","MCA","BBA"],true) || $year<1 || $year>4) {
    die("Invalid student details.");
}

$stmt=$conn->prepare(
    "UPDATE students SET name=?,email=?,course=?,year=? WHERE student_iid=?"
);

$stmt->bind_param("sssii",$name,$email,$course,$year,$id);

if($stmt->execute()) {
    header("Location: students.php?message=updated");
    exit;
} else {
    echo "Unable to update student.";
}

$stmt->close();
$conn->close();
?>
