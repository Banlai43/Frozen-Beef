<?php
session_start();

// *** ระบบป้องกัน: ตรวจสอบว่าล็อกอินหรือยัง และมี role เป็น admin หรือไม่ ***
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "กรุณาเข้าสู่ระบบสำหรับผู้ดูแลระบบ";
    header("Location: admin_login.php");
    exit();
}

require_once 'connect.php';
// (สามารถดึงข้อมูลสรุปต่างๆ มาแสดงผลที่นี่ได้ เช่น จำนวนออเดอร์, จำนวนผู้ใช้)
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php"><i class="fas fa-cogs"></i> Admin Panel</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            ยินดีต้อนรับ, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light" href="logout.php">ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">แผงควบคุมสำหรับผู้ดูแลระบบ</h1>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <h5 class="card-title">จัดการสินค้า</h5>
                        <p class="card-text">เพิ่ม ลบ หรือแก้ไขข้อมูลสินค้าทั้งหมด</p>
                        <a href="admin_products.php" class="btn btn-primary">ไปที่หน้าจัดการสินค้า</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                    <h5 class="card-title">จัดการคำสั่งซื้อ</h5>
                    <p class="card-text">ดูและอัปเดตสถานะของคำสั่งซื้อ</p>
                    <a href="admin_orders.php" class="btn btn-success">ไปที่หน้าจัดการคำสั่งซื้อ</a>
                </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5 class="card-title">จัดการผู้ใช้งาน</h5>
                        <p class="card-text">ดูข้อมูลและจัดการสิทธิ์ผู้ใช้งาน</p>
                        <a href="#" class="btn btn-info">ไปที่หน้าจัดการผู้ใช้</a>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>