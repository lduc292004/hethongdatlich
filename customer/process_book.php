<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy thông tin từ session đăng nhập và form gửi lên
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];

    // ALGORITHM KIỂM TRA TRÙNG LỊCH:
    // Kiểm tra xem khung giờ này ($date + $time) của chiếc xe này ($car_id) đã bị trùng chưa
    $check_query = "SELECT COUNT(*) FROM bookings WHERE car_id = ? AND booking_date = ? AND booking_time = ? AND status != 'Đã hủy'";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("iss", $car_id, $date, $time);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // NẾU TRÙNG: Đưa ra thông báo lỗi và giữ khách ở lại trang để chọn lại
        echo "<script>alert('LỖI TRÙNG LỊCH: Xe của bạn đã được đăng ký khung giờ này vào ngày " . date('d/m/Y', strtotime($date)) . " rồi! Vui lòng chọn khung giờ khác.'); window.history.back();</script>";
        exit();
    } else {
        // NẾU HỢP LỆ: Tiến hành tạo bản ghi lịch hẹn mới
        $insert_query = "INSERT INTO bookings (user_id, car_id, booking_date, booking_time, status) VALUES (?, ?, ?, ?, 'Chờ rửa')";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iiss", $user_id, $car_id, $date, $time);
        
        if ($insert_stmt->execute()) {
            
            // TÍCH HỢP HÀM GỬI EMAIL THÔNG BÁO TỰ ĐỘNG Ở ĐÂY
            // (Tạm thời dùng hàm ẩn để tránh lỗi hệ thống, bước sau ta tích hợp PHPMailer nâng cao)
            $subject = "Xac nhan dat lich thanh cong";
            $message = "He thong da ghi nhan lich hen cua ban vao luc $time ngay $date. Cam on ban!";
            @mail($_SESSION['user_email'], $subject, $message);

            echo "<script>alert('Chúc mừng! Bạn đã đăng ký đặt lịch rửa xe thành công.'); window.location.href='dashboard.php';</script>";
        } else {
            echo "Lỗi hệ thống: Không thể lưu lịch hẹn vào cơ sở dữ liệu.";
        }
        $insert_stmt->close();
    }
}
?>