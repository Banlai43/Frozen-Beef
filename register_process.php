<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบข้อมูลเบื้องต้น
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน";
        header("Location: register.php");
        exit();
    }
    
    // ตรวจสอบว่ามีอีเมลนี้ในระบบแล้วหรือยัง
    $stmt = $connect->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "อีเมลนี้ถูกใช้งานแล้ว กรุณาใช้อีเมลอื่น";
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    // เข้ารหัสผ่านด้วย Argon2ID (ตรงตามรูปแบบในรูปภาพของคุณ)
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);

    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $connect->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . $stmt->error;
        header("Location: register.php");
        exit();
    }
    $stmt->close();
    $connect->close();
}
?>