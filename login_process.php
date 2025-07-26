<?php
// บรรทัดนี้สำคัญมาก ต้องเริ่ม session ก่อนเสมอ
session_start();
require_once 'connect.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "กรุณากรอกอีเมลและรหัสผ่าน";
        header("Location: login.php");
        exit();
    }

    // ค้นหาผู้ใช้จากอีเมล (ใช้ prepared statement เพื่อความปลอดภัย)
    $stmt = $connect->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่านที่ถูก hash ไว้
        if (password_verify($password, $user['password'])) {
            
            // --- ✅ นี่คือส่วนสำคัญที่เพิ่มเข้ามา ---
            // เมื่อรหัสผ่านถูกต้อง ให้บันทึกข้อมูลลง Session ทันที
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // เก็บ role ไว้ด้วย

            // ตรวจสอบว่าเป็น 'admin' หรือ 'user'
            if ($user['role'] === 'admin') {
                // ถ้าเป็น admin ให้ส่งไปหน้า admin dashboard
                header("Location: admin_dashboard.php");
            } else {
                // ถ้าเป็น user ธรรมดา ให้ส่งไปหน้า dashboard ของลูกค้า
                header("Location: dashboard.php");
            }
            exit(); // จบการทำงานทันทีหลังจาก redirect

        } else {
            // รหัสผ่านไม่ถูกต้อง
            $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
            header("Location: login.php");
            exit();
        }
    } else {
        // ไม่พบอีเมลในระบบ
        $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
    $connect->close();
} else {
    // ถ้าไม่ได้เข้ามาหน้านี้ผ่านการ POST ให้กลับไปหน้าแรก
    header("Location: index.php");
    exit();
}
?>