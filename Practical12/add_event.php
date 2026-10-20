
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

$name=trim($_POST["event_name"] ?? "");
$date=$_POST["event_date"] ?? "";
$category=trim($_POST["category"] ?? "");
$location=trim($_POST["location"] ?? "");
$description=trim($_POST["description"] ?? "");
$status=$_POST["status"] ?? "Open";

$allowedCategories=["Workshop","Sports","Cultural","Technical","Other"];
$allowedStatuses=["Open","Closed"];

if($name==="" || $date==="" || $location==="" ||
   !in_array($category,$allowedCategories,true) ||
   !in_array($status,$allowedStatuses,true) ||
   !preg_match('/^\d{4}-\d{2}-\d{2}$/',$date) ||
   !checkdate((int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4))) {
    header("Location: events.php?message=invalid");
    exit;
}

if(!isset($_FILES["poster"]) || $_FILES["poster"]["error"]!==UPLOAD_ERR_OK) {
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

$posterName=bin2hex(random_bytes(16)).".".$allowedTypes[$mime];
$posterPath=$uploadDir.$posterName;

if(!move_uploaded_file($file["tmp_name"],$posterPath)) {
    header("Location: events.php?message=upload_error");
    exit;
}

$stmt=$conn->prepare(
    "INSERT INTO events (event_name,event_date,category,location,description,poster,status)
     VALUES (?,?,?,?,?,?,?)"
);

$stmt->bind_param("sssssss",$name,$date,$category,$location,$description,$posterName,$status);

if($stmt->execute()) {
    header("Location: events.php?message=added");
    exit;
}

if(is_file($posterPath)) {
    unlink($posterPath);
}

header("Location: events.php?message=failed");
exit;
?>
