<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Mã hóa bảo mật

    // Kiểm tra trùng lặp email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email này đã được đăng ký!'); window.history.back();</script>";
        exit();
    }
    $stmt->close();

    // Chèn user mới vào CSDL với role mặc định là customer
    $insert = $conn->prepare("INSERT INTO users (fullname, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
    $insert->bind_param("ssss", $fullname, $email, $phone, $password);
    
    if ($insert->execute()) {
        echo "<script>alert('Đăng ký thành công! Hãy đăng nhập.'); window.location.href='login.php';</script>";
    } else {
        echo "Lỗi hệ thống.";
    }
    $insert->close();
}
?>