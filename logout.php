<?php
session_start();

// ลบ session ทั้งหมด
$_SESSION = array();

// ทำลาย session
session_destroy();

// กลับไปยังหน้า login
header("Location: login.php");
exit();
?>