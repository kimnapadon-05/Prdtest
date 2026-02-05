<?php
$servername = "localhost";
$isername = "root";
$pasword = "";
$dbname = "";

$conn= new mysqli($servername, $isername, $pasword, $dbname);

if ($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);

}
?>
