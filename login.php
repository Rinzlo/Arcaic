<?php
include_once "db.php";
include_once "functions.php";

if (isset($_POST['submit'])) {

    login($connection);
}

?>

<?php include "includes/header.php";?>

<form action="login.php" method="post">
    <input type="text" name="email" placeholder="Enter Email">
    <br>

    <input type="password" name="password" placeholder="Enter Password">

    <input type="submit" name="submit" value="Submit">

</form>