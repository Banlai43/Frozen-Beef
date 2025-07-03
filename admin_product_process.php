<?php
session_start();
require_once 'connect.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit('Access Denied');
}

// --- จัดการการลบ ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // ดึง URL ของรูปภาพเก่าเพื่อนำไปลบไฟล์
    $stmt_select = $connect->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if ($row = $result->fetch_assoc()) {
        $image_to_delete = $row['image_url'];
        // ตรวจสอบว่าไฟล์มีอยู่จริงและไม่ใช่ path เริ่มต้น ก่อนที่จะลบ
        if (!empty($image_to_delete) && file_exists($image_to_delete)) {
            unlink($image_to_delete); // ลบไฟล์รูปภาพ
        }
    }
    $stmt_select->close();

    // ลบข้อมูลสินค้าออกจากฐานข้อมูล
    $stmt = $connect->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_products.php");
    exit();
}

// --- จัดการการเพิ่มและแก้ไข ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $code = $_POST['product_code'];
    $type = $_POST['product_type'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image_path = $_POST['current_image'] ?? ''; // ใช้รูปเดิมเป็นค่าเริ่มต้น

    // อัปโหลดรูปภาพใหม่ (ถ้ามี)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/"; // โฟลเดอร์เก็บรูปภาพ
        // สร้างโฟลเดอร์ถ้ายังไม่มี
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid('product_', true) . '.' . strtolower($file_extension);
        $target_file = $target_dir . $new_filename;
        
        // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ images
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // ถ้าอัปโหลดสำเร็จ ให้ลบรูปเก่า (ถ้ามี)
            if (!empty($image_path) && file_exists($image_path)) {
                 unlink($image_path);
            }
            $image_path = $target_file; // เปลี่ยน path เป็นรูปใหม่
        }
    }

    if ($_POST['action'] == 'add') {
        $stmt = $connect->prepare("INSERT INTO products (name, product_code, product_type, price, description, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        // แก้ไข: เพิ่ม 's' สำหรับ image_url
        $stmt->bind_param("sssds", $name, $code, $type, $price, $desc, $image_path);
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $connect->prepare("UPDATE products SET name=?, product_code=?, product_type=?, price=?, description=?, image_url=? WHERE id=?");
        // แก้ไข: เพิ่ม 's' สำหรับ image_url ก่อน 'i'
        $stmt->bind_param("sssdssi", $name, $code, $type, $price, $desc, $image_path, $id);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: admin_products.php");
    exit();
}
?>