
<?php

require "db.php";


if(isset($_POST["add_student"])) {
    $name=trim($_POST["name"] ?? "");
    $email=trim($_POST["email"] ?? "");
    $course=trim($_POST["course"] ?? "");
    $year=(int)($_POST["year"] ?? 0);

    $stmt=$conn->prepare(
        "INSERT INTO students (name,email,course,year) VALUES (?,?,?,?)"
    );
    $stmt->bind_param("sssi",$name,$email,$course,$year);

    if($stmt->execute()) {
        header("Location: students.php?message=added");
        exit;
    } else {
        echo "Unable to add student: ".htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

$search=trim($_GET["search"] ?? "");
$course=trim($_GET["course"] ?? "");

$limit=5;
$page=max(1,(int)($_GET["page"] ?? 1));
$offset=($page-1)*$limit;

$sql="SELECT COUNT(*) AS total FROM students WHERE 1=1";
$params=[];
$types="";

if($search!=="") {
    $sql.=" AND (name LIKE ? OR email LIKE ?)";
    $value="%".$search."%";
    $params[]=$value;
    $params[]=$value;
    $types.="ss";
}

if($course!=="") {
    $sql.=" AND course=?";
    $params[]=$course;
    $types.="s";
}

$countStmt=$conn->prepare($sql);

if(!empty($params)) {
    $countStmt->bind_param($types,...$params);
}

$countStmt->execute();
$total=(int)$countStmt->get_result()->fetch_assoc()["total"];
$totalPages=max(1,(int)ceil($total/$limit));
$countStmt->close();

$sql=str_replace("COUNT(*) AS total","*", $sql);
$sql.=" ORDER BY student_iid DESC LIMIT ? OFFSET ?";

$params[]=$limit;
$params[]=$offset;
$types.="ii";

$stmt=$conn->prepare($sql);
$stmt->bind_param($types,...$params);
$stmt->execute();
$result=$stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link rel="stylesheet" href="students.css">
</head>
<body>

    <h1>Student Management System</h1>

    
    <?php
    if(isset($_GET["message"])) {
        $messages=[
            "added"=>"Student added successfully!",
            "updated"=>"Student updated successfully!",
            "deleted"=>"Student deleted successfully!",
            "invalid"=>"Invalid student ID."
        ];

        $message=$_GET["message"];

        if(isset($messages[$message])) {
            echo "<div id='successMessage'>"
                .htmlspecialchars($messages[$message])
                ."</div>";
        }
    }
    ?>

    <style>
    #successMessage {
        position:fixed;
        top:20px;
        right:20px;
        background:#d4edda;
        color:#155724;
        padding:14px 22px;
        border-radius:8px;
        box-shadow:0 3px 10px rgba(0,0,0,0.15);
        z-index:1000;
        animation:hideMessage 0.5s ease 1.5s forwards;
    }

    @keyframes hideMessage {
        to {
            opacity:0;
            visibility:hidden;
        }
    }
    </style>

    <form method="GET" action="students.php">

        <input type="text" name="search"
               placeholder="Search name or email"
               value="<?php echo htmlspecialchars($search); ?>">

        <select name="course">
            <option value="">All Courses</option>
            <option value="B.Tech" <?php if($course==="B.Tech") echo "selected"; ?>>B.Tech</option>
            <option value="BCA" <?php if($course==="BCA") echo "selected"; ?>>BCA</option>
            <option value="MCA" <?php if($course==="MCA") echo "selected"; ?>>MCA</option>
            <option value="BBA" <?php if($course==="BBA") echo "selected"; ?>>BBA</option>
        </select>

        <button type="submit">Search / Filter</button>
        <a href="students.php">Reset</a>

    </form>

<h2>Add New Student</h2>
<form method="POST" action="students.php">
    <input type="text" name="name" placeholder="Student Name" required>
    <input type="email" name="email" placeholder="Email Address" required>

    <select name="course" required>
        <option value="">Select Course</option>
        <option value="B.Tech">B.Tech</option>
        <option value="BCA">BCA</option>
        <option value="MCA">MCA</option>
        <option value="BBA">BBA</option>
    </select>

    <select name="year" required>
        <option value="">Select Year</option>
        <option value="1">1st Year</option>
        <option value="2">2nd Year</option>
        <option value="3">3rd Year</option>
        <option value="4">4th Year</option>
    </select>

    <button type="submit" name="add_student">Add Student</button>
</form>

    <h2>Student Records</h2>
    <a href="export_students.php">Export Student List to CSV</a>
<br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Year</th>
            <th>Action</th>
        </tr>

        
        <?php while($student=$result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $student["student_iid"]; ?></td>
            <td><?php echo htmlspecialchars($student["name"]); ?></td>
            <td><?php echo htmlspecialchars($student["email"]); ?></td>
            <td><?php echo htmlspecialchars($student["course"]); ?></td>
            <td><?php echo htmlspecialchars((string)$student["year"]); ?></td>
            <td>
                <a href="edit_student.php?id=<?php echo $student['student_iid']; ?>">Edit</a>

                <form method="POST" action="delete_student.php"
                    onsubmit="return confirm('Are you sure you want to delete this student?');"
                    style="display:inline;">
                    <input type="hidden" name="student_iid"
                        value="<?php echo $student['student_iid']; ?>">
                    <button type="submit" name="delete_student">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>


    </table>

   <p>Total matching records: <?php echo $total; ?></p>
    <p>Page <?php echo $page; ?> of <?php echo $totalPages; ?></p>

    <div>
    <?php if($page>1) { ?>
        <a href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&course=<?php echo urlencode($course); ?>">Previous</a>
    <?php } ?>

    <?php for($i=1;$i<=$totalPages;$i++) { ?>
        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&course=<?php echo urlencode($course); ?>"
           style="margin:5px;<?php if($i===$page) echo 'font-weight:bold;'; ?>">
            <?php echo $i; ?>
        </a>
    <?php } ?>

    <?php if($page<$totalPages) { ?>
        <a href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&course=<?php echo urlencode($course); ?>">Next</a>
    <?php } ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
