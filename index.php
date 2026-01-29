<?php
require "db_connect.php";
require "header.php";


// TODO: สร้าง SQL
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// TODO: แสดงแบบตาราง
?>

<a href="create.php">Create New</a>

<table border="1">
<tr><th>ID</th><th>Name</th><th>Action</th></tr>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= $row["id"] ?></td>
    <td><?= $row["name"] ?></td>
    <td>
        <a href="update.php?id=<?= $row["id"] ?>">Edit</a> |
        <a href="delete.php?id=<?= $row["id"] ?>">Delete</a>
    </td>
</tr>
<?php endwhile; ?>

</table>

<?php require "footer.php"; ?>
