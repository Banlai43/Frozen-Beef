<?php
// กำหนดชื่อหน้าและเรียกใช้ Header
$page_title = "ยืนยันคำสั่งซื้อ";
include 'header.php';

//--- ตรวจสอบการล็อกอินและตะกร้าสินค้า ---
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ";
    echo '<script>window.location.href="login.php";</script>';
    exit();
}
if (empty($_SESSION['cart'])) {
    echo '<script>window.location.href="products.php";</script>';
    exit();
}

//--- ดึงข้อมูลสินค้าจาก DB ---
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

<div class="order-form-section">
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
                                <p>
                                    <?php echo htmlspecialchars($product['name']); ?><br>
                                    <span class="cart-item-price">฿<?php echo number_format($product['price'], 2); ?>/กก.</span>
                                </p>
                            </div>
                            <div class="cart-item-actions">
                                <form action="cart_actions.php" method="POST" class="update-form">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="0" class="quantity-input-cart">
                                    <button type="submit" class="btn-update">อัปเดต</button>
                                </form>
                                <form action="cart_actions.php" method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" class="btn-remove">ลบ</button>
                                </form>
                            </div>
                        </div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>

            <p class="cart-total">รวมทั้งหมด: <span>฿<?php echo number_format($total_price, 2); ?></span></p>

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
                    <label for="deliveryAddress">ที่อยู่สำหรับจัดส่ง:</label>
                    <textarea id="deliveryAddress" name="deliveryAddress" rows="4" required></textarea>
                </div>
                <button type="submit">ยืนยันการสั่งซื้อ</button>
            </form>
        </div>
    </div>
</div>

<?php
// เรียกใช้ Footer เพื่อปิดท้ายหน้า
include 'footer.php';
?>