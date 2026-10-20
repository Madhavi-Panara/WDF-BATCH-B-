<?php
session_start();
require "db.php";

if(!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "")!=="admin") {
    http_response_code(403);
    exit("Access denied. Please log in as an admin.");
}

$search=trim($_GET["search"] ?? "");
$category=trim($_GET["category"] ?? "");
$status=trim($_GET["status"] ?? "");

$sql="SELECT * FROM events WHERE 1=1";
$params=[];
$types="";

if($search!=="") {
    $sql.=" AND event_name LIKE ?";
    $params[]="%".$search."%";
    $types.="s";
}

if($category!=="") {
    $sql.=" AND category=?";
    $params[]=$category;
    $types.="s";
}

if($status!=="") {
    $sql.=" AND status=?";
    $params[]=$status;
    $types.="s";
}

$sql.=" ORDER BY event_id DESC";
$stmt=$conn->prepare($sql);

if(!empty($params)) {
    $stmt->bind_param($types,...$params);
}

$stmt->execute();
$result=$stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="events.css">
</head>
<body>
    <div class="container">
        <h1>Event Management</h1>

        
<?php
if(isset($_GET["message"])) {
    
    $messages=[
        "added"=>"Event added successfully!",
        "updated"=>"Event updated successfully!",
        "deleted"=>"Event deleted successfully!",
        "invalid"=>"Please enter valid event details.",
        "size"=>"Poster must be smaller than 2 MB.",
        "type"=>"Only JPG, PNG, and WebP posters are allowed.",
        "upload_error"=>"Poster upload failed. Please try again.",
        "folder"=>"Upload folder is missing or not writable.",
        "failed"=>"Unable to save the event.",
        "poster_failed"=>"Unable to update the poster."
    ];

    $message=$_GET["message"];

    if(isset($messages[$message])) {
        $isSuccess=in_array($message,["added","updated","deleted"],true);
        echo "<div class='notice ".($isSuccess?"success":"error")."'>"
            .htmlspecialchars($messages[$message])
            ."</div>";
    }
}
?>

<script>
setTimeout(function() {
    const notice=document.querySelector(".notice");
    if(notice) {
        notice.style.transition="opacity 0.4s ease";
        notice.style.opacity="0";
        setTimeout(function() {
            notice.remove();
        },400);
    }
},3000);
</script>

        <p class="subtitle">Manage campus events and posters</p>

        <section class="panel">
            <h2>Add New Event</h2>

            <form action="add_event.php" method="POST" enctype="multipart/form-data">
                <label>Event Name</label>
                <input type="text" name="event_name" required>

                <label>Event Date</label>
                <input type="date" name="event_date" required>

                <label>Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Sports">Sports</option>
                    <option value="Cultural">Cultural</option>
                    <option value="Technical">Technical</option>
                    <option value="Other">Other</option>
                </select>

                <label>Location</label>
                <input type="text" name="location" required>

                <label>Description</label>
                <textarea name="description" rows="3"></textarea>

                <label>Event Poster (JPG, PNG, WebP; max 2 MB)</label>
                <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp" required>

                <label>Status</label>
                <select name="status" required>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>

                <button type="submit">Add Event</button>
            </form>
        </section>

        <section class="panel">
            <h2>Event Records</h2>

            <form method="GET" action="events.php" class="filters">
                <input type="text" name="search" placeholder="Search event name">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Sports">Sports</option>
                    <option value="Cultural">Cultural</option>
                    <option value="Technical">Technical</option>
                    <option value="Other">Other</option>
                </select>
                <select name="status">
                    <option value="">All Statuses</option>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>
                <button type="submit">Search / Filter</button>
                <a href="events.php">Reset</a>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($event=$result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $event["event_id"]; ?></td>
                            <td>
                                <?php if(!empty($event["poster"])) { ?>
                                    <img class="poster" src="uploads/<?php echo htmlspecialchars($event["poster"]); ?>" alt="Event poster">
                                <?php } else { echo "No poster"; } ?>
                            </td>
                            <td><?php echo htmlspecialchars($event["event_name"]); ?></td>
                            <td><?php echo htmlspecialchars($event["event_date"]); ?></td>
                            <td><?php echo htmlspecialchars($event["category"]); ?></td>
                            <td><?php echo htmlspecialchars($event["location"]); ?></td>
                            <td><?php echo htmlspecialchars($event["status"] ?? "Open"); ?></td>                            <td>
                                <a href="edit_event.php?id=<?php echo $event["event_id"]; ?>">Edit</a>
                                <form method="POST" action="delete_event.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                    <input type="hidden" name="event_id" value="<?php echo $event["event_id"]; ?>">
                                    <button type="submit" name="delete_event">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>
<?php
$result->free();
$conn->close();
?>
