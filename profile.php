<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

$stmt = $connect->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<?php 
    include 'header.php';
    echo "<title>แก้ไขข้อมูลส่วนตัว - Beef Export</title>";
?>

<div class="container mt-5 mb-5">
    <h2>แก้ไขข้อมูลส่วนตัว</h2>
    <hr>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <form action="profile_process.php" method="POST">
        <div class="mb-3">
            <label class="form-label">อีเมล:</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">ชื่อผู้ใช้ (Username):</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        
        <h4 class="mt-4">เปลี่ยนรหัสผ่าน (กรอกหากต้องการเปลี่ยน)</h4>
        <div class="mb-3">
            <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน:</label>
            <input type="password" id="current_password" name="current_password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">รหัสผ่านใหม่:</label>
            <input type="password" id="new_password" name="new_password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="confirm_new_password" class="form-label">ยืนยันรหัสผ่านใหม่:</label>
            <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>

<?php include 'footer.php'; ?>