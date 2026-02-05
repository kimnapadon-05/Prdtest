<?php
// เปิดแสดง error ชั่วคราวเพื่อ debug (ลบออกตอนสอบ)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// รวมไฟล์เชื่อม DB
include_once 'db_connect.php';

// กำหนดตัวแปรเริ่มต้นให้ครบทุกตัว (สำคัญมาก!)
$productName          = "";
$productPicture       = "";
$productCategory      = "";
$productDescription   = "";
$productPrice         = 0;
$productQuantityStock = 0;

// ตัวแปร error
$productNameErr = $productPictureErr = $productCategoryErr = $productDescriptionErr = "";
$productPriceErr = $productQuantityStockErr = "";

// ถ้ามีการ submit ฟอร์ม (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าและ trim ให้สะอาด (ใช้ ?? เพื่อป้องกัน undefined index)
    $productName          = trim($_POST['PrdName'] ?? '');
    $productPicture       = trim($_POST['PrdPicture'] ?? '');
    $productCategory      = trim($_POST['PrdCategory'] ?? '');
    $productDescription   = trim($_POST['PrdDescription'] ?? '');
    $productPrice         = floatval($_POST['PrdPrice'] ?? 0);
    $productQuantityStock = intval($_POST['PrdQtyStock'] ?? 0);

    // Validation (ส่วนนี้คุณทำดีอยู่แล้ว แต่ปรับให้ครบ spec ข้อสอบ)
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
    } elseif (!filter_var($productQuantityStock, FILTER_VALIDATE_INT) || $productQuantityStock < 0 || $productQuantityStock > 99999) {
        $productQuantityStockErr = "จำนวนต้องเป็นจำนวนเต็ม 0–9999";
    }

    // ถ้าไม่มี error → บันทึกข้อมูล
        if (empty($productNameErr) && empty($productPictureErr) && empty($productCategoryErr) &&
        empty($productDescriptionErr) && empty($productPriceErr) && empty($productQuantityStockErr)) {

            echo "<pre>ข้อมูลที่จะบันทึก:\n";
            print_r([
                'name'          => $productName,
                'picture'       => $productPicture,
                'category'      => $productCategory,
                'description'   => $productDescription,
                'price'         => $productPrice,
                'qty_stock'     => $productQuantityStock
            ]);
            echo "</pre>";
            $stmt = $conn->prepare("
            INSERT INTO Product (PrdName, PrdPicture, PrdCategory, PrdDescription, PrdPrice, PrdQtyStock) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("ssssii", $productName, $productPicture, $productCategory, $productDescription, $productPrice, $productQuantityStock);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Execute ล้มเหลว: " . $stmt->error;
        }

        $stmt->close();
    }
    // ถ้ามี error → ค่าเดิมจะยังอยู่ (เพราะ POST แล้ว set ตัวแปรใหม่) → ฟอร์มจะแสดงค่าเก่า + error
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add_product</title>

    <!-- ไฟล์ CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
        <form action="create.php" method="POST">
            <h2>เพิ่มสินค้าใหม่</h2>

            <label>ชื่อสินค้า:</label><br>
            <input type="text" name="PrdName" value="<?php echo htmlspecialchars($productName); ?>">
            <span style="color: red;"><?php echo $productNameErr; ?></span><br><br>

            <label>รูปภาพสินค้า (URL):</label><br>
            <input type="text" name="PrdPicture" value="<?php echo htmlspecialchars($productPicture); ?>">
            <span style="color: red;"><?php echo $productPictureErr; ?></span><br><br>

            <label>ประเภทสินค้า:</label><br>
            <input type="text" name="PrdCategory" value="<?php echo htmlspecialchars($productCategory); ?>">
            <span style="color: red;"><?php echo $productCategoryErr; ?></span><br><br>

            <label>รายละเอียดสินค้า:</label><br>
            <textarea name="PrdDescription"><?php echo htmlspecialchars($productDescription); ?></textarea>
            <span style="color: red;"><?php echo $productDescriptionErr; ?></span><br><br>

            <label>ราคาสินค้า:</label><br>
            <input type="number" name="PrdPrice" value="<?php echo htmlspecialchars($productPrice); ?>">
            <span style="color: red;"><?php echo $productPriceErr; ?></span><br><br>

            <label>จำนวนสินค้า:</label><br>
            <input type="number" name="PrdQtyStock" value="<?php echo htmlspecialchars($productQuantityStock); ?>">
            <span style="color: red;"><?php echo $productQuantityStockErr; ?></span><br><br>

            <div class="flex">
                <a href="index.php" class="button red">ยกเลิก</a>
                <input type="submit" value="เพิ่มสินค้า" class="button green">
            </div>
        </form>
    </div>
    </form>
</body>
</html>