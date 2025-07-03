<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Beef Export</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 450px; margin: auto; }
        .card { border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-header { background-color: #343a40; color: white; }
    </style>
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="login-container">
            <div class="card">
                <div class="card-header text-center p-3">
                    <h4><i class="fas fa-user-shield"></i> &nbsp;Administrator Login</h4>
                </div>
                <div class="card-body p-5">
                    
                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="admin_login_process.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg">เข้าสู่ระบบ</button>
                        </div>
                    </form>
                    <div class="text-center mt-4">
                        <a href="index.php">กลับสู่หน้าเว็บไซต์หลัก</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>