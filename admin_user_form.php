<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: admin_login.php"); exit(); }
require_once 'connect.php';

$user = ['id' => '', 'username' => '', 'email' => '', 'role' => 'user'];
$page_title = 'แก้ไขข้อมูลผู้ใช้';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $connect->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $page_title = 'แก้ไขข้อมูล: ' . htmlspecialchars($user['username']);
    } else {
        header("Location: admin_users.php");
        exit();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin-page-users"> <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>

        <div class="admin-main-content">
            <?php include 'admin_navbar.php'; ?>

            <div class="p-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary"><?php echo $page_title; ?></h6>
                    </div>
                    <div class="card-body">
                        <form action="admin_user_process.php" method="POST" class="col-md-6">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">อีเมล:</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">ชื่อผู้ใช้:</label>
                                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">สิทธิ์ (Role):</label>
                                <select id="role" name="role" class="form-select">
                                    <option value="user" <?php if($user['role'] == 'user') echo 'selected'; ?>>User (ลูกค้าทั่วไป)</option>
                                    <option value="admin" <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin (ผู้ดูแลระบบ)</option>
                                </select>
                            </div>
                            <p class="form-text text-muted small">หมายเหตุ: ไม่สามารถแก้ไขรหัสผ่านของผู้ใช้ได้โดยตรงจากหน้านี้</p>
                            <hr>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง</button>
                            <a href="admin_users.php" class="btn btn-secondary">ยกเลิก</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>