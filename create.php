<?php
require "config.php";
require "partials/header.php";

// TODO: Check if form posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    
    // TODO: Insert SQL
    $sql = "INSERT INTO users (name) VALUES ('$name')";
    mysqli_query($conn, $sql);

    // TODO: Redirect
    header("Location: index.php");
}
?>

<form method="post">
    <label>Name</label>
    <input type="text" name="name">
    <button type="submit">Save</button>
</form>

<?php require "partials/footer.php"; ?>
