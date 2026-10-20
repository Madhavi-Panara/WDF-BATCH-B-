
<?php
session_start();
require "db.php";

if(!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "")!=="admin") {
    http_response_code(403);
    exit("Access denied. Admin login required.");
}

$id=filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT);

if(!$id || $id<1) {
    header("Location: events.php");
    exit;
}

$stmt=$conn->prepare("SELECT * FROM events WHERE event_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows!==1) {
    header("Location: events.php");
    exit;
}

$event=$result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Event</title>
<link rel="stylesheet" href="events.css">
</head>
<body>
<div class="container">
<h1>Edit Event</h1>
<p class="subtitle">Update the event details below.</p>

<div class="panel">
<form action="update_event.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="event_id" value="<?php echo (int)$event["event_id"]; ?>">

<label>Event Name</label>
<input type="text" name="event_name" value="<?php echo htmlspecialchars($event["event_name"]); ?>" required>

<label>Event Date</label>
<input type="date" name="event_date" value="<?php echo htmlspecialchars($event["event_date"]); ?>" required>

<label>Category</label>
<select name="category" required>
<?php
$categories=["Workshop","Sports","Cultural","Technical","Other"];
foreach($categories as $category) {
    $selected=$event["category"]===$category?"selected":"";
    echo "<option value='".htmlspecialchars($category)."' $selected>".htmlspecialchars($category)."</option>";
}
?>
</select>

<label>Location</label>
<input type="text" name="location" value="<?php echo htmlspecialchars($event["location"]); ?>" required>

<label>Description</label>
<textarea name="description" rows="4"><?php echo htmlspecialchars($event["description"] ?? ""); ?></textarea>

<label>Current Poster</label>
<?php if(!empty($event["poster"])) { ?>
<img class="poster" src="uploads/<?php echo rawurlencode(basename($event["poster"])); ?>" alt="Current event poster">
<?php } else { ?>
<p>No poster uploaded.</p>
<?php } ?>

<label>Choose a New Poster (optional, JPG, PNG, WebP; max 2 MB)</label>
<input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp">

<label>Status</label>
<select name="status" required>
<option value="Open" <?php echo ($event["status"] ?? "Open")==="Open"?"selected":""; ?>>Open</option>
<option value="Closed" <?php echo ($event["status"] ?? "Open")==="Closed"?"selected":""; ?>>Closed</option>
</select>

<button type="submit">Update Event</button>
<a href="events.php">Cancel</a>
</form>
</div>
</div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
