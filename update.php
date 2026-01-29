<?php
require "config.php";
require "partials/header.php";

$id = $_GET["id"];

// TODO: Get existing
$res = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($res);

// TODO: On post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    mysqli_query($conn, "UPDATE users SET name = '$name' WHERE id = $id");
    header("Location: index.php");
}

?>

<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?= $user["name"] ?>">
    <button type="submit">Update</button>
</form>

<?php require "partials/footer.php"; ?>
