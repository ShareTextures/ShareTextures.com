<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Yoast | Submit</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="new_tex.js"></script>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
<div id="page" class="center-all">

<?php

$conn = db_conn_read_write();  // Create Database connection first so we can use `mysqli_real_escape_string`

$tag_title = mysqli_real_escape_string($conn, $_POST["tag_title"]);
$tag_meta = mysqli_real_escape_string($conn, $_POST["tag_meta"]);
$single_title = mysqli_real_escape_string($conn, $_POST["single_title"]);
$single_meta = mysqli_real_escape_string($conn, $_POST["single_meta"]);

$sql = 'UPDATE yoast SET tag_title="'.$tag_title.'",tag_meta="'.$tag_meta.'",single_title="'.$single_title.'",single_meta="'.$single_meta.'" where id=1';
 
$result = mysqli_query($conn, $sql);

if ($result == 1){
    echo "<h1>Success!</h1>";
    echo ' ';



}else{
    echo "<h1>Submission Failed.</h1>";

    // Check for existing
}

?>

</div>
</div>

</body>
</html>
