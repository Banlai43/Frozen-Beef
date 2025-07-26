<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    exit('Access Denied');
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $connect->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "ลบข้อความเรียบร้อยแล้ว";
    }
    $stmt->close();
}

header("Location: admin_messages.php");
exit();