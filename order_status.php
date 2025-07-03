<?php
session_start();
require_once 'connect.php';

// ถ้ายังไม่ล็อกอิน ให้ส่งไปหน้า login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "กรุณาเข้าสู่ระบบเพื่อดูสถานะคำสั่งซื้อ";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ - Beef Export</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .order-table { width: 100%; border-collapse: collapse; margin: 25px 0; font-size: 0.9em; }
        .order-table th, .order-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .order-table th { background-color: #f8f9fa; font-weight: bold; }
        .order-table tr:nth-child(even){ background-color: #f2f2f2; }
        .no-orders { text-align: center; color: #777; padding: 20px; }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
             <div class="logo">
                <img src="images/logo.png" alt="Prime Beef Export Logo">
                <h1>Beef Export</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">หน้าแรก</a></li>
                    <li><a href="products.php">สินค้า</a></li>
                    <li><a href="contact.php">ติดต่อเรา</a></li>
                    <li><a href="order_status.php" class="active">สถานะคำสั่งซื้อ</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                 <?php if (isset($_SESSION['user_id'])): 
                    $cart_item_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                ?>
                    <a href="order.php" class="btn btn-gold cart-button">🛒<span class="cart-count"><?php echo $cart_item_count; ?></span></a>
                    <span class="welcome-message">ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="btn btn-secondary">ออกจากระบบ</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">เข้าสู่ระบบลูกค้า</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <div class="container" style="padding: 50px 0;">
            <h2>ประวัติและสถานะคำสั่งซื้อ</h2>

            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success" style="color: green; border: 1px solid green; padding: 15px; margin-bottom: 20px; background-color: #d4edda;">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <table class="order-table">
                <thead>
                    <tr>
                        <th>รหัสคำสั่งซื้อ</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>ยอดรวม</th>
                        <th>สถานะ</th>
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
                    ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo date("d F Y, H:i", strtotime($row['created_at'])); ?></td>
                            <td>฿<?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="4" class="no-orders">คุณยังไม่มีประวัติการสั่งซื้อ</td></tr>';
                    }
                    $stmt->close();
                    $connect->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>© Beef Export MSU</p>
        </div>
    </footer>
</body>
</html>