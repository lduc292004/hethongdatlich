<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký Tài Khoản CarWash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container" style="max-width: 450px;">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h3 class="text-center fw-bold text-primary mb-4">ĐĂNG KÝ THÀNH VIÊN</h3>
                <form action="process_register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và Tên</label>
                        <input type="text" name="fullname" class="form-select" placeholder="Nguyễn Văn A" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Địa chỉ Email</label>
                        <input type="email" name="email" class="form-select" placeholder="name@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="phone" class="form-select" placeholder="09xxxxxxx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mật khẩu</label>
                        <input type="password" name="password" class="form-select" placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2">Đăng Ký Ngay</button>
                </form>
                <div class="text-center mt-3">
                    <small>Đã có tài khoản? <a href="login.php">Đăng nhập</a></small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>