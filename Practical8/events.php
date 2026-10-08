<?php

require "db.php";

$sql="SELECT * FROM events";

$stmt=$pdo->prepare($sql);

$stmt->execute();

$events=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Events</title>

</head>

<body>

    <h1>Event Records</h1>

    <?php

    foreach($events as $event) {

        echo "<p>";
        echo $event["event_id"]." - ";
        echo htmlspecialchars($event["event_name"])." - ";
        echo $event["event_date"]." - ";
        echo htmlspecialchars($event["category"])." - ";
        echo htmlspecialchars($event["location"]);
        echo "</p>";

    }

    ?>

</body>

</html>