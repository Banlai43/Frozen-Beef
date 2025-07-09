<?php
$page_title = "สถานะคำสั่งซื้อ";
include 'header.php'; // เรียกใช้ Header กลาง

// ตรวจสอบว่ามีการล็อกอินหรือยัง ถ้ายังให้กลับไปหน้า login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "กรุณาเข้าสู่ระบบเพื่อดูสถานะคำสั่งซื้อ";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<main>
    <div class="container" style="padding-top: 40px; padding-bottom: 60px;">
        <h2 class="text-center mb-4">ประวัติการสั่งซื้อ</h2>
        
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>รหัสคำสั่งซื้อ</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th class="text-end">ยอดรวม</th>
                                <th class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $connect->prepare("SELECT id, created_at, total_price, order_status FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $status_class = 'bg-secondary'; // Default
                                    if ($row['order_status'] === 'Completed') {
                                        $status_class = 'bg-success';
                                    } elseif ($row['order_status'] === 'Processing' || $row['order_status'] === 'Shipped') {
                                        $status_class = 'bg-primary';
                                    } elseif ($row['order_status'] === 'Cancelled') {
                                        $status_class = 'bg-danger';
                                    }
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo date("d F Y, H:i", strtotime($row['created_at'])); ?></td>
                                    <td class="text-end">฿<?php echo number_format($row['total_price'], 2); ?></td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($row['order_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center p-5">คุณยังไม่มีประวัติการสั่งซื้อ</td></tr>';
                            }
                            $stmt->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php';  ?>