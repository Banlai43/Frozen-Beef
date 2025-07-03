<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Beef Export - เนื้อวัวแช่แข็งคุณภาพพรีเมียม</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <img src="images/logo.png" alt="Prime Beef Export Logo">
                <h1>Prime Beef Export</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">หน้าแรก</a></li>
                    <li><a href="products.php">สินค้า</a></li>
                    <li><a href="contact.php">ติดต่อเรา</a></li>
                    <li><a href="order_status.php">สถานะคำสั่งซื้อ</a></li>
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
        <section class="hero-section">
            <div class="container">
                <div class="hero-text">
                    <h2>ที่สุดแห่งคุณภาพเนื้อวัวแช่แข็ง เพื่อการส่งออก</h2>
                    <p>เราคัดสรรเนื้อวัวเกรดพรีเมียมจากฟาร์มที่ได้มาตรฐานสากล ผ่านกระบวนการแช่แข็งทันสมัย คงความสดใหม่และรสชาติอันเป็นเลิศ </p>
                    <div class="hero-buttons">
                        <a href="products.php" class="btn btn-gold">ดูสินค้าทั้งหมด</a>
                        <a href="contact.php" class="btn btn-outline-gold">ติดต่อเรา</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="quality-section">
            <div class="container">
                <h3>ทำไมต้องเลือก Prime Beef Export?</h3>
                <div class="quality-grid">
                    <div class="quality-item">
                        <i class="fas fa-seedling icon-quality"></i> <h4>แหล่งที่มาคุณภาพ</h4>
                        <p>เนื้อวัวของเรามาจากฟาร์มที่ได้รับการรับรองมาตรฐานการเลี้ยงดูระดับโลก เพื่อให้มั่นใจในคุณภาพและสุขอนามัยที่ดีที่สุด</p>
                    </div>
                    <div class="quality-item">
                        <i class="fas fa-snowflake icon-quality"></i> <h4>กระบวนการแช่แข็งทันสมัย</h4>
                        <p>ใช้เทคโนโลยีการแช่แข็งแบบรวดเร็ว (IQF) เพื่อคงสภาพเนื้อสัมผัส รสชาติ และคุณค่าทางโภชนาการให้สมบูรณ์แบบ</p>
                    </div>
                    <div class="quality-item">
                        <i class="fas fa-boxes icon-quality"></i> <h4>หลากหลายผลิตภัณฑ์</h4>
                        <p>ไม่ว่าจะเป็นสันใน ริบอาย สันนอก หรือส่วนอื่นๆ เรามีเนื้อวัวหลากหลายชนิด ตอบโจทย์ทุกความต้องการทางธุรกิจของคุณ</p>
                    </div>
                    <div class="quality-item">
                        <i class="fas fa-award icon-quality"></i> <h4>มาตรฐานส่งออกสากล</h4>
                        <p>ผลิตภัณฑ์ของเราได้รับการรับรองมาตรฐานสากล เช่น HACCP และ GMP เพื่อความมั่นใจในทุกการจัดส่ง</p>
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
    <script src="js/script.js"></script>
</body>
</html>