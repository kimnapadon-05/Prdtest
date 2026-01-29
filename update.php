<?php
require "db_connect.php";
require_once __DIR__ . '/db_connect.php';

$id = $_GET["id"];
if (!isset($id)) {
    die("ID missing");
}

// TODO: Get existing
$id = $_GET["id"];
$res = mysqli_query($conn, "SELECT * FROM product WHERE id = $id");
$user = mysqli_fetch_assoc($res);

// TODO: On post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $stock = $_POST["stock"];
    mysqli_query($conn, "UPDATE product SET PrdName = '$name', PrdCategory = '$category', PrdPrice = '$price', PrdQtyStock = '$stock' WHERE id = $id");
    header("Location: index.php");
}
?>

<form method="post">
    Name : <label>Name</label>
    <input type="text" name="name">
    Category : <label>Category</label>
    <input type="text" name="category">
    Price : <label>Price</label>
    <input type="text" name="price">
    Stock : <label>Stock</label>
    <input type="text" name="stock">
    <button type="submit">Save</button>
</form>

<?php require "include/footer.php"; ?>
