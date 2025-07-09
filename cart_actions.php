<?php
session_start();

// เริ่มต้นสร้างตะกร้าถ้ายังไม่มี
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST และมี action กำกับหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    $action = $_POST['action'];
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    if ($product_id > 0) {

        switch ($action) {
            // กรณี: เพิ่มสินค้า
            case 'add':
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                if ($quantity > 0) {
                    if (isset($_SESSION['cart'][$product_id])) {
                        // ถ้ามีอยู่แล้ว ให้บวกจำนวนเพิ่ม
                        $_SESSION['cart'][$product_id] += $quantity;
                    } else {
                        // ถ้ายังไม่มี ให้เพิ่มใหม่
                        $_SESSION['cart'][$product_id] = $quantity;
                    }
                }
                // *** บรรทัดสำคัญ: หลังจากเพิ่มสินค้า ให้กลับไปหน้าสินค้าเสมอ ***
                header('Location: products.php');
                exit();
                break;

            // กรณี: อัปเดตหรือลบสินค้า (จะส่งกลับไปหน้าตะกร้า)
            case 'update':
            case 'remove':
                if ($action === 'update') {
                    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
                    if ($quantity > 0) {
                        $_SESSION['cart'][$product_id] = $quantity;
                    } else {
                        // ถ้าจำนวนเป็น 0 หรือน้อยกว่า ให้ลบออก
                        unset($_SESSION['cart'][$product_id]);
                    }
                }
                if ($action === 'remove') {
                    unset($_SESSION['cart'][$product_id]);
                }
                // ส่งกลับไปหน้าตะกร้า
                header('Location: order.php');
                exit();
                break;
        }
    }
}

// ถ้าไม่มี action ที่ถูกต้อง หรือมีข้อผิดพลาด ให้ส่งกลับไปหน้าแรก
header('Location: index.php');
exit();
?>