<?php
session_start();

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    // ตรวจสอบว่าเป็นภาษาที่รองรับหรือไม่ (en หรือ th)
    if ($lang == 'en' || $lang == 'th') {
        $_SESSION['lang'] = $lang;
    }
}

// ส่งผู้ใช้กลับไปยังหน้าเดิมที่พวกเขาอยู่
$previous_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header('Location: ' . $previous_page);
exit();
?>