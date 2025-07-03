<?php session_start(); require_once 'connect.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Beef Export' : 'Beef Export'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" alt="Beef Export Logo"></a>
                <h1><a href="index.php">Beef Export</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">หน้าแรก</a></li>
                    <li><a href="products.php">สินค้า</a></li>
                    <li><a href="contact.php">ติดต่อเรา</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? 'user') === 'user'): ?>
                    <?php
                    $cart_item_count = 0;
                    if (!empty($_SESSION['cart'])) {
                        $cart_item_count = array_sum($_SESSION['cart']);
                    }
                    ?>
                    <a href="order.php" class="btn btn-gold cart-button">
                        🛒 <span class="cart-count"><?php echo $cart_item_count; ?></span>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-secondary welcome-message">
                            ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?> ▾
                        </button>
                        <div class="dropdown-content">
                            <a href="dashboard.php">แดชบอร์ด</a>
                            <a href="order_status.php">ประวัติสั่งซื้อ</a>
                            <a href="profile.php">แก้ไขโปรไฟล์</a>
                            <a href="logout.php">ออกจากระบบ</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
                    <a href="admin_login.php" class="btn btn-secondary">สำหรับผู้ดูแล</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>