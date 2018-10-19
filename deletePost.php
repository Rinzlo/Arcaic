<?php
include_once "db.php";
include_once "functions.php";

deletePost($connection, $_GET['id']);
$parent = $_GET['parent'];
header("Location: index.php?parent=$parent");
die();
?>