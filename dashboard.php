<?php
session_start();

// ตรวจสอบว่ามีการล็อกอินหรือยัง ถ้ายังให้กลับไปหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าสมาชิก - Beef Export</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .dashboard-main {
            padding: 60px 0;
            background-color: #f9f9f9;
        }
        .dashboard-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .dashboard-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .dashboard-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .dashboard-card .icon {
            font-size: 3rem;
            color: #cdaa7c; /* สีทองเดียวกับปุ่ม */
            margin-bottom: 20px;
        }
        .dashboard-card h4 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .dashboard-card a {
            text-decoration: none;
            color: #333;
        }
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
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php
        // --- เพิ่มโค้ดคำนวณจำนวนสินค้าทั้งหมดในตะกร้า ---
        $cart_item_count = 0;
        if (!empty($_SESSION['cart'])) {
            $cart_item_count = array_sum($_SESSION['cart']);
        }
        ?>

        <a href="order.php" class="btn btn-gold cart-button">
            🛒
            <span class="cart-count"><?php echo $cart_item_count; ?></span>
        </a>

        <span class="welcome-message">ยินดีต้อนรับ, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="logout.php" class="btn btn-secondary">ออกจากระบบ</a>

    <?php else: ?>
        <a href="login.php" class="btn btn-primary">เข้าสู่ระบบลูกค้า</a>
        <a href="admin_login.php" class="btn btn-secondary">เข้าสู่ระบบแอดมิน</a>
    <?php endif; ?>
</div>
        </div>
    </header>

    <main>
        <section class="dashboard-main">
            <div class="container">
                <div class="dashboard-header">
                    <h2>หน้าสมาชิก</h2>
                    <p>เลือกเมนูที่คุณต้องการด้านล่างนี้</p>
                </div>
                
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <a href="products.php">
                            <i class="fas fa-box-open icon"></i>
                            <h4>ดูสินค้าทั้งหมด</h4>
                            <p>เลือกซื้อเนื้อวัวคุณภาพพรีเมียมของเรา</p>
                        </a>
                    </div>
                    <div class="dashboard-card">
                        <a href="order_status.php">
                            <i class="fas fa-truck-fast icon"></i>
                            <h4>ตรวจสอบสถานะ</h4>
                            <p>ติดตามสถานะคำสั่งซื้อล่าสุดของคุณ</p>
                        </a>
                    </div>
                    <div class="dashboard-card">
                        <a href="profile.php"> <i class="fas fa-user-pen icon"></i>
                            <h4>แก้ไขข้อมูลส่วนตัว</h4>
                            <p>อัปเดตข้อมูลและรหัสผ่านของคุณ</p>
                        </a>
                    </div>
                    <div class="dashboard-card">
                        <a href="contact.php">
                            <i class="fas fa-headset icon"></i>
                            <h4>ติดต่อเจ้าหน้าที่</h4>
                            <p>สอบถามข้อมูลเพิ่มเติมหรือแจ้งปัญหา</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>Beef Export MSU</p>
            <div class="footer-links">
                <a href="contact.php">นโยบายความเป็นส่วนตัว</a> | 
                <a href="contact.php">เงื่อนไขการบริการ</a>
            </div>
        </div>
    </footer>
    
    </body>
</html>