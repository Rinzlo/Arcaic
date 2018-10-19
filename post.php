<?php
include_once "db.php";
include_once "functions.php";

if (!isset($_COOKIE['username'])) {
    header("Location: login.php");
    die();
}

$parent;
if (!isset($_GET['parent'])) {
    $parent = null;
} else {
    $parent = $_GET['parent'];
}
//echo "parent: " . $parent . "<br>";

$author;
if (!isset($_GET['author'])) {
    $author = null;
} else {
    $author = $_GET['author'];
}

if (isset($_POST['submit'])) {

    post($connection, $parent);
}

?>

<?php include "includes/header.php";?>

<?php
if ($parent == null) {
    echo "Make a Post";
} else {
    echo "Comment on " . $author . "'s post id: " . $parent;
}
?>

<form action="post.php?parent=<?php echo $parent ?>" method="post">
    <input type="text" name="title" placeholder="Enter Title">
    <br>

    <textarea rows="20" cols="60" name="body">Enter Some shiiiiiiii...</textarea>

    <input type="submit" name="submit" value="Post">

</form>