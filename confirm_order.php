<?php
$page_title = "ยืนยันรายการสั่งซื้อ";
include 'header.php';

// ตรวจสอบว่ามีข้อมูลออเดอร์ที่รอการยืนยันหรือไม่
if (!isset($_SESSION['pending_order'])) {
    echo "<div class='container' style='text-align:center; padding: 50px;'><h2>ไม่พบข้อมูลคำสั่งซื้อ</h2><p>กรุณากลับไปที่ตะกร้าสินค้า</p><a href='order.php' class='btn btn-primary'>กลับไปตะกร้า</a></div>";
    include 'footer.php';
    exit();
}

$order_data = $_SESSION['pending_order'];
$cart_items = $order_data['cart'];
$total_price = 0;
$products_in_cart = [];

// ดึงข้อมูลสินค้าจากฐานข้อมูล
if (!empty($cart_items)) {
    $product_ids = array_keys($cart_items);
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

<style>
    .confirm-container { max-width: 800px; margin: 40px auto; }
    .order-summary-card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 25px; }
    .order-summary-card h3 { color: #8B0000; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-top: 0; }
    .summary-item { display: flex; justify-content: space-between; padding: 8px 0; }
    .summary-item strong { color: #333; }
    .customer-info p { margin: 5px 0; font-size: 1.1em; }
    .customer-info p strong { display: inline-block; width: 120px; color: #555; }
    .confirm-actions { text-align: center; margin-top: 30px; }
    .confirm-actions .btn { padding: 15px 35px; font-size: 1.2em; }
</style>

<div class="container confirm-container">
    <h2 style="text-align:center; margin-bottom: 30px;">กรุณาตรวจสอบรายการสั่งซื้อ</h2>

    <div class="order-summary-card">
        <h3>รายการสินค้า</h3>
        <?php foreach ($cart_items as $product_id => $quantity): 
            if(isset($products_in_cart[$product_id])):
            $product = $products_in_cart[$product_id];
            $subtotal = $product['price'] * $quantity;
            $total_price += $subtotal;
        ?>
            <div class="summary-item">
                <span><?php echo htmlspecialchars($product['name']); ?> x <?php echo $quantity; ?></span>
                <strong>฿<?php echo number_format($subtotal, 2); ?></strong>
            </div>
        <?php endif; endforeach; ?>
        <hr>
        <div class="summary-item" style="font-size: 1.5em; font-weight: bold;">
            <span>ยอดรวมทั้งหมด</span>
            <strong>฿<?php echo number_format($total_price, 2); ?></strong>
        </div>
    </div>

    <div class="order-summary-card customer-info">
        <h3>ข้อมูลการจัดส่งและชำระเงิน</h3>
        <p><strong>ชื่อ-นามสกุล:</strong> <?php echo htmlspecialchars($order_data['customer_name']); ?></p>
        <p><strong>เบอร์โทรศัพท์:</strong> <?php echo htmlspecialchars($order_data['phone']); ?></p>
        <p><strong>อีเมล:</strong> <?php echo htmlspecialchars($order_data['email']); ?></p>
        <p><strong>ที่อยู่จัดส่ง:</strong> <?php echo nl2br(htmlspecialchars($order_data['address'])); ?></p>
        <p><strong>วิธีชำระเงิน:</strong> <?php echo $order_data['payment_method'] === 'bank_transfer' ? 'โอนเงินผ่านธนาคาร' : 'เก็บเงินปลายทาง'; ?></p>
    </div>

    <div class="confirm-actions">
        <form action="process_confirmed_order.php" method="POST" style="display:inline;">
            <button type="submit" class="btn btn-success">ยืนยันการสั่งซื้อและชำระเงิน</button>
        </form>
        <a href="order.php" class="btn btn-secondary" style="margin-left: 15px;">กลับไปแก้ไข</a>
    </div>
</div>

<?php include 'footer.php'; ?>