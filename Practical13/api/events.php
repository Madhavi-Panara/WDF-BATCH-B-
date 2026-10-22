
<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../db.php";

try {
    $stmt = $pdo->query(
        "SELECT event_id, event_name, event_date, category,
                location, description, poster, status
         FROM events
         ORDER BY event_id DESC"
    );

    $events = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "data" => $events
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to fetch events."
    ]);
}
?>
