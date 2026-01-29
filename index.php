<?php
require_once __DIR__ . '/db_connect.php';
require "include/header.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// TODO: สร้าง SQL
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

// TODO: แสดงแบบตาราง
?>
<link rel="stylesheet" href=style.css>
<div class="container">
    <div class="header">
        <h2>Product</h2>
        <a class="btn btn-primary" href="create.php">Create New Product</a>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Search">
        <button class="btn btn-serch">Search</button>
        <button class="btn btn-reset">Reset</button>
    </div>

    <table class="table">
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Picture</th>
        <th>Category</th>
        <th>Description</th>
        <th>Price</th>
        <th>Quantity Stock</th>
        <th>Action</th>
    </tr>

<?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <tr>
    <td><?= $row['PrdID'] ?></td>
    <td><?= $row['PrdName'] ?></td>
    <td><?= $row['PrdPicture'] ?></td>
    <td><?= $row['PrdCategory'] ?></td>
    <td><?= $row['PrdDescription'] ?></td>
    <td><?= $row['PrdPrice'] ?></td>
    <td><?= $row['PrdQtyStock'] ?></td>
    <td>
        <a href="update.php?id=<?= $row['PrdID'] ?>">Edit</a> |
        <a href="delete.php?id=<?= $row['PrdID'] ?>"
           onclick="return confirm('Delete?')">Delete</a>
    </td>
</tr>

<?php endwhile; ?>
</table>

</div>
<?php require "include/footer.php"; ?>
