<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Phương Tiện Của Bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Hàm JavaScript tự động thay đổi Hãng xe dựa trên Loại xe được chọn
        function updateBrands() {
            var carType = document.getElementById("car_type").value;
            var brandSelect = document.getElementById("brand_name");
            
            // Xóa sạch các tùy chọn cũ
            brandSelect.innerHTML = "";

            if (carType === "Xe 2 bánh") {
                var brands = ["Honda", "Yamaha", "Suzuki", "Piaggio", "VinFast (Điện)"];
            } else if (carType === "Xe 4 bánh") {
                var brands = ["Toyota", "Honda", "Hyundai", "Kia", "Mazda", "Ford", "VinFast", "Mercedes-Benz"];
            } else {
                var brands = ["-- Vui lòng chọn loại xe trước --"];
            }

            brands.forEach(function(brand) {
                var option = document.createElement("option");
                option.value = brand;
                option.text = brand;
                brandSelect.appendChild(option);
            });
        }
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5" style="max-width: 500px;">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center fw-bold">
                THÊM PHƯƠNG TIỆN MỚI TRONG HỒ SƠ
            </div>
            <div class="card-body p-4">
                <form action="process_add_car.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phân Loại Xe</label>
                        <select name="car_type" id="car_type" class="form-select" onchange="updateBrands()" required>
                            <option value="">-- Chọn loại xe --</option>
                            <option value="Xe 2 bánh">Xe 2 bánh (Xe máy/Mô tô)</option>
                            <option value="Xe 4 bánh">Xe 4 bánh (Ô tô/Bán tải)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hãng Sản Xuất</label>
                        <select name="brand_name" id="brand_name" class="form-select" required>
                            <option value="">-- Vui lòng chọn loại xe trước --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Dòng Xe (Model)</label>
                        <input type="text" name="car_model" class="form-select" placeholder="Ví dụ: Vision, Wave, Vios, Accent..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Biển Số Xe</label>
                        <input type="text" name="license_plate" class="form-select" placeholder="Ví dụ: 51G-123.45" required>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="dashboard.php" class="btn btn-secondary w-50">Quay lại</a>
                        <button type="submit" class="btn btn-success w-50 fw-bold">Lưu Xe Vào Hồ Sơ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>