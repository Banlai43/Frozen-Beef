<?php
$page_title = "สถานะคำสั่งซื้อ";
include 'header.php';

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
        
        <?php if(isset($_SESSION['upload_success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['upload_success']; unset($_SESSION['upload_success']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['upload_error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['upload_error']; unset($_SESSION['upload_error']); ?></div>
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
                                <th>หลักฐานการชำระเงิน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $connect->prepare("SELECT id, created_at, total_price, order_status, payment_method, payment_slip FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                                    <td class="text-end">฿<?php echo number_format($row['total_price'], 2); ?></td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-info text-dark"><?php echo htmlspecialchars($row['order_status']); ?></span>
                                    </td>
                                    <td>
                                        <?php if ($row['payment_method'] === 'bank_transfer'): ?>
                                            <?php if (empty($row['payment_slip'])): ?>
                                                <form action="upload_slip.php" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                    <div class="input-group">
                                                        <input type="file" name="slip_image" class="form-control form-control-sm" required>
                                                        <button type="submit" class="btn btn-sm btn-primary">อัปโหลด</button>
                                                    </div>
                                                </form>
                                            <?php else: ?>
                                                <a href="<?php echo htmlspecialchars($row['payment_slip']); ?>" target="_blank" class="btn btn-sm btn-success">ดูสลิป</a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="5" class="text-center p-5">คุณยังไม่มีประวัติการสั่งซื้อ</td></tr>';
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
<?php include 'footer.php'; ?>