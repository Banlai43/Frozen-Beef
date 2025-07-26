<?php
session_start();
require_once 'connect.php';

// ตรวจสอบสิทธิ์แอดมิน
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit('Access Denied');
}

// --- จัดการการลบ ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $current_admin_id = $_SESSION['user_id'];

    // ป้องกันแอดมินลบตัวเอง
    if ($id === $current_admin_id) {
        $_SESSION['error_message'] = "ไม่สามารถลบบัญชีของตัวเองได้";
        header("Location: admin_users.php");
        exit();
    }

    $stmt = $connect->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        $_SESSION['success_message'] = "ลบผู้ใช้สำเร็จแล้ว";
    }
    $stmt->close();
    header("Location: admin_users.php");
    exit();
}

// --- จัดการการอัปเดต ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = (int)$_POST['id'];
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    $stmt = $connect->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $role, $id);
    if($stmt->execute()){
        $_SESSION['success_message'] = "อัปเดตข้อมูลผู้ใช้ #" . $id . " เรียบร้อยแล้ว";
    }
    $stmt->close();
    header("Location: admin_users.php");
    exit();
}

// ถ้าไม่มี action ที่ถูกต้อง
header("Location: admin_users.php");
exit();