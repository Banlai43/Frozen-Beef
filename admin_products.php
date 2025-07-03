<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: admin_login.php"); exit(); }
require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>จัดการสินค้า</h1>
            <a href="admin_product_form.php" class="btn btn-success"><i class="fas fa-plus"></i> เพิ่มสินค้าใหม่</a>
        </div>
        
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>รูปภาพ</th>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $connect->query("SELECT * FROM products ORDER BY id DESC");
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="" width="60" height="60" style="object-fit: cover;"></td>
                        <td><?php echo htmlspecialchars($row['product_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <a href="admin_product_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> แก้ไข</a>
                            <a href="admin_product_process.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?');"><i class="fas fa-trash"></i> ลบ</a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">ไม่มีสินค้า</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>