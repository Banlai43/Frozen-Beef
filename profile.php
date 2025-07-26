<?php
// 1. เริ่ม session และตรวจสอบการล็อกอินก่อนเป็นอันดับแรก
session_start();
require_once 'connect.php'; 

// 2. ถ้ายังไม่ล็อกอิน ให้ redirect ไปหน้า login ทันที
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// --- โค้ดส่วนที่เหลือของหน้าจะทำงานก็ต่อเมื่อผู้ใช้ล็อกอินแล้ว ---

// 3. กำหนด title และ include header.php
$page_title = "แก้ไขข้อมูลส่วนตัว";
include 'header.php';

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $connect->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<div class="container my-5">
    <div class="profile-card-container">
        <h2 class="text-center mb-4">แก้ไขข้อมูลส่วนตัว</h2>

        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
        <?php endif; ?>

        <form action="profile_process.php" method="POST">
            <div class="mb-3">
                <label class="form-label">อีเมล</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้ (Username)</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            
            <hr class="my-4">
            
            <h4 class="mb-3">เปลี่ยนรหัสผ่าน (กรอกหากต้องการเปลี่ยน)</h4>
            <div class="mb-3">
                <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน</label>
                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="กรอกรหัสผ่านปัจจุบันของคุณ">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                    <input type="password" id="new_password" name="new_password" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirm_new_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control">
                </div>
            </div>
            
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>