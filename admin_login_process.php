<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
        header("Location: admin_login.php");
        exit();
    }

    // ค้นหาผู้ใช้จากอีเมล
    $stmt = $connect->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            
            // *** จุดสำคัญ: ตรวจสอบว่าเป็น Admin หรือไม่ ***
            if ($user['role'] === 'admin') {
                // หากเป็น Admin ให้สร้าง Session และส่งไปหน้า Dashboard ของ Admin
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // เก็บ role ไว้ใน session ด้วย
                header("Location: admin_dashboard.php");
                exit();
            } else {
                // ถ้าเป็น User ธรรมดา แต่พยายามล็อกอินหน้า Admin ให้แจ้งว่าผิดพลาด
                $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
                header("Location: admin_login.php");
                exit();
            }

        } else {
            // รหัสผ่านไม่ถูกต้อง
            $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
            header("Location: admin_login.php");
            exit();
        }
    } else {
        // ไม่พบอีเมลในระบบ
        $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
        header("Location: admin_login.php");
        exit();
    }
    $stmt->close();
    $connect->close();
}
?>