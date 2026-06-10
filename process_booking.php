<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['customer_email'];
    $car_id = $_POST['car_id'];
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];

    // BƯỚC 1: KIỂM TRA XEM KHUNG GIỜ CỦA XE NÀY ĐÃ BỊ ĐẶT CHƯA
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE car_id = ? AND booking_date = ? AND booking_time = ?");
    $check_stmt->bind_param("iss", $car_id, $date, $time);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // Nếu tìm thấy kết quả trùng, báo lỗi ngay lập tức và quay lại trang trước
        echo "<script>alert('LỖI: Khung giờ này của xe đã có người đặt! Vui lòng chọn giờ khác.'); window.history.back();</script>";
        exit();
    } else {
        // BƯỚC 2: TIẾN HÀNH LƯU VÀO CSDL
        $insert_stmt = $conn->prepare("INSERT INTO bookings (customer_email, car_id, booking_date, booking_time) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("siss", $email, $car_id, $date, $time);
        
        if ($insert_stmt->execute()) {
            // BƯỚC 3: GỬI MAIL TỰ ĐỘNG (Sử dụng hàm mail mặc định)
            $subject = "Xac nhan lich hen rua xe tu dong";
            $message = "Cam on ban da dat lich. Lich hen cua ban vao luc: $time ngay $date.";
            $headers = "From: no-reply@carwash.com";
            
            // Hàm @ dùng để ẩn cảnh báo nếu máy bạn chưa cấu hình SMTP mail server thành công
            @mail($email, $subject, $message, $headers);

            echo "<script>alert('Đặt lịch thành công! Email thông báo đã gửi.'); window.location.href='booking.php';</script>";
        } else {
            echo "Đã xảy ra lỗi hệ thống khi lưu lịch.";
        }
        $insert_stmt->close();
    }
}
?>