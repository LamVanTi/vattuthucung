<?php
$servername = "localhost";   // Tên máy chủ MySQL
$username = "root";          // Tên đăng nhập MySQL
$password = "";              // Mật khẩu (để trống nếu dùng XAMPP mặc định)
$database = "vattuthucung";  // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập bảng mã UTF-8
$conn->set_charset("utf8");

?>
