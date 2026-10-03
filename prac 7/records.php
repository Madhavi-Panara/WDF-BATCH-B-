<?php

$file=__DIR__."/registrations.csv";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registered Students</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f8fc;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #173b6c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 30px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #285a96;
            color: white;
        }

        tr:nth-child(even) {
            background: #f5f8fc;
        }

        .message {
            text-align: center;
            margin-top: 30px;
            color: #555;
        }

    </style>

</head>

<body>

    <h1>Registered Students</h1>

    <?php

    if(!file_exists($file)) {

        echo "<p class='message'>No registration records found.</p>";

    }
    else {

        $handle=fopen($file,"r");

        if($handle===false) {

            echo "<p class='message'>Unable to open registration file.</p>";

        }
        else {

            echo "<table>";

            $firstRow=true;

            while(($row=fgetcsv($handle))!==false) {

                if($firstRow) {

                    echo "<tr>";

                    foreach($row as $heading) {
                        echo "<th>".htmlspecialchars($heading)."</th>";
                    }

                    echo "</tr>";

                    $firstRow=false;

                }
                else {

                    echo "<tr>";

                    foreach($row as $data) {
                        echo "<td>".htmlspecialchars($data)."</td>";
                    }

                    echo "</tr>";

                }

            }

            echo "</table>";

            fclose($handle);

        }

    }

    ?>

</body>

</html>