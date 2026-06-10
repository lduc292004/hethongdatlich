<?php
require_once 'db.php';

// XỬ LÝ CẬP NHẬT TRẠNG THÁI THEO VÒNG ĐỜI
if (isset($_GET['action']) && isset($_GET['car_id'])) {
    $car_id = intval($_GET['car_id']);
    $current_status = $_GET['action'];
    $new_status = "";

    // Thiết lập State Machine: Chờ rửa -> Đang rửa -> Đã xong
    if ($current_status == 'Chờ rửa') {
        $new_status = 'Đang rửa';
    } elseif ($current_status == 'Đang rửa') {
        $new_status = 'Đã xong';
    }

    if (!empty($new_status)) {
        $update_stmt = $conn->prepare("UPDATE cars SET status = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_status, $car_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Chuyển hướng lại trang admin để cập nhật giao diện mới
        header("Location: admin.php");
        exit();
    }
}

// LẤY DANH SÁCH TẤT CẢ XE ĐỂ HIỂN THỊ
$result = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Quản Lý Trạng Thái</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 6px 12px; text-decoration: none; color: white; border-radius: 4px; font-size: 14px; }
        .btn-process { background-color: #28a745; }
        .btn-done { background-color: #17a2b8; }
        .status-finish { color: #6c757d; font-style: italic; }
    </style>
</head>
<body>

    <h2>HỆ THỐNG QUẢN LÝ TIẾN ĐỘ RỬA XE (ADMIN)</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Biển Số Xe</th>
                <th>Trạng Thái Hiện Tại</th>
                <th>Hành Động Cập Nhật</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><strong><?php echo $row['license_plate']; ?></strong></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <?php if ($row['status'] == 'Chờ rửa'): ?>
                        <a class="btn btn-process" href="admin.php?action=Chờ rửa&car_id=<?php echo $row['id']; ?>">Bắt đầu rửa (→ Đang rửa)</a>
                    <?php elseif ($row['status'] == 'Đang rửa'): ?>
                        <a class="btn btn-done" href="admin.php?action=Đang rửa&car_id=<?php echo $row['id']; ?>">Hoàn thành (→ Đã xong)</a>
                    <?php else: ?>
                        <span class="status-finish">✓ Quy trình kết thúc</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>