# 🚗 Hệ Thống Đặt Lịch Rửa Xe Chuyên Nghiệp (Car Wash Booking System)

Hệ thống quản lý và đặt lịch rửa xe trực tuyến dành cho khách hàng và quản trị viên (Admin). Hỗ trợ đăng ký thành viên, tự động thay đổi hãng xe theo loại xe (2 bánh/4 bánh), và gửi email thông báo tự động khi hoàn thành dịch vụ.

## 🛠️ Công Nghệ Sử Dụng
* **Backend:** PHP (Thuần), MySQL Database
* **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (AJAX)
* **Thư viện:** PHPMailer (Gửi email tự động qua SMTP)

---

## 📋 Chức Năng Chính

### 1. Dành cho Khách hàng (Customer)
* Đăng ký / Đăng nhập linh hoạt bằng **Email** hoặc **Số điện thoại**.
* Quản lý hồ sơ phương tiện: Tự thêm xe cá nhân (Hệ thống tự động gợi ý hãng xe tương ứng khi chọn Xe 2 bánh hoặc Xe 4 bánh).
* Chủ động chọn ngày hẹn và khung giờ rảnh để đặt lịch.

### 2. Dành cho Quản trị viên (Admin)
* Giao diện Dashboard theo dõi toàn bộ tiến độ đặt lịch theo thời gian thực.
* Chuyển đổi trạng thái lịch hẹn: *Chờ rửa* ➔ *Đang rửa* ➔ *Đã xong* hoặc *Đủy hủy*.
* Hệ thống tự động kích hoạt gửi Email thông báo về Gmail của khách ngay khi Admin bấm **"Hoàn thành"**.

---

## 🚀 Hướng Dẫn Cài Đặt Trên Localhost (XAMPP)

### Bước 1: Tải mã nguồn về máy
Cài đặt công cụ Git và chạy lệnh sau trong thư mục `xampp/htdocs/`:
```bash
