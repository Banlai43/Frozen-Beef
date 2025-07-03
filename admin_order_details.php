<?php
session_start();
// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
require_once 'connect.php';

// รับ ID ของออเดอร์จาก URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id === 0) {
    header("Location: admin_orders.php");
    exit();
}

// อัปเดตสถานะถ้ามีการส่งฟอร์มมา
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['new_status'];
    $update_stmt = $connect->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    $update_stmt->close();
    // ตั้งค่าข้อความแจ้งเตือน
    $_SESSION['success_message'] = "อัปเดตสถานะออเดอร์ #$order_id เรียบร้อยแล้ว";
}


// ดึงข้อมูลออเดอร์หลัก
$stmt = $connect->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: admin_orders.php");
    exit();
}

// ดึงรายการสินค้าในออเดอร์
$items_stmt = $connect->prepare(
    "SELECT oi.quantity, oi.price_per_unit, p.name as product_name, p.product_code 
     FROM order_items oi 
     JOIN products p ON oi.product_id = p.id 
     WHERE oi.order_id = ?"
);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$order_items = $items_stmt->get_result();
$items_stmt->close();

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php include 'admin_navbar.php'; ?>

    <div class="container mt-4">
        <a href="admin_orders.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> กลับไปหน้ารายการ</a>
        <h1 class="mb-4">รายละเอียดคำสั่งซื้อ #<?php echo $order['id']; ?></h1>

        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <h4>รายการสินค้า</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>ราคารวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($item = $order_items->fetch_assoc()): 
                            $subtotal = $item['quantity'] * $item['price_per_unit'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_code']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price_per_unit'], 2); ?></td>
                            <td><?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">ยอดรวมทั้งหมด:</td>
                            <td><?php echo number_format($order['total_price'], 2); ?> บาท</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">ข้อมูลลูกค้าและสถานะ</div>
                    <div class="card-body">
                <p><strong>ชื่อ-นามสกุล:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                <p><strong>ที่อยู่จัดส่ง:</strong><br><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
    
        <?php
        // แปลงค่า payment_method เป็นข้อความภาษาไทย
        $payment_text = 'ไม่ระบุ';
        if ($order['payment_method'] === 'bank_transfer') {
            $payment_text = 'โอนเงินผ่านธนาคาร';
        } elseif ($order['payment_method'] === 'cash_on_delivery') {
            $payment_text = 'เก็บเงินปลายทาง';
        }
        ?>
    <p><strong>ช่องทางชำระเงิน:</strong> <?php echo $payment_text; ?></p>
    <hr>
                        <form action="admin_order_details.php?id=<?php echo $order_id; ?>" method="POST">
                            <div class="mb-3">
                                <label for="new_status" class="form-label"><strong>อัปเดตสถานะคำสั่งซื้อ:</strong></label>
                                <select name="new_status" id="new_status" class="form-select">
                                    <option value="Pending" <?php if($order['order_status'] == 'Pending') echo 'selected'; ?>>รอดำเนินการ</option>
                                    <option value="Processing" <?php if($order['order_status'] == 'Processing') echo 'selected'; ?>>กำลังเตรียมของ</option>
                                    <option value="Shipped" <?php if($order['order_status'] == 'Shipped') echo 'selected'; ?>>จัดส่งแล้ว</option>
                                    <option value="Completed" <?php if($order['order_status'] == 'Completed') echo 'selected'; ?>>สำเร็จ</option>
                                    <option value="Cancelled" <?php if($order['order_status'] == 'Cancelled') echo 'selected'; ?>>ยกเลิก</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary w-100">อัปเดตสถานะ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>