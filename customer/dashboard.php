<?php
require_once '../config/db.php';

// Kiểm tra quyền truy cập của Khách hàng
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Truy vấn lấy danh sách lịch hẹn của riêng user này (kèm thông tin xe)
$query = "SELECT b.id, b.booking_date, b.booking_time, b.status, c.license_plate, c.car_model 
          FROM bookings b 
          JOIN customer_cars c ON b.car_id = c.id 
          WHERE b.user_id = ? 
          ORDER BY b.booking_date DESC, b.booking_time DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khách Hàng - Quản Lý Lịch Hẹn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container">
            <a class="navbar-brand" href="#">Hệ Thống Rửa Xe Chuyên Nghiệp</a>
            <div class="d-flex text-white align-items-center">
                <span class="me-3">Xin chào, <strong><?php echo $_SESSION['user_name']; ?></strong></span>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lịch Sử Đặt Lịch Của Bạn</h2>
            <a href="book.php" class="btn btn-primary">+ Đặt Lịch Mới</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã Lịch</th>
                            <th>Xe / Biển Số</th>
                            <th>Ngày Hẹn</th>
                            <th>Khung Giờ</th>
                            <th>Trạng Thái Tiến Độ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings_result->num_rows > 0): ?>
                            <?php while($row = $bookings_result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td><strong><?php echo $row['license_plate']; ?></strong> (<?php echo $row['car_model']; ?>)</td>
                                    <td><?php echo date('d/m/Y', strtotime($row['booking_date'])); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $row['booking_time']; ?></span></td>
                                    <td>
                                        <?php 
                                        if ($row['status'] == 'Chờ rửa') echo '<span class="badge bg-warning text-dark">Chờ rửa</span>';
                                        elseif ($row['status'] == 'Đang rửa') echo '<span class="badge bg-primary">Đang rửa</span>';
                                        elseif ($row['status'] == 'Đã xong') echo '<span class="badge bg-success">✓ Đã xong</span>';
                                        else echo '<span class="badge bg-danger">Đã hủy</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Bạn chưa có lịch hẹn nào. Hãy bấm "Đặt Lịch Mới" để trải nghiệm dịch vụ!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>