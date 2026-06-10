<?php 
require_once 'db.php'; 

// Lấy danh sách xe có sẵn trong hệ thống để hiện thị lên Form
$cars_result = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Lịch Rửa Xe</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 30px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #007BFF; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

    <h2>ĐĂNG KÝ ĐẶT LỊCH RỬA XE</h2>
    
    <form action="process_booking.php" method="POST">
        <div class="form-group">
            <label>Email khách hàng:</label>
            <input type="email" name="customer_email" required placeholder="example@gmail.com">
        </div>

        <div class="form-group">
            <label>Chọn Xe / Biển số:</label>
            <select name="car_id" required>
                <option value="">-- Chọn xe của bạn --</option>
                <?php while($car = $cars_result->fetch_assoc()): ?>
                    <option value="<?php echo $car['id']; ?>"><?php echo $car['license_plate']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Chọn Ngày:</label>
            <input type="date" name="booking_date" required>
        </div>

        <div class="form-group">
            <label>Chọn Giờ (Chỉ chọn các khung giờ chẵn):</label>
            <select name="booking_time" required>
                <option value="08:00:00">08:00 AM</option>
                <option value="09:00:00">09:00 AM</option>
                <option value="10:00:00">10:00 AM</option>
                <option value="14:00:00">02:00 PM</option>
                <option value="15:00:00">03:00 PM</option>
                <option value="16:00:00">04:00 PM</option>
            </select>
        </div>

        <button type="submit">Xác Nhận Đặt Lịch</button>
    </form>

</body>
</html>