<?php
// connect.php

$host = "localhost";
$user = "root";
$password = "";
$db = "beef";

// ใช้ UTF-8 เพื่อรองรับภาษาไทย
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$connect = new mysqli($host, $user, $password, $db);
$connect->set_charset("utf8mb4");

if ($connect->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $connect->connect_error);
}
?>