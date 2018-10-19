<?php
define('DATABASE', 'DB');
define('USER', 'root');
define('PASSWORD', 'root');
define('HOST', 'localhost');
define('DEBUGGING', true);

$connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE); // or die("ERROR: with connection");
if (DEBUGGING) {
    if ($connection) {
        echo "Connected";
    } else {
        echo "Could not connect to database";
    }
    echo "<br>";
}
