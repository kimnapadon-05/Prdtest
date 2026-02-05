<?php
// เปิด debug ชั่วคราว (ลบออกตอนสอบ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'db_connect.php';

// ตรวจสอบว่ามี id ส่งมาหรือไม่
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: .php");
    exit();
}

$id = intval($_GET['id']);

// กำหนดตัวแปรเริ่มต้น
$productName          = "";
$productPicture       = "";
$productCategory      = "";
$productDescription   = "";
$productPrice         = 0;
$productQuantityStock = 0;

// error message
$productNameErr = $productPictureErr = $productCategoryErr = $productDescriptionErr = "";
$productPriceErr = $productQuantityStockErr = "";

// ดึงข้อมูลสินค้าตาม id
$sql = "SELECT * FROM  WHERE PrdID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();

// เติมค่าเริ่มต้นจากฐานข้อมูล
$productName          = $row['PrdName'];
$productPicture       = $row['PrdPicture'];
$productCategory      = $row['PrdCategory'];
$productDescription   = $row['PrdDescription'];
$productPrice         = $row['PrdPrice'];
$productQuantityStock = $row['PrdQtyStock'];

// ประมวลผลเมื่อ submit (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับค่าใหม่จากฟอร์ม
    $productName          = trim($_POST['PrdName'] ?? '');
    $productPicture       = trim($_POST['PrdPicture'] ?? '');
    $productCategory      = trim($_POST['PrdCategory'] ?? '');
    $productDescription   = trim($_POST['PrdDescription'] ?? '');
    $productPrice         = trim($_POST['PrdPrice'] ?? '');
    $productQuantityStock = trim($_POST['PrdQtyStock'] ?? '');

    // Validation (เหมือนหน้า add)
    if (empty($productName)) {
        $productNameErr = "กรุณากรอกชื่อสินค้า";
    } elseif (strlen($productName) > 50) {
        $productNameErr = "ชื่อสินค้าต้องไม่เกิน 50 ตัวอักษร";
    }

    if (empty($productPicture)) {
        $productPictureErr = "กรุณากรอก URL รูปภาพสินค้า";
    } elseif (strlen($productPicture) > 100) {
        $productPictureErr = "URL รูปภาพต้องไม่เกิน 100 ตัวอักษร";
    } elseif (!filter_var($productPicture, FILTER_VALIDATE_URL)) {
        $productPictureErr = "รูปแบบ URL ไม่ถูกต้อง";
    }

    if (empty($productCategory)) {
        $productCategoryErr = "กรุณากรอกประเภทสินค้า";
    } elseif (strlen($productCategory) > 50) {
        $productCategoryErr = "ประเภทสินค้าต้องไม่เกิน 50 ตัวอักษร";
    }

    if (empty($productDescription)) {
        $productDescriptionErr = "กรุณากรอกรายละเอียดสินค้า";
    } elseif (strlen($productDescription) > 250) {
        $productDescriptionErr = "รายละเอียดต้องไม่เกิน 250 ตัวอักษร";
    }

    if (empty($productPrice)) {
        $productPriceErr = "กรุณากรอกราคาสินค้า";
    } elseif (!filter_var($productPrice, FILTER_VALIDATE_INT) || $productPrice < 0 || $productPrice > 9999) {
        $productPriceErr = "ราคาต้องเป็นจำนวนเต็ม 0–9999";
    }

    if (empty($productQuantityStock)) {
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้า";
    } 

    // ถ้าไม่มี error → ตรวจสอบว่ากด Save หรือ Delete
    if (empty($productNameErr) && empty($productPictureErr) && empty($productCategoryErr) &&
        empty($productDescriptionErr) && empty($productPriceErr) && empty($productQuantityStockErr)) {

        if (isset($_POST['save'])) {
            // อัปเดตข้อมูล
            $updateStmt = $conn->prepare("
                UPDATE  SET 
                    PrdName = ?, PrdPicture = ?, PrdCategory = ?, 
                    PrdDescription = ?, PrdPrice = ?, PrdQtyStock = ? 
                WHERE PrdID = ?
            ");
            $updateStmt->bind_param("ssssiii", $productName, $productPicture, $productCategory, 
                                    $productDescription, $productPrice, $productQuantityStock, $id);

            if ($updateStmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "อัปเดตไม่ได้: " . $updateStmt->error;
            }
            $updateStmt->close();
        } 
        elseif (isset($_POST['delete'])) {
            // ลบข้อมูล
            $deleteStmt = $conn->prepare("DELETE FROM  WHERE PrdID = ?");
            $deleteStmt->bind_param("i", $id);

            if ($deleteStmt->execute()) {
                header("Location: ");
                exit();
            } else {
                echo "ลบไม่ได้: " . $deleteStmt->error;
            }
            $deleteStmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>แก้ไขสินค้า: <?= htmlspecialchars($productName) ?></h1>

<form action="#.php?id=<?= $id ?>" method="POST">

    <label>ชื่อสินค้า *</label><br>
    <input type="text" name="PrdName" value="<?= htmlspecialchars($productName) ?>">
    <span style="color: red;"><?= $productNameErr ?></span><br><br>

    <label>รูปภาพสินค้า (URL) *</label><br>
    <input type="text" name="PrdPicture" value="<?= htmlspecialchars($productPicture) ?>">
    <span style="color: red;"><?= $productPictureErr ?></span><br><br>

    <label>ประเภทสินค้า *</label><br>
    <input type="text" name="PrdCategory" value="<?= htmlspecialchars($productCategory) ?>">
    <span style="color: red;"><?= $productCategoryErr ?></span><br><br>

    <label>รายละเอียดสินค้า *</label><br>
    <textarea name="PrdDescription"><?= htmlspecialchars($productDescription) ?></textarea>
    <span style="color: red;"><?= $productDescriptionErr ?></span><br><br>

    <label>ราคาสินค้า *</label><br>
    <input type="number" name="PrdPrice" value="<?= htmlspecialchars($productPrice) ?>">
    <span style="color: red;"><?= $productPriceErr ?></span><br><br>

    <label>จำนวนสินค้า *</label><br>
    <input type="number" name="PrdQtyStock" value="<?= htmlspecialchars($productQuantityStock) ?>">
    <span style="color: red;"><?= $productQuantityStockErr ?></span><br><br>

    <div class="flex">
        <input type="submit" name="delete" value="ลบสินค้า" class="button red">
        <input type="submit" name="save" value="บันทึก" class="button green">
        <a href="index.php" class="button gray">ยกเลิก</a>
    </div>

</form>

</body>
</html>