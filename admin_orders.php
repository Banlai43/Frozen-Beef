<?php
session_start();
// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการคำสั่งซื้อ - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php include 'admin_navbar.php'; // เรียกใช้ Navbar ของแอดมิน ?>

    <div class="container mt-4">
        <h1 class="mb-4">รายการคำสั่งซื้อทั้งหมด</h1>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>ชื่อลูกค้า</th>
                    <th>วันที่สั่งซื้อ</th>
                    <th>ยอดรวม (บาท)</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ดึงข้อมูลออเดอร์ทั้งหมด เรียงจากล่าสุดไปเก่าสุด
                $sql = "SELECT * FROM orders ORDER BY created_at DESC";
                $result = $connect->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['created_at'])); ?></td>
                        <td><?php echo number_format($row['total_price'], 2); ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['order_status']); ?></span></td>
                        <td>
                            <a href="admin_order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> ดูรายละเอียด
                            </a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">ยังไม่มีคำสั่งซื้อเข้ามา</td></tr>';
                }
                $connect->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>