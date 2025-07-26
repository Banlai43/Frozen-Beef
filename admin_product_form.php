<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: admin_login.php"); exit(); }
require_once 'connect.php';

// --- Logic to get product data for editing ---
$product = ['id' => '', 'name' => '', 'description' => '', 'price' => '', 'image_url' => '', 'product_code' => '', 'product_type' => ''];
$form_action = 'add';
$page_title = 'เพิ่มสินค้าใหม่'; // Default page title

if (isset($_GET['id']) && !empty($_GET['id'])) {
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
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-page-products"> <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>

        <div class="admin-main-content">
            <?php include 'admin_navbar.php'; ?>

            <div class="p-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary"><?php echo $page_title; ?></h6>
                    </div>
                    <div class="card-body">
                        <form action="admin_product_process.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="<?php echo $form_action; ?>">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">ชื่อสินค้า:</label>
                                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="product_code" class="form-label">รหัสสินค้า:</label>
                                            <input type="text" id="product_code" name="product_code" class="form-control" value="<?php echo htmlspecialchars($product['product_code']); ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="product_type" class="form-label">ประเภทสินค้า (เช่น ริบอาย, สันใน):</label>
                                            <input type="text" id="product_type" name="product_type" class="form-control" value="<?php echo htmlspecialchars($product['product_type']); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">ราคา (ต่อ กก.):</label>
                                        <input type="number" step="0.01" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">คำอธิบาย:</label>
                                        <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">รูปภาพสินค้า</label>
                                        <p class="form-text text-muted small">หากไม่ต้องการเปลี่ยน ไม่ต้องอัปโหลดใหม่</p>
                                        <input type="file" name="image" id="image" class="form-control">
                                        <?php if (!empty($product['image_url'])): ?>
                                            <div class="mt-3">
                                                <p>รูปภาพปัจจุบัน:</p>
                                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Current Product Image" class="img-fluid rounded" style="max-height: 150px;">
                                                <input type="hidden" name="current_image" value="<?php echo $product['image_url']; ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกข้อมูล</button>
                            <a href="admin_products.php" class="btn btn-secondary">ยกเลิก</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>