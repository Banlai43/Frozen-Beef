<?php
session_start();
require_once 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "กรุณากรอกอีเมลและรหัสผ่าน";
        header("Location: login.php");
        exit();
    }

    // ค้นหาผู้ใช้จากอีเมล
    $stmt = $connect->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่านที่เข้ารหัสไว้
        if (password_verify($password, $user['password'])) {
            // หากรหัสผ่านถูกต้อง สร้าง session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
    $connect->close();
}
?>