<?php

setcookie('username', $_COOKIE['username'], time() - (86400 * 30), "/");

header("Location: index.php");
die();

?>