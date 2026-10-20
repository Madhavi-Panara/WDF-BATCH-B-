
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
$name=trim($_POST["event_name"] ?? "");
$date=$_POST["event_date"] ?? "";
$category=trim($_POST["category"] ?? "");
$location=trim($_POST["location"] ?? "");
$description=trim($_POST["description"] ?? "");
$status=$_POST["status"] ?? "";

$allowedCategories=["Workshop","Sports","Cultural","Technical","Other"];
$allowedStatuses=["Open","Closed"];

if(!$id || $id<1 || $name==="" || $location==="" ||
   !in_array($category,$allowedCategories,true) ||
   !in_array($status,$allowedStatuses,true) ||
   !preg_match('/^\d{4}-\d{2}-\d{2}$/',$date) ||
   !checkdate((int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4))) {
    header("Location: events.php?message=invalid");
    exit;
}

$stmt=$conn->prepare("SELECT poster FROM events WHERE event_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();

if($result->num_rows!==1) {
    header("Location: events.php");
    exit;
}

$oldEvent=$result->fetch_assoc();
$oldPoster=$oldEvent["poster"] ?? "";
$newPoster=$oldPoster;
$newPosterPath=null;

if(isset($_FILES["poster"]) && $_FILES["poster"]["error"]!==UPLOAD_ERR_NO_FILE) {
    if($_FILES["poster"]["error"]!==UPLOAD_ERR_OK) {
        header("Location: events.php?message=upload_error");
        exit;
    }

    $file=$_FILES["poster"];

    if($file["size"]<=0 || $file["size"]>2*1024*1024) {
        header("Location: events.php?message=size");
        exit;
    }

    $finfo=new finfo(FILEINFO_MIME_TYPE);
    $mime=$finfo->file($file["tmp_name"]);

    $allowedTypes=[
        "image/jpeg"=>"jpg",
        "image/png"=>"png",
        "image/webp"=>"webp"
    ];

    if(!isset($allowedTypes[$mime])) {
        header("Location: events.php?message=type");
        exit;
    }

    $uploadDir=__DIR__."/uploads/";

    if(!is_dir($uploadDir) || !is_writable($uploadDir)) {
        header("Location: events.php?message=folder");
        exit;
    }

    $newPoster=bin2hex(random_bytes(16)).".".$allowedTypes[$mime];
    $newPosterPath=$uploadDir.$newPoster;

    if(!move_uploaded_file($file["tmp_name"],$newPosterPath)) {
        header("Location: events.php?message=upload_error");
        exit;
    }
}

$stmt=$conn->prepare(
    "UPDATE events
     SET event_name=?,event_date=?,category=?,location=?,description=?,poster=?,status=?
     WHERE event_id=?"
);

$stmt->bind_param(
    "sssssssi",
    $name,$date,$category,$location,$description,$newPoster,$status,$id
);

if($stmt->execute()) {
    if($newPosterPath!==null && $oldPoster!=="") {
        $oldPath=__DIR__."/uploads/".basename($oldPoster);
        if(is_file($oldPath)) {
            unlink($oldPath);
        }
    }

    header("Location: events.php?message=updated");
    exit;
}

if($newPosterPath!==null && is_file($newPosterPath)) {
    unlink($newPosterPath);
}

header("Location: events.php?message=poster_failed");
exit;
?>
