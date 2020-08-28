<?php
include 'conn.php';
$id = intval($_GET["id"]);
$deleteQuery = "delete from tbl_students where id='$id'";
$result = mysqli_query($link,$deleteQuery);
header("location:insert.php");
exit();