<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: admin_login.php"); exit(); }
require_once 'connect.php';

$product = ['id' => '', 'name' => '', 'description' => '', 'price' => '', 'image_url' => '', 'product_code' => '', 'product_type' => ''];
$form_action = 'add';
$page_title = 'เพิ่มสินค้าใหม่';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $connect->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $form_action = 'edit';
        $page_title = 'แก้ไขสินค้า: ' . htmlspecialchars($product['name']);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>
    <div class="container mt-4 mb-5">
        <h1 class="mb-4"><?php echo $page_title; ?></h1>
        <form action="admin_product_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            
            <div class="mb-3"><label class="form-label">ชื่อสินค้า:</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required></div>
            <div class="mb-3"><label class="form-label">รหัสสินค้า:</label><input type="text" name="product_code" class="form-control" value="<?php echo htmlspecialchars($product['product_code']); ?>"></div>
            <div class="mb-3"><label class="form-label">ประเภทสินค้า (เช่น ริบอาย, สันใน):</label><input type="text" name="product_type" class="form-control" value="<?php echo htmlspecialchars($product['product_type']); ?>"></div>
            <div class="mb-3"><label class="form-label">ราคา (ต่อ กก.):</label><input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required></div>
            <div class="mb-3"><label class="form-label">คำอธิบาย:</label><textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea></div>
            <div class="mb-3"><label class="form-label">รูปภาพ (หากไม่ต้องการเปลี่ยน ไม่ต้องอัปโหลดใหม่):</label><input type="file" name="image" class="form-control">
                <?php if ($product['image_url']): ?>
                    <p class="mt-2">รูปภาพปัจจุบัน:</p>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" width="100" class="mt-1">
                    <input type="hidden" name="current_image" value="<?php echo $product['image_url']; ?>">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
            <a href="admin_products.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
</body>
</html>