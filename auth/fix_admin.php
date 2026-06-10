<?php
require_once '../config/db.php';

// Tạo chuỗi băm chuẩn 60 ký tự trực tiếp từ hàm của PHP
$new_password = password_hash('123456', PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = 'admin@gmail.com'");
$stmt->bind_param("s", $new_password);

if ($stmt->execute()) {
    echo "<h3>CẬP NHẬT ADMIN THÀNH CÔNG!</h3>";
    echo "Chuỗi băm chuẩn vừa tạo: <code>" . $new_password . "</code> (Độ dài: " . strlen($new_password) . " ký tự)<br><br>";
    echo "Giờ bạn hãy xóa file này đi và quay lại trang <a href='login.php'>login.php</a> để đăng nhập thử nhé.";
} else {
    echo "Lỗi cập nhật CSDL: " . $conn->error;
}

$stmt->close();
$conn->close();
?>