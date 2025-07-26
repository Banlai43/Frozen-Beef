<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];

    $stmt_user = $connect->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt_user->bind_param("si", $username, $user_id);
    $stmt_user->execute();
    $_SESSION['username'] = $username;
    $stmt_user->close();

    $_SESSION['success_message'] = "อัปเดตชื่อผู้ใช้สำเร็จแล้ว";

    if (!empty($_POST['current_password']) && !empty($_POST['new_password']) && !empty($_POST['confirm_new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_new_password = $_POST['confirm_new_password'];

        $stmt_pass = $connect->prepare("SELECT password FROM users WHERE id = ?");
        $stmt_pass->bind_param("i", $user_id);
        $stmt_pass->execute();
        $user_data = $stmt_pass->get_result()->fetch_assoc();
        $stmt_pass->close();

        if (password_verify($current_password, $user_data['password'])) {
            if ($new_password === $confirm_new_password) {
                $hashed_new_password = password_hash($new_password, PASSWORD_ARGON2ID);
                $stmt_update_pass = $connect->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt_update_pass->bind_param("si", $hashed_new_password, $user_id);
                $stmt_update_pass->execute();
                $stmt_update_pass->close();
                $_SESSION['success_message'] = "อัปเดตข้อมูลส่วนตัวและรหัสผ่านสำเร็จ!";
            } else {
                $_SESSION['error_message'] = "รหัสผ่านใหม่และการยืนยันไม่ตรงกัน";
            }
        } else {
            $_SESSION['error_message'] = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
        }
    }
    
    header("Location: profile.php");
    exit();
}