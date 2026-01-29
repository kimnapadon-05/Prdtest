<?php

$db_host = "localhost";
$db_user = "your_username";
$db_pass = "your_password";
$db_name = "your_database";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
