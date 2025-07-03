<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดต่อเรา - Beef Export</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
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
                    <li><a href="contact.php" class="active">ติดต่อเรา</a></li>
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
        <section class="contact-section">
            <div class="container">
                <h2>ติดต่อเรา</h2>
                <div class="contact-info-grid">
                    <div class="contact-item">
                        <h3>ที่อยู่</h3>
                        <p>บริษัท </p>
                        <p>มมส</p>
                        <p>มหาสารคาม</p>
                        <div class="map-embed">
                            <iframe src="https://www.google.com/maps/place/%E0%B8%84%E0%B8%93%E0%B8%B0%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%9A%E0%B8%B1%E0%B8%8D%E0%B8%8A%E0%B8%B5%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%88%E0%B8%B1%E0%B8%94%E0%B8%81%E0%B8%B2%E0%B8%A3+%E0%B8%A1%E0%B8%AB%E0%B8%B2%E0%B8%A7%E0%B8%B4%E0%B8%97%E0%B8%A2%E0%B8%B2%E0%B8%A5%E0%B8%B1%E0%B8%A2%E0%B8%A1%E0%B8%AB%E0%B8%B2%E0%B8%AA%E0%B8%B2%E0%B8%A3%E0%B8%84%E0%B8%B2%E0%B8%A1/@16.2442626,103.2548056,14z/data=!4m6!3m5!1s0x3122a39997b0ef51:0x846e64234611352e!8m2!3d16.2482971!4d103.247609!16s%2Fg%2F1hc0hr290?entry=ttu&g_ep=EgoyMDI1MDYyMy4yIKXMDSoASAFQAw%3D%3D" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="contact-item">
                        <h3>ข้อมูลติดต่อ</h3>
                        <p><strong>เบอร์โทรศัพท์:</strong> <a href="tel:+66987654321">+66 (0) 98 765 4321</a></p>
                        <p><strong>อีเมล:</strong> <a href="mailto:@beefexport.com">@primebeefexport.com</a></p>
                        <p><strong>LINE Official:</strong> @BeefExport</p>
                        <img src="images/line-qr-code.png" alt="LINE QR Code" class="line-qr-code">
                        <p><strong>เวลาทำการ:</strong> จันทร์ - ศุกร์, 09:00 - 17:00 น.</p>
                    </div>
                </div>

                <div class="contact-form-section">
                    <h3>ส่งข้อความถึงเรา</h3>
                    <form id="contactForm" action="submit_contact.php" method="POST">
                        <div class="form-group">
                            <label for="contactName">ชื่อของคุณ:</label>
                            <input type="text" id="contactName" name="contactName" required>
                        </div>
                        <div class="form-group">
                            <label for="contactEmail">อีเมล:</label>
                            <input type="email" id="contactEmail" name="contactEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="contactSubject">เรื่อง:</label>
                            <input type="text" id="contactSubject" name="contactSubject">
                        </div>
                        <div class="form-group">
                            <label for="contactMessage">ข้อความของคุณ:</label>
                            <textarea id="contactMessage" name="contactMessage" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-gold">ส่งข้อความ</button>
                        <div id="contactStatusMessage" class="status-message"></div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; Beef Export MSU</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>