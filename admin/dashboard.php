<?php
require_once '../config/db.php';

// Bảo mật: Nếu không phải tài khoản admin thì ép về trang đăng nhập
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// 1. LOGIC XỬ LÝ ĐỔI TRẠNG THÁI (BACKEND)
if (isset($_GET['action']) && isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);
    $current_status = $_GET['action'];
    $new_status = "";

    // Thiết lập luồng trạng thái một chiều (State Machine)
    if ($current_status == 'Chờ rửa') {
        $new_status = 'Đang rửa';
    } elseif ($current_status == 'Đang rửa') {
        $new_status = 'Đã xong';
    } elseif ($current_status == 'Hủy') {
        $new_status = 'Đã hủy';
    }

    // Nếu tìm thấy trạng thái tiếp theo hợp lệ, cập nhật vào CSDL
    if (!empty($new_status)) {
        $update_stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_status, $booking_id);
        
        if ($update_stmt->execute()) {
            // NẾU TRẠNG THÁI LÀ "ĐA XONG" -> GỬI MAIL THÔNG BÁO CHO KHÁCH ĐẾN NHẬN XE
            if ($new_status == 'Đã xong') {
                require_once '../config/mail.php';
                
                // Truy vấn lại để lấy chính xác Email và Tên của khách hàng thuộc mã lịch hẹn này
                $customer_stmt = $conn->prepare("SELECT u.email, u.fullname, c.license_plate FROM bookings b JOIN users u ON b.user_id = u.id JOIN customer_cars c ON b.car_id = c.id WHERE b.id = ?");
                $customer_stmt->bind_param("i", $booking_id);
                $customer_stmt->execute();
                $customer_stmt->bind_result($custEmail, $custName, $licensePlate);
                $customer_stmt->fetch();
                $customer_stmt->close();

                $mailSubject = "=?UTF-8?B?".base64_encode("THÔNG BÁO: XE CỦA BẠN ĐA RỬA XONG!")."?=";
                $mailBody = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px;'>
                        <div style='background-color: #28a745; color: white; padding: 10px; text-align: center;'>
                            <h2>QUY TRÌNH CHĂM SÓC XE HOÀN TẤT!</h2>
                        </div>
                        <p>Chào <strong>$custName</strong>,</p>
                        <p>Trung tâm xin thông báo phương tiện có biển số <strong>$licensePlate</strong> của bạn đã được xử lý làm sạch và kiểm tra chất lượng hoàn tất.</p>
                        <p style='font-size: 16px; color: #28a745;'><strong>Trạng thái: Đã sẵn sàng bàn giao ✓</strong></p>
                        <p>Vui lòng đến quầy trung tâm để hoàn tất thủ tục nhận lại xe. Xin cảm ơn bạn đã lựa chọn dịch vụ của chúng tôi!</p>
                    </div>
                ";

                sendNotificationEmail($custEmail, $custName, $mailSubject, $mailBody);
            }
        }
        $update_stmt->close();
        
        header("Location: dashboard.php");
        exit();
    }
}

// 2. TRUY VẤN LẤY TOÀN BỘ LỊCH HẸN HỆ THỐNG
$query = "SELECT b.id as booking_id, b.booking_date, b.booking_time, b.status, 
                 u.fullname, u.email, c.license_plate, c.car_model 
          FROM bookings b
          JOIN users u ON b.user_id = u.id
          JOIN customer_cars c ON b.car_id = c.id
          ORDER BY b.booking_date ASC, b.booking_time ASC";
          
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản Lý Tiến Độ Rửa Xe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">HỆ THỐNG QUẢN TRỊ - CAR WASH</a>
            <div class="d-flex text-white align-items-center">
                <span class="me-3">Chào Admin: <strong><?php echo $_SESSION['user_name']; ?></strong></span>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 mt-4">
        <h2 class="mb-4">Bảng Quản Lý Tiến Độ Lịch Hẹn</h2>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Mã Lịch</th>
                                <th>Khách Hàng</th>
                                <th>Thông Tin Xe</th>
                                <th>Thời Gian Hẹn</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động Cập Nhật</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center fw-bold">#<?php echo $row['booking_id']; ?></td>
                                        <td>
                                            <strong><?php echo $row['fullname']; ?></strong><br>
                                            <small class="text-muted"><?php echo $row['email']; ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary fs-6"><?php echo $row['license_plate']; ?></span><br>
                                            <small><?php echo $row['car_model']; ?></small>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-primary fw-bold"><?php echo date('d/m/Y', strtotime($row['booking_date'])); ?></span><br>
                                            <span class="badge bg-info text-dark"><?php echo $row['booking_time']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php 
                                            if ($row['status'] == 'Chờ rửa') echo '<span class="badge bg-warning text-dark px-3 py-2">Chờ rửa</span>';
                                            elseif ($row['status'] == 'Đang rửa') echo '<span class="badge bg-primary px-3 py-2">Đang rửa</span>';
                                            elseif ($row['status'] == 'Đã xong') echo '<span class="badge bg-success px-3 py-2">✓ Đã xong</span>';
                                            else echo '<span class="badge bg-danger px-3 py-2">Đã hủy</span>';
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <?php if ($row['status'] == 'Chờ rửa'): ?>
                                                    <a class="btn btn-success btn-sm" href="dashboard.php?action=Chờ rửa&booking_id=<?php echo $row['booking_id']; ?>">Bắt đầu rửa</a>
                                                    <a class="btn btn-outline-danger btn-sm" href="dashboard.php?action=Hủy&booking_id=<?php echo $row['booking_id']; ?>" onclick="return confirm('Bạn có chắc muốn hủy lịch này?')">Hủy lịch</a>
                                                
                                                <?php elseif ($row['status'] == 'Đang rửa'): ?>
                                                    <a class="btn btn-info btn-sm text-white" href="dashboard.php?action=Đang rửa&booking_id=<?php echo $row['booking_id']; ?>">Hoàn thành</a>
                                                
                                                <?php else: ?>
                                                    <span class="text-muted small">Không thể thao tác</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Hiện tại chưa có lịch hẹn nào được đăng ký trên hệ thống.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>