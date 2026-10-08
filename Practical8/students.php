<?php

require "db.php";

$sql="SELECT * FROM students";

$stmt=$pdo->prepare($sql);

$stmt->execute();

$students=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Students</title>

</head>

<body>

    <h1>Student Records</h1>

    <?php

    foreach($students as $student) {

        echo "<p>";
        echo $student["student_iid"]." - ";
        echo htmlspecialchars($student["name"])." - ";
        echo htmlspecialchars($student["email"])." - ";
        echo htmlspecialchars($student["course"])." - ";
        echo $student["year"];
        echo "</p>";

    }

    ?>

</body>

</html>