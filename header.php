<?php session_start(); require_once 'connect.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Beef Export' : 'Beef Export'; ?></title>
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
                    <li><a href="order_status.php">สถานะคำสั่งซื้อ</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? 'user') === 'user'): ?>
                    <?php
                    $cart_item_count = !empty($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                    ?>
                    <a href="order.php" class="cart-button-nav">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count-badge"><?php echo $cart_item_count; ?></span>
                    </a>
                    <div class="user-dropdown">
                        <button class="user-dropdown-toggle btn">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown-menu">
                            <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> แดชบอร์ด</a>
                            <a href="order_status.php"><i class="fas fa-history"></i> ประวัติสั่งซื้อ</a>
                            <a href="profile.php"><i class="fas fa-user-edit"></i> แก้ไขโปรไฟล์</a>
                            <hr class="dropdown-divider">
                            <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ/LOGIN</a>
                    <a href="admin_login.php" class="btn btn-secondary">สำหรับผู้ดูแล/ADMIN</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>