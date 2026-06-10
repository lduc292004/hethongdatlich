<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập Hệ Thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container" style="max-width: 400px;">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h3 class="text-center fw-bold text-primary mb-4">ĐĂNG NHẬP</h3>
                
                <form action="process_login.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email hoặc Số điện thoại</label>
                        <input type="text" name="username" class="form-control" placeholder="Nhập email hoặc sđt..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2">Đăng Nhập</button>
                </form>

                <div class="text-center mt-3">
                    <small>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></small>
                </div>
            </div>
        </div>
    </div>

</body>
</html>