<?php
// Nhúng các file thư viện cốt lõi vào hệ thống
require_once __DIR__ . '/../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendNotificationEmail($toEmail, $toName, $subject, $bodyContent) {
    $mail = new PHPMailer(true);

    try {
        // Cấu hình kết nối máy chủ Mail Server (Ở đây cấu hình mẫu bằng Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';                     // Máy chủ SMTP của Gmail
        $mail->SMTPAuth   = true;                                 // Bật tính năng xác thực
        $mail->Username   = 'email_cua_tiem@gmail.com';           // Tên tài khoản Gmail của tiệm
        $mail->Password   = 'abcd efgh ijkl mnop';               // Mật khẩu ứng dụng Gmail (App Password)
        $mail->SMTPSecure = 'tls';      // Cơ chế mã hóa bảo mật
        $mail->Port       = 587;                                  // Cổng kết nối TCP

        // Thông tin người gửi & người nhận
        $mail->setFrom('email_cua_tiem@gmail.com', 'Trung Tam Rua Xe CarWash');
        $mail->addAddress($toEmail, $toName);

        // Thiết lập nội dung Email dạng giao diện HTML chuẩn
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // Đảm bảo không bị lỗi font tiếng Việt
        $mail->Subject = $subject;
        $mail->Body    = $bodyContent;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Ghi lại log lỗi nếu gửi thất bại mà không làm sập ứng dụng web
        error_log("Không thể gửi mail. Lỗi hệ thống: {$mail->ErrorInfo}");
        return false;
    }
}
?>