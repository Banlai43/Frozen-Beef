<?php
session_start();
require_once 'connect.php';

// ตรวจสอบว่ามีการล็อกอินและมีของในตะกร้าหรือไม่
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['customerName'];
    $phone = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $address = $_POST['deliveryAddress'];
    $payment_method = 'QR Payment'; // กำหนดค่าเป็น QR Payment โดยอัตโนมัติ

    // คำนวณราคารวมจากฐานข้อมูลเพื่อความปลอดภัย
    $total_price = 0;
    $cart_items = $_SESSION['cart'];
    $product_ids = array_keys($cart_items);
    $products_data = [];

    if (!empty($product_ids)) {
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
        $stmt_order = $connect->prepare("INSERT INTO orders (user_id, customer_name, phone, email, address, payment_method, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_order->bind_param("isssssd", $user_id, $name, $phone, $email, $address, $payment_method, $total_price);
        $stmt_order->execute();
        $order_id = $connect->insert_id; // ดึง ID ของออเดอร์ที่เพิ่งสร้าง
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

        // 4. ล้างตะกร้าสินค้า
        unset($_SESSION['cart']); 
        
        // 5. ส่งต่อไปยังหน้าชำระเงินพร้อมกับ ID ของออเดอร์
        header("Location: payment.php?order_id=" . $order_id);
        exit();

    } catch (mysqli_sql_exception $exception) {
        $connect->rollback();
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ";
        header("Location: order.php");
        exit();
    }
}