<?php
include_once "db.php";
include_once "functions.php";

if (isset($_POST['submit'])) {
    editUser($connection);

    header("Location: index.php");
    die();
}

?>

<?php include "includes/header.php";?>

<form action="editAccount.php" method="post">
    <input type="text" name="email" placeholder="Enter New Email">
    <br>

    <input type="text" name="username" placeholder="Enter New Username">
    <br>

    <input type="submit" name="submit" value="Submit">

</form>