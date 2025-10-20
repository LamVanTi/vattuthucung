<?php
session_start();
include '../backend/db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Lấy thông tin cart giống như cart.php
$cartItems = [];
$placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
$types = str_repeat('i', count($_SESSION['cart']));
$stmt = $conn->prepare("SELECT ma_sp, ten_sp, gia, hinh_anh, so_luong FROM san_pham WHERE ma_sp IN ($placeholders)");
$vals = [];
foreach ($_SESSION['cart'] as $k => $v) $vals[] = $k;
$stmt->bind_param($types, ...$vals);
$stmt->execute();
$res = $stmt->get_result();
$total = 0;
while ($row = $res->fetch_assoc()) {
    $row['qty'] = $_SESSION['cart'][$row['ma_sp']];
    $row['subtotal'] = $row['gia'] * $row['qty'];
    $total += $row['subtotal'];
    $cartItems[] = $row;
}

// xử lý đặt hàng
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_nd = $_SESSION['user']['ma_nd'] ?? null;
    $tong_tien = $total;
    $trang_thai = 'cho_xac_nhan';

    // bắt đầu transaction
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO don_hang (ma_nd, tong_tien, trang_thai) VALUES (?, ?, ?)");
        if ($ma_nd) $stmt->bind_param("ids", $ma_nd, $tong_tien, $trang_thai);
        else $stmt->bind_param("ids", $ma_nd, $tong_tien, $trang_thai); // ma_nd null allowed
        $stmt->execute();
        $ma_dh = $conn->insert_id;

        // insert chi tiết
        $stmt_ct = $conn->prepare("INSERT INTO chi_tiet_don_hang (ma_dh, ma_sp, so_luong, gia) VALUES (?, ?, ?, ?)");
        $update_stmt = $conn->prepare("UPDATE san_pham SET so_luong = so_luong - ? WHERE ma_sp = ?");

        foreach ($cartItems as $it) {
            $stmt_ct->bind_param("iiid", $ma_dh, $it['ma_sp'], $it['qty'], $it['gia']);
            $stmt_ct->execute();

            // cập nhật tồn kho
            $update_stmt->bind_param("ii", $it['qty'], $it['ma_sp']);
            $update_stmt->execute();
        }

        $conn->commit();
        // xóa giỏ
        unset($_SESSION['cart']);
        header('Location: index.php?order=success');
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $err = 'Đặt hàng thất bại: ' . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Thanh toán - PetSupply</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <h3>Thanh toán</h3>
  <?php if ($err) echo "<div class='alert alert-danger'>".htmlspecialchars($err)."</div>"; ?>

  <div class="row">
    <div class="col-md-8">
      <table class="table">
        <thead><tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Thành tiền</th></tr></thead>
        <tbody>
          <?php foreach ($cartItems as $it): ?>
          <tr>
            <td><?php echo htmlspecialchars($it['ten_sp']); ?></td>
            <td><?php echo number_format($it['gia']); ?> đ</td>
            <td><?php echo (int)$it['qty']; ?></td>
            <td><?php echo number_format($it['subtotal']); ?> đ</td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <h4>Tổng: <?php echo number_format($total); ?> đ</h4>
    </div>
    <div class="col-md-4">
      <h5>Thông tin thanh toán</h5>
      <form method="post" action="">
        <div class="mb-3">
          <label>Người nhận</label>
          <input type="text" class="form-control" name="nguoi_nhan" value="<?php echo htmlspecialchars($_SESSION['user']['ho_ten'] ?? ''); ?>">
        </div>
        <div class="mb-3">
          <label>Địa chỉ</label>
          <input type="text" class="form-control" name="dia_chi" value="<?php echo htmlspecialchars($_SESSION['user']['dia_chi'] ?? ''); ?>">
        </div>
        <div class="mb-3">
          <label>Số điện thoại</label>
          <input type="text" class="form-control" name="so_dt" value="<?php echo htmlspecialchars($_SESSION['user']['so_dien_thoai'] ?? ''); ?>">
        </div>
        <button class="btn btn-success w-100">Xác nhận đặt hàng</button>
      </form>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
