<?php
require_once __DIR__ . '/db_connect.php';
require "include/header.php";

// TODO: Check if form posted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $price = $_POST["price"];
    $stock = $_POST["stock"];
    $Picture = $_POST["picture"];

    // TODO: Insert SQL
    $sql = "INSERT INTO product (PrdName, PrdCategory, PrdPrice, PrdQtyStock, PrdImage) VALUES ('$name', '$category', '$price', '$stock', '$Picture')";
    mysqli_query($conn, $sql);

    // TODO: Redirect
    mysqli_query($conn, $sql);
    header("Location: index.php");
}
?>
<link rel="stylesheet" href="style.css">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<div class="form-box">
    <h2>Create New Product</h2>
<form method="post">
    <label>Name: </label>
    <input type="text" name="name">
    <label>Category: </label>
    <input type="text" name="category">
    <label>Price: </label>
    <input type="text" name="price">
    <label>Stock: </label>
    <input type="text" name="stock">
    <label>Image: </label>
    <input type="text" name="image">
    <button type="submit">Save</button>
</form>

<div>
    <a class="btn btn-primary" href="index.php">Back to Product List</a>
</div>

<?php require "include/footer.php"; ?>
