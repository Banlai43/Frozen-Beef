<?php
// submit_contact.php (ปลอดภัย)
session_start();
require('connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['contactName'];
    $email = $_POST['contactEmail'];
    $subject = $_POST['contactSubject'];
    $message = $_POST['contactMessage'];

    // ใช้ Prepared Statements เพื่อป้องกัน SQL Injection
    $stmt = $connect->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    // 'ssss' หมายถึงตัวแปร 4 ตัวเป็นชนิด String
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "ส่งข้อความเรียบร้อยแล้ว ทีมงานจะติดต่อกลับโดยเร็วที่สุด";
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการส่งข้อความ: " . $stmt->error;
    }
    $stmt->close();
    // เปลี่ยนลิงก์ไปที่ contact.php
    header("Location: contact.php");
    exit();

} else {
    echo "ไม่สามารถเข้าถึงไฟล์นี้โดยตรงได้";
}
?>