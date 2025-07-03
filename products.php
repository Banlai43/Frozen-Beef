<?php
session_start();
require_once 'connect.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าของเรา - Beef Export</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <header>
        <div class="container header-content">
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" alt="Prime Beef Export Logo"></a>
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
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                    // คำนวณจำนวนสินค้าในตะกร้า
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
                            <a href="logout.php">ออกจากระบบ</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">เข้าสู่ระบบ</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main>
        <section class="products-header">
            <div class="container">
                <h2>สินค้าของเรา</h2>
                <div class="filter-sort-controls">
                    <div class="search-box">
                        <input type="text" id="searchProduct" placeholder="ค้นหาสินค้า..." onkeyup="filterProducts()">
                    </div>
                    <div class="filter-group">
                        <label for="meatType">ประเภทเนื้อ:</label>
                        <select id="meatType" onchange="filterProducts()">
                            <option value="all">ทั้งหมด</option>
                            <?php
                            $type_query = "SELECT DISTINCT product_type FROM products WHERE product_type IS NOT NULL AND product_type != '' ORDER BY product_type";
                            $type_result = $connect->query($type_query);
                            while($type_row = $type_result->fetch_assoc()){
                                echo '<option value="' . htmlspecialchars($type_row['product_type']) . '">' . htmlspecialchars($type_row['product_type']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="products-grid-section">
            <div class="container">
                <div class="products-grid" id="productsGrid">
                    <?php
                    $sql = "SELECT * FROM products ORDER BY id ASC";
                    $result = $connect->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                            <div class="product-card" data-type="<?php echo htmlspecialchars(strtolower($row['product_type'])); ?>" data-name="<?php echo htmlspecialchars(strtolower($row['name'])); ?>">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                <p class="product-id">รหัสสินค้า: <?php echo htmlspecialchars($row['product_code']); ?></p>
                                <p class="product-price">ราคา: ฿<?php echo number_format($row['price'], 2); ?>/กก.</p>
                                <div class="product-actions">
                                    <form action="cart_actions.php" method="POST" class="add-to-cart-form">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                                        <button type="submit" class="btn btn-add-cart">เพิ่มลงตะกร้า</button>
                                    </form>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>ไม่พบสินค้าในขณะนี้</p>";
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>© Beef Export MSU</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>