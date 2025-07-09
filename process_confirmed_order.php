<?php
// process_confirmed_order.php
session_start();
require_once 'connect.php';

// ตรวจสอบว่ามีข้อมูลออเดอร์ที่รอการยืนยัน และผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['pending_order']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$order_data = $_SESSION['pending_order'];
$user_id = $_SESSION['user_id'];
$cart_items = $order_data['cart'];
$total_price = 0;

// ดึงข้อมูลสินค้าและคำนวณราคารวมอีกครั้งเพื่อความปลอดภัย
$products_data = [];
if (!empty($cart_items)) {
    $product_ids = array_keys($cart_items);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $types = str_repeat('i', count($product_ids));
    $stmt_price = $connect->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
    $stmt_price->bind_param($types, ...$product_ids);
    $stmt_price->execute();
    $result_price = $stmt_price->get_result();
    while ($row = $result_price->fetch_assoc()) {
        $products_data[$row['id']] = $row['price'];
    }
    $stmt_price->close();
    foreach ($cart_items as $product_id => $quantity) {
        if (isset($products_data[$product_id])) {
            $total_price += $products_data[$product_id] * $quantity;
        }
    }
}

// ใช้ Transaction เพื่อความถูกต้องของข้อมูล
$connect->begin_transaction();
try {
    // 1. บันทึกในตาราง orders
    $stmt_order = $connect->prepare("INSERT INTO orders (user_id, customer_name, phone, email, address, payment_method, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt_order->bind_param("isssssd", $user_id, $order_data['customer_name'], $order_data['phone'], $order_data['email'], $order_data['address'], $order_data['payment_method'], $total_price);
    $stmt_order->execute();
    $order_id = $connect->insert_id;
    $stmt_order->close();

    // 2. บันทึกรายการสินค้าแต่ละชิ้นใน order_items
    $stmt_items = $connect->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_per_unit) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $product_id => $quantity) {
        if (isset($products_data[$product_id])) {
            $price_per_unit = $products_data[$product_id];
            $stmt_items->bind_param("iiid", $order_id, $product_id, $quantity, $price_per_unit);
            $stmt_items->execute();
        }
    }
    $stmt_items->close();

    // 3. ยืนยันการบันทึก
    $connect->commit();

    // 4. ล้างข้อมูลชั่วคราวและตะกร้า
    unset($_SESSION['pending_order']);
    unset($_SESSION['cart']);

    // 5. ส่งต่อไปยังหน้าชำระเงิน
    header("Location: payment.php?order_id=" . $order_id);
    exit();

} catch (mysqli_sql_exception $exception) {
    $connect->rollback();
    $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ: " . $exception->getMessage();
    header("Location: confirm_order.php"); // กลับไปหน้ายืนยันถ้าพลาด
    exit();
}

?>