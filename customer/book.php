<?php
require_once '../config/db.php';

// Khởi động session để nhận diện khách hàng đang đăng nhập
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra bảo mật phân quyền
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// TRUY VẤN ĐỘNG: Lấy danh sách xe thuộc sở hữu của RIÊNG khách hàng này, kèm theo các cột thông tin mới
$stmt = $conn->prepare("SELECT id, car_type, brand_name, car_model, license_plate FROM customer_cars WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cars_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Lịch Hẹn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0 fw-bold">ĐĂNG KÝ LỊCH RỬA XE CHUYÊN NGHIỆP</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="process_book.php" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn xe trong hồ sơ của bạn:</label>
                                <select name="car_id" class="form-select" required>
                                    <option value="">-- Click để chọn xe của bạn --</option>
                                    <?php if ($cars_result && $cars_result->num_rows > 0): ?>
                                        <?php while($car = $cars_result->fetch_assoc()): ?>
                                            <option value="<?php echo $car['id']; ?>">
                                                <?php echo "[" . $car['car_type'] . "] " . $car['brand_name'] . " " . $car['car_model'] . " - Biển số: " . $car['license_plate']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Hồ sơ của bạn chưa có xe nào...</option>
                                    <?php endif; ?>
                                </select>
                                
                                <div class="text-end mt-2">
                                    <a href="add_car.php" class="text-decoration-none small fw-bold text-success">+ Bạn muốn thêm xe mới vào hồ sơ?</a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn Ngày Hẹn:</label>
                                <input type="date" name="booking_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Chọn Khung Giờ Rảnh:</label>
                                <select name="booking_time" class="form-select" required>
                                    <option value="08:00:00">08:00 AM (Sáng)</option>
                                    <option value="09:30:00">09:30 AM (Sáng)</option>
                                    <option value="11:00:00">11:00 AM (Trưa)</option>
                                    <option value="13:30:00">13:30 PM (Chiều)</option>
                                    <option value="15:00:00">15:00 PM (Chiều)</option>
                                    <option value="16:30:00">16:30 PM (Chiều)</option>
                                </select>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="dashboard.php" class="btn btn-secondary w-50">Quay lại</a>
                                <button type="submit" class="btn btn-success w-50 fw-bold">Xác Nhận Đặt Ngay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<?php 
$stmt->close(); 
?>