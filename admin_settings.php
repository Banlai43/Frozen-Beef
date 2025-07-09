<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตั้งค่า - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-page-settings">
    <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>

        <div class="admin-main-content">
            <?php include 'admin_navbar.php'; ?>

            <div class="container-fluid p-4">
                <h1 class="h3 mb-4 text-gray-800">ตั้งค่า</h1>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">เปลี่ยนรหัสผ่านผู้ดูแลระบบ</h6>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
                        <?php endif; ?>

                        <form action="admin_settings_process.php" method="POST" class="col-md-6">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน:</label>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
                             <div class="mb-3">
                                <label for="new_password" class="form-label">รหัสผ่านใหม่:</label>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                             <div class="mb-3">
                                <label for="confirm_new_password" class="form-label">ยืนยันรหัสผ่านใหม่:</label>
                                <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>