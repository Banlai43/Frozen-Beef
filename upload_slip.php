<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['slip_image']) && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // --- ตรวจสอบว่าเป็นเจ้าของออเดอร์จริง ---
    $stmt = $connect->prepare("SELECT id FROM orders WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $_SESSION['upload_error'] = "ไม่พบคำสั่งซื้อนี้ หรือคุณไม่ใช่เจ้าของ";
        header("Location: order_status.php");
        exit();
    }
    $stmt->close();

    // --- จัดการการอัปโหลดไฟล์ ---
    if ($_FILES['slip_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/slips/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES["slip_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = "slip_" . $order_id . "_" . time() . "." . strtolower($file_extension);
        $target_file = $target_dir . $new_filename;

        // ย้ายไฟล์
        if (move_uploaded_file($_FILES["slip_image"]["tmp_name"], $target_file)) {
            // อัปเดตฐานข้อมูล
            $update_stmt = $connect->prepare("UPDATE orders SET payment_slip = ?, order_status = 'Verifying Payment' WHERE id = ?");
            $update_stmt->bind_param("si", $target_file, $order_id);
            $update_stmt->execute();
            $update_stmt->close();
            $_SESSION['upload_success'] = "อัปโหลดสลิปสำหรับออเดอร์ #$order_id สำเร็จแล้ว";
        } else {
            $_SESSION['upload_error'] = "เกิดข้อผิดพลาดในการบันทึกไฟล์";
        }
    } else {
        $_SESSION['upload_error'] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
    }
}

header("Location: order_status.php");
exit();