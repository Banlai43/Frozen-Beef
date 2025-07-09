<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['slip_image'])) {
    $order_id = (int)$_POST['order_id'];
    $file = $_FILES['slip_image'];

    // ตรวจสอบว่ามีไฟล์อัปโหลดมาจริง
    if ($file['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'slips/'; // สร้างโฟลเดอร์นี้ในโปรเจกต์ของคุณ
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // สร้างชื่อไฟล์ใหม่เพื่อป้องกันชื่อซ้ำ
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = 'slip_' . $order_id . '_' . time() . '.' . $file_extension;
        $target_path = $upload_dir . $new_filename;

        // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            // บันทึก path ของสลิปลงใน DB และเปลี่ยนสถานะ
            // คุณอาจจะต้องเพิ่มคอลัมน์ `slip_url` และ `payment_verified_at` ในตาราง `orders`
            $stmt = $connect->prepare("UPDATE orders SET order_status = 'รอการตรวจสอบ', slip_url = ? WHERE id = ?");
            $stmt->bind_param("si", $target_path, $order_id);
            $stmt->execute();
            
            $_SESSION['success_message'] = "อัปโหลดสลิปสำหรับคำสั่งซื้อ #{$order_id} เรียบร้อยแล้ว! ขอบคุณครับ";
            header("Location: order_status.php");
            exit();
        }
    }
}

$_SESSION['error_message'] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
header("Location: payment.php?order_id=" . $order_id); // กลับไปหน้าเดิมถ้าพลาด
exit();

?>