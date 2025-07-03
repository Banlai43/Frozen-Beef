<?php
session_start();
require_once 'connect.php';

// --- 1. ตรวจสอบสถานะการล็อกอินและตะกร้าสินค้า ---
// ถ้ายังไม่ล็อกอิน ให้ส่งไปหน้า login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ";
    header('Location: login.php');
    exit();
}

// ถ้าตะกร้าว่าง ให้ส่งกลับไปหน้าสินค้า
if (empty($_SESSION['cart'])) {
    header('Location: products.php');
    exit();
}


// --- 2. ดึงข้อมูลสินค้าในตะกร้าจากฐานข้อมูล ---
$cart_items = $_SESSION['cart'];
$product_ids = array_keys($cart_items);
$total_price = 0;
$products_in_cart = [];

if (!empty($product_ids)) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    
    $stmt = $connect->prepare("SELECT id, name, price, image_url FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $products_in_cart[$row['id']] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันคำสั่งซื้อ - Beef Export</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
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
                <?php
                    $cart_item_count = array_sum($_SESSION['cart']);
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
            </div>
        </div>
    </header>

    <main>
        <section class="order-form-section">
            <div class="container">
                <h2>ตะกร้าสินค้าและยืนยันคำสั่งซื้อ</h2>

                <div class="cart-summary">
                    <div id="cartItems">
                        <?php foreach ($cart_items as $product_id => $quantity): 
                            if (isset($products_in_cart[$product_id])):
                                $product = $products_in_cart[$product_id];
                                $subtotal = $product['price'] * $quantity;
                                $total_price += $subtotal;
                        ?>
                                <div class="cart-item-editable">
                                    <div class="cart-item-details">
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="cart-item-img">
                                        <p><?php echo htmlspecialchars($product['name']); ?><br>
                                        <span class="cart-item-price">฿<?php echo number_format($product['price'], 2); ?>/กก.</span></p>
                                    </div>
                                    <div class="cart-item-actions">
                                        <form action="cart_actions.php" method="POST" class="update-form"><input type="hidden" name="action" value="update"><input type="hidden" name="product_id" value="<?php echo $product_id; ?>"><input type="number" name="quantity" value="<?php echo $quantity; ?>" min="0" class="quantity-input-cart"><button type="submit" class="btn-update">อัปเดต</button></form>
                                        <form action="cart_actions.php" method="POST"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?php echo $product_id; ?>"><button type="submit" class="btn-remove">ลบ</button></form>
                                    </div>
                                </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                    <hr>
                    <p class="cart-total">รวมทั้งหมด: <span id="totalPrice">฿<?php echo number_format($total_price, 2); ?></span></p>

                    <form id="orderForm" action="submit_order.php" method="POST">
                        <h3>ข้อมูลผู้สั่งซื้อและที่อยู่จัดส่ง</h3>
                        <div class="form-group">
                            <label for="customerName">ชื่อ-นามสกุล ผู้รับ:</label>
                            <input type="text" id="customerName" name="customerName" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">เบอร์โทรศัพท์ติดต่อ:</label>
                            <input type="tel" id="phoneNumber" name="phoneNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="email">อีเมลสำหรับรับการแจ้งเตือน:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="deliveryAddress">ที่อยู่สำหรับจัดส่ง (บ้านเลขที่, ถนน, ตำบล, อำเภอ, จังหวัด, รหัสไปรษณีย์):</label>
                            <textarea id="deliveryAddress" name="deliveryAddress" rows="4" required></textarea>
                        </div>
                        
                        <h3>ช่องทางการชำระเงิน</h3>
                        <div class="form-group">
                            <label for="paymentMethod">เลือกช่องทางชำระเงิน:</label>
                            <select id="paymentMethod" name="paymentMethod" required>
                        <option value="">-- กรุณาเลือก --</option>
                        <option value="bank_transfer">โอนเงินผ่านธนาคาร</option>
                        <option value="cash_on_delivery">เก็บเงินปลายทาง (Cash on Delivery)</option>
                        
                    </select>
                        </div>

                        <button type="submit" class="btn btn-gold" style="width: 100%; margin-top: 20px; padding: 15px; font-size: 1.2rem;">ยืนยันการสั่งซื้อ</button>
                    </form>
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