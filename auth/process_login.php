<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_input = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($login_input) || empty($password)) {
        echo "<script>alert('Vui lòng nhập đầy đủ!'); window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $login_input, $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra khớp mật khẩu
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_role'] = $user['role'];

            // Điều hướng chuẩn quyền hạn
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../customer/book.php");
            }
            exit();
        } else {
            echo "<script>alert('Mật khẩu không chính xác!'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Tài khoản không tồn tại!'); window.history.back();</script>";
        exit();
    }
    $stmt->close();
}
$conn->close();
?>