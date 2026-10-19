
<?php
require "db.php";

$id=(int)($_GET["id"] ?? 0);

$stmt=$conn->prepare("SELECT * FROM students WHERE student_iid=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();
$student=$result->fetch_assoc();

if(!$student) {
    die("Student not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
</head>
<body>

<h1>Edit Student Details</h1>

<form method="POST" action="update_student.php">
    <input type="hidden" name="student_iid"
           value="<?php echo $student['student_iid']; ?>">

    <label>Name:</label>
    <input type="text" name="name" required
           value="<?php echo htmlspecialchars($student['name']); ?>">
    <br><br>

    <label>Email:</label>
    <input type="email" name="email" required
           value="<?php echo htmlspecialchars($student['email']); ?>">
    <br><br>

    <label>Course:</label>
    <select name="course" required>
        <?php
        $courses=["B.Tech","BCA","MCA","BBA"];
        foreach($courses as $c) {
            $selected=($student["course"]===$c) ? "selected" : "";
            echo "<option value=\"".htmlspecialchars($c)."\" $selected>".htmlspecialchars($c)."</option>";
        }
        ?>
    </select>
    <br><br>

    <label>Year:</label>
    <select name="year" required>
        <?php
        for($y=1;$y<=4;$y++) {
            $selected=((int)$student["year"]===$y) ? "selected" : "";
            echo "<option value=\"$y\" $selected>$y</option>";
        }
        ?>
    </select>
    <br><br>

    <button type="submit">Update Student</button>
</form>

<br>
<a href="students.php">Back to Student Records</a>

</body>
</html>
