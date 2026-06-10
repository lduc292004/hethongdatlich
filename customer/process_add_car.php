<?php
require_once '../config/db.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $car_type = $_POST['car_type'];
    $brand_name = $_POST['brand_name'];
    $car_model = $_POST['car_model'];
    $license_plate = strtoupper($_POST['license_plate']); // Ép chữ in hoa cho biển số sạch đẹp

    $stmt = $conn->prepare("INSERT INTO customer_cars (user_id, car_type, brand_name, car_model, license_plate) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $car_type, $brand_name, $car_model, $license_plate);
    
    if ($stmt->execute()) {
        echo "<script>alert('Đã thêm phương tiện vào hồ sơ của bạn thành công!'); window.location.href='book.php';</script>";
    } else {
        echo "Lỗi không thể lưu phương tiện.";
    }
    $stmt->close();
}
?>