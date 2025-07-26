<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit('Access Denied');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // ดึงรหัสผ่านปัจจุบันจาก DB
    $stmt_pass = $connect->prepare("SELECT password FROM users WHERE id = ?");
    $stmt_pass->bind_param("i", $admin_id);
    $stmt_pass->execute();
    $user_data = $stmt_pass->get_result()->fetch_assoc();
    $stmt_pass->close();

    // ตรวจสอบรหัสผ่านปัจจุบัน
    if (password_verify($current_password, $user_data['password'])) {
        // ตรวจสอบว่ารหัสผ่านใหม่ตรงกัน
        if ($new_password === $confirm_new_password) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt_update_pass = $connect->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt_update_pass->bind_param("si", $hashed_new_password, $admin_id);
            $stmt_update_pass->execute();
            $stmt_update_pass->close();
            $_SESSION['success_message'] = "อัปเดตรหัสผ่านสำเร็จ!";
        } else {
            $_SESSION['error_message'] = "รหัสผ่านใหม่และการยืนยันไม่ตรงกัน";
        }
    } else {
        $_SESSION['error_message'] = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
    }
    
    header("Location: admin_settings.php");
    exit();
}