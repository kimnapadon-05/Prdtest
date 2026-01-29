<?php
require_once __DIR__ . '/db_connect.php';

if(!isset($_GET["PrdID"])){
    die("ID missing");
}

$id = $_GET["PrdID"];
// TODO: Delete
mysqli_query($conn, "DELETE FROM users WHERE id = $id");

// TODO: Redirect
header("Location: index.php");
?>
