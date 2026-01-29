<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CRUD App</title>
</head>
<body>
    <h1>CRUD Application</h1>
    <hr>
<link rel="stylesheet" href="style.css">

