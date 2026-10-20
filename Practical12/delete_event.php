
<?php
session_start();
require "db.php";

if(!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "")!=="admin") {
    http_response_code(403);
    exit("Access denied. Admin login required.");
}

if($_SERVER["REQUEST_METHOD"]!=="POST") {
    header("Location: events.php");
    exit;
}

$id=filter_input(INPUT_POST,"event_id",FILTER_VALIDATE_INT);

if(!$id || $id<1) {
    header("Location: events.php?message=failed");
    exit;
}

$stmt=$conn->prepare("SELECT poster FROM events WHERE event_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows!==1) {
    header("Location: events.php?message=failed");
    exit;
}

$event=$result->fetch_assoc();
$poster=$event["poster"] ?? "";

$stmt=$conn->prepare("DELETE FROM events WHERE event_id=?");
$stmt->bind_param("i",$id);

if($stmt->execute()) {
    if($poster!=="") {
        $posterPath=__DIR__."/uploads/".basename($poster);
        if(is_file($posterPath)) {
            unlink($posterPath);
        }
    }

    header("Location: events.php?message=deleted");
    exit;
}

header("Location: events.php?message=failed");
exit;
?>
