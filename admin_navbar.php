<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php"><i class="fas fa-cogs"></i> Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="admin_products.php">จัดการสินค้า</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_orders.php">จัดการคำสั่งซื้อ</a></li>
                <li class="nav-item"><a class="nav-link" href="#">จัดการผู้ใช้งาน</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="navbar-text me-3">
                        Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="logout.php">ออกจากระบบ</a>
                </li>
            </ul>
        </div>
    </div>
</nav>