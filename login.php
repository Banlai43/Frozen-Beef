<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .card { border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">เข้าสู่ระบบ</h2>

                    <?php if(isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="login_process.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">เข้าสู่ระบบ</button>
                        </div>
                    </form>
                    <hr>
                    <p class="text-center">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิกที่นี่</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>