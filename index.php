<?php
// เชื่อมต่อฐานข้อมูล
include_once 'db_connect.php';

$index = 1; 

// สร้างตัวแปรค้นหา
$keyword = $_GET['keyword'] ?? '';
$sql = "SELECT * FROM #";

if ($keyword !== '') {
    $searchTerm = "%$keyword%";
    $sql .= " WHERE PrdName LIKE ? OR PrdCategory LIKE ? OR PrdDescription LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

$index = 1;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>รายการสินค้า</h1>
    <a href="#" class="button green">เพิ่มสินค้า</a>
    <form method="GET">
        <input type="text" name="keyword" placeholder="ค้นหาสินค้า" value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">ค้นหา</button>
        <?php if ($keyword !== ''): ?>
            <a href="#.php">เเสดงสินค้าทั้งหมด/a>
        <?php endif; ?>
    </form>
    <table border="1">
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รูปสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่สินค้า</th>
                <th>คำอธิบายสินค้า</th>
                <th>ราคาสินค้า</th>
                <th>สินค้าที่อยู่ในคลัง</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $index++ ?></td>
                    <td>
                        <?php if (!empty($row['PrdPicture'])): ?>
                            <img src="<?= htmlspecialchars($row['PrdPicture']) ?>" alt="รูปสินค้า" width="80">
                        <?php else: ?>
                            ไม่มีรูป
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="#.php?id=<?= $row['PrdID'] ?>">
                            <?= htmlspecialchars($row['PrdName']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['PrdCategory']) ?></td>
                    <td><?= htmlspecialchars($row['PrdDescription']) ?></td>
                    <td><?= $row['PrdQtyStock'] ?></td>
                    <td><?= number_format($row['PrdPrice']) ?> </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center; color:red; padding:20px;">
                    ไม่พบสินค้า
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
