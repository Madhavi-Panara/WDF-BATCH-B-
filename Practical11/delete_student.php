
<?php
require "db.php";

if($_SERVER["REQUEST_METHOD"]!=="POST" || !isset($_POST["delete_student"])) {
    header("Location: students.php");
    exit;
}

$id=(int)($_POST["student_iid"] ?? 0);

if($id<=0) {
    header("Location: students.php?message=invalid");
    exit;
}

$stmt=$conn->prepare("DELETE FROM students WHERE student_iid=?");
$stmt->bind_param("i",$id);

if($stmt->execute() && $stmt->affected_rows>0) {
    header("Location: students.php?message=deleted");
    exit;
} else {
    echo "Unable to delete student or student not found.";
}

$stmt->close();
$conn->close();
?>
