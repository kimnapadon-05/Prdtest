<?php
require "config.php";

$id = $_GET["id"];

// TODO: Delete
mysqli_query($conn, "DELETE FROM users WHERE id = $id");

// TODO: Redirect
header("Location: index.php");
?>
