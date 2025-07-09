<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
require_once 'connect.php';

// ดึงข้อมูลสรุป
$result_revenue = $connect->query("SELECT SUM(total_price) as total_revenue FROM orders WHERE order_status = 'Completed'");
$total_revenue = $result_revenue->fetch_assoc()['total_revenue'] ?? 0;
$result_orders = $connect->query("SELECT COUNT(id) as total_orders FROM orders");
$total_orders = $result_orders->fetch_assoc()['total_orders'] ?? 0;
$result_users = $connect->query("SELECT COUNT(id) as total_users FROM users WHERE role = 'user'");
$total_users = $result_users->fetch_assoc()['total_users'] ?? 0;
$latest_orders_stmt = $connect->query("SELECT id, customer_name, total_price, order_status, created_at FROM orders ORDER BY created_at DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>
        
        <div class="admin-main-content">
            <nav class="admin-navbar navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <h2 class="m-0 fs-4">แผงควบคุม</h2>
                    <div class="ms-auto">
                        <span class="navbar-text me-3">
                            Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                        <a class="btn btn-outline-danger" href="logout.php">ออกจากระบบ</a>
                    </div>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card stat-card border-left-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase text-success">ยอดขายรวม</div>
                                        <div class="h4 mb-0 text-gray-800">฿<?php echo number_format($total_revenue, 2); ?></div>
                                    </div>
                                    <div class="icon-circle bg-success"><i class="fas fa-dollar-sign"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card stat-card border-left-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase text-info">คำสั่งซื้อ</div>
                                        <div class="h4 mb-0 text-gray-800"><?php echo number_format($total_orders); ?></div>
                                    </div>
                                    <div class="icon-circle bg-info"><i class="fas fa-file-invoice-dollar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card stat-card border-left-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-uppercase text-warning">ลูกค้าทั้งหมด</div>
                                        <div class="h4 mb-0 text-gray-800"><?php echo number_format($total_users); ?></div>
                                    </div>
                                    <div class="icon-circle bg-warning"><i class="fas fa-users"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">5 คำสั่งซื้อล่าสุด</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#ID</th><th>ชื่อลูกค้า</th><th>ยอดรวม</th><th>สถานะ</th><th>วันที่สั่ง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = $latest_orders_stmt->fetch_assoc()): ?>
                                    <tr>
                                        <td><a href="admin_order_details.php?id=<?php echo $order['id']; ?>">#<?php echo $order['id']; ?></a></td>
                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                        <td>฿<?php echo number_format($order['total_price'], 2); ?></td>
                                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($order['order_status']); ?></span></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>