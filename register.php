<?php
include_once "db.php";
include_once "functions.php";

if (isset($_POST['submit'])) {

    registerUser($connection);

}

?>

<?php include "includes/header.php";?>

<form action="register.php" method="post">
    <input type="text" name="email" placeholder="Enter Email">
    <br>

    <input type="text" name="firstname" placeholder="Enter FirstName">
    <br>

    <input type="text" name="lastname" placeholder="Enter LastName">
    <br>

    <input type="text" name="username" placeholder="Enter Username">
    <br>

    <input type="password" name="password" placeholder="Enter Password">

    <input type="submit" name="submit" value="Register">

</form>
