<?php
// กำหนด Title และเรียกใช้ header กลางของเว็บ
$page_title = "Dashboard";
include 'header.php';

// ตรวจสอบการล็อกอิน (header.php ได้เริ่ม session ให้แล้ว)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="dashboard-main">
    <div class="container">
        <div class="dashboard-header">
            <h2><?php echo $lang['dashboard_title']; ?></h2>
            <p><?php echo $lang['dashboard_subtitle']; ?></p>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <a href="products.php">
                    <i class="fas fa-box-open icon"></i>
                    <h4><?php echo $lang['view_all_products']; ?></h4>
                    <p><?php echo $lang['view_all_products_desc']; ?></p>
                </a>
            </div>
            <div class="dashboard-card">
                <a href="order_status.php">
                    <i class="fas fa-truck-fast icon"></i>
                    <h4><?php echo $lang['check_status']; ?></h4>
                    <p><?php echo $lang['check_status_desc']; ?></p>
                </a>
            </div>
            <div class="dashboard-card">
                <a href="profile.php">
                    <i class="fas fa-user-pen icon"></i>
                    <h4><?php echo $lang['edit_profile']; ?></h4>
                    <p><?php echo $lang['edit_profile_desc']; ?></p>
                </a>
            </div>
            <div class="dashboard-card">
                <a href="contact.php">
                    <i class="fas fa-headset icon"></i>
                    <h4><?php echo $lang['contact_staff']; ?></h4>
                    <p><?php echo $lang['contact_staff_desc']; ?></p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php 
// เรียกใช้ footer กลางของเว็บ
include 'footer.php'; 
?>