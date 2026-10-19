
<?php
require "db.php";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=students.csv");

$output=fopen("php://output","w");

fputcsv($output,["ID","Name","Email","Course","Year"]);

$stmt=$conn->prepare(
    "SELECT student_iid,name,email,course,year FROM students ORDER BY student_iid DESC"
);

$stmt->execute();
$result=$stmt->get_result();

while($student=$result->fetch_assoc()) {
    fputcsv($output,[
        $student["student_iid"],
        $student["name"],
        $student["email"],
        $student["course"],
        $student["year"]
    ]);
}

fclose($output);
$stmt->close();
$conn->close();
exit;
?>
