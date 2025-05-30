<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("location:index.php");
    exit(); // Ensure to stop further execution if not logged in
}
include('connection.php');

$eid = $_GET['eid'];
$approval = "Allowed";
$napproval = "Not Allowed";

$view = "SELECT * FROM `contact` WHERE id = '$eid'";
$re = mysqli_query($con, $view);
while ($row = mysqli_fetch_array($re)) {
    $id = $row['approval'];
}

if ($id == "Not Allowed") {
    $sql = "UPDATE `contact` SET `approval`= '$approval' WHERE id = '$eid' ";
    if (mysqli_query($con, $sql)) {
        echo '<script>alert("Approval Updated") </script>';
        header("Location: messages.php");
    }
} else {
    $sql = "UPDATE `contact` SET `approval`= '$napproval' WHERE id = '$eid' ";
    if (mysqli_query($con, $sql)) {
        echo '<script>alert("Disapproval Updated") </script>';
        header("Location: messages.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Message Approval</title>
    <!-- Bootstrap Styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="assets/css/custom-styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Message Approval</h2>
        <p>
            <a href="#" class="btn btn-primary" onclick="window.print()">Print Message</a>
            <a href="mailto:<?php echo $row['email']; ?>" class="btn btn-info">Email Link</a>
        </p>
        <div class="panel panel-default">
            <div class="panel-body">
                <p>Message Content: <?php echo $row['message']; ?></p>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
