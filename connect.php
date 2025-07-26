<?php
// connect.php

// ดึงค่าจาก Environment Variables ของ Render
// ถ้าไม่เจอบน Render ให้ใช้ค่า localhost สำหรับรันบนเครื่องตัวเอง
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$db = getenv('DB_NAME') ?: 'beef';

// ใช้ UTF-8 เพื่อรองรับภาษาไทย
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connect = new mysqli($host, $user, $password, $db);
$connect->set_charset("utf8mb4");

if ($connect->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $connect->connect_error);
}
?>