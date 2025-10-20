<?php
// Bắt đầu session để kiểm tra quyền admin
session_start();
// ⚠️ Tạm thời bỏ kiểm tra đăng nhập để test trang admin
// Không khuyến khích trên bản chính thức

// Kết nối CSDL
include_once __DIR__ . '/../../backend/db.php';


// Đếm số lượng sản phẩm, đơn hàng, người dùng
$products = $conn->query("SELECT COUNT(*) AS total FROM san_pham")->fetch_assoc()['total'];
$orders = $conn->query("SELECT COUNT(*) AS total FROM don_hang")->fetch_assoc()['total'];
$users = $conn->query("SELECT COUNT(*) AS total FROM nguoi_dung")->fetch_assoc()['total'];
$reviews = $conn->query("SELECT COUNT(*) AS total FROM danh_gia")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Bảng điều khiển Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f6fa;
      font-family: 'Poppins', sans-serif;
    }
    .dashboard-header {
      background-color: #FFB6C1;
      color: #fff;
      padding: 20px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
    }
    .card {
      border: none;
      border-radius: 15px;
      transition: transform 0.2s;
    }
    .card:hover {
      transform: scale(1.03);
    }
    .icon {
      font-size: 36px;
      color: #FF69B4;
    }
  </style>
</head>
<body>
  <div class="dashboard-header">
    🐾 Bảng điều khiển quản trị - PetSupply
  </div>

  <div class="container mt-4">
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
          <div class="icon mb-2">📦</div>
          <h5 class="fw-bold">Sản phẩm</h5>
          <p class="text-muted fs-5"><?= $products ?></p>
          <a href="products.php" class="btn btn-outline-pink btn-sm" style="color:#FF69B4;">Xem chi tiết</a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
          <div class="icon mb-2">🛍️</div>
          <h5 class="fw-bold">Đơn hàng</h5>
          <p class="text-muted fs-5"><?= $orders ?></p>
          <a href="orders.php" class="btn btn-outline-pink btn-sm" style="color:#FF69B4;">Xem chi tiết</a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
          <div class="icon mb-2">👥</div>
          <h5 class="fw-bold">Người dùng</h5>
          <p class="text-muted fs-5"><?= $users ?></p>
          <a href="users.php" class="btn btn-outline-pink btn-sm" style="color:#FF69B4;">Xem chi tiết</a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
          <div class="icon mb-2">⭐</div>
          <h5 class="fw-bold">Đánh giá</h5>
          <p class="text-muted fs-5"><?= $reviews ?></p>
          <a href="reviews.php" class="btn btn-outline-pink btn-sm" style="color:#FF69B4;">Xem chi tiết</a>
        </div>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="promotions.php" class="btn btn-success mx-2">🎁 Quản lý khuyến mãi</a>
      <a href="logout.php" class="btn btn-danger mx-2">🚪 Đăng xuất</a>
    </div>
  </div>
</body>
</html>
