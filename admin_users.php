<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: admin_login.php"); exit(); }
require_once 'connect.php';
$page_title = "จัดการผู้ใช้งาน"; // กำหนดชื่อหน้า
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
<body class="admin-page-users">
    <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>
        <div class="admin-main-content">
            <?php include 'admin_navbar.php'; ?>
            <div class="p-4">
                <div class="card shadow">
                    <div class="card-body">
                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                        <?php endif; ?>
                        <div class="table-responsive">
                             <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>อีเมล</th>
                                        <th>สิทธิ์ (Role)</th>
                                        <th>วันที่สมัคร</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $current_admin_id = $_SESSION['user_id'];
                                    $result = $connect->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo ($row['role'] === 'admin') ? 'danger' : 'secondary'; ?>">
                                                    <?php echo htmlspecialchars($row['role']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <a href="admin_user_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                                <?php if ($row['id'] !== $current_admin_id): ?>
                                                    <a href="admin_user_process.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?');"><i class="fas fa-trash"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center p-5">ไม่มีผู้ใช้งานในระบบ</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>