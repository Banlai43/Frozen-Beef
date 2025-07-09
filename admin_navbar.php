<?php
$page_title = $page_title ?? 'Admin Panel';
?>
<nav class="admin-navbar">
    <h1 class="h3 mb-0 text-gray-800"><?php echo htmlspecialchars($page_title); ?></h1>
    <div class="d-flex align-items-center">
        <span class="navbar-text me-3">
            Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
        </span>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">
            <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
        </a>
    </div>
</nav>