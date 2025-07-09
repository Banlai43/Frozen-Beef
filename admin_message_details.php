<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header("Location: admin_login.php"); exit(); }
require_once 'connect.php';

$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($message_id === 0) {
    header("Location: admin_messages.php");
    exit();
}

$stmt = $connect->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->bind_param("i", $message_id);
$stmt->execute();
$message = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$message) {
    header("Location: admin_messages.php");
    exit();
}

$page_title = "รายละเอียดข้อความ #" . $message['id'];
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
<body class="admin-page-messages"> <div class="admin-wrapper">
        <?php include 'admin_sidebar.php'; ?>

        <div class="admin-main-content">
            <?php include 'admin_navbar.php'; ?>

            <div class="p-4">
                <a href="admin_messages.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> กลับไปหน้ารายการ
                </a>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">
                            หัวข้อ: <?php echo htmlspecialchars($message['subject']); ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>จาก:</strong> <?php echo htmlspecialchars($message['name']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>อีเมล:</strong> <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>"><?php echo htmlspecialchars($message['email']); ?></a></p>
                            </div>
                        </div>
                        <p><strong>วันที่ส่ง:</strong> <?php echo date("d F Y, H:i", strtotime($message['created_at'])); ?></p>
                        <hr>
                        <h5 class="mt-4">เนื้อหาข้อความ:</h5>
                        <div class="message-content bg-light p-3 rounded border">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>