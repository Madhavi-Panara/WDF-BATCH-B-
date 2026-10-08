<?php

require "db.php";

$sql="
    SELECT
        registration.registration_id,
        students.name,
        events.event_name,
        registration.registration_date
    FROM registration
    INNER JOIN students
        ON registration.student_id=students.student_iid
    INNER JOIN events
        ON registration.event_id=events.event_id
";

$stmt=$pdo->prepare($sql);

$stmt->execute();

$registrations=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Registrations</title>

</head>

<body>

    <h1>Event Registrations</h1>

    <?php

    foreach($registrations as $registration) {

        echo "<p>";

        echo $registration["registration_id"]." - ";

        echo htmlspecialchars($registration["name"])." - ";

        echo htmlspecialchars($registration["event_name"])." - ";

        echo $registration["registration_date"];

        echo "</p>";

    }

    ?>

</body>

</html>