<?php
session_start();
include '../backend/db.php';

// khởi tạo cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// hành động từ form
$action = $_REQUEST['action'] ?? '';
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ma_sp = intval($_POST['ma_sp'] ?? 0);
    $qty = max(1, intval($_POST['qty'] ?? 1));
    if ($ma_sp > 0) {
        if (isset($_SESSION['cart'][$ma_sp])) $_SESSION['cart'][$ma_sp] += $qty;
        else $_SESSION['cart'][$ma_sp] = $qty;
    }
    header('Location: cart.php');
    exit;
}
if ($action === 'remove') {
    $ma_sp = intval($_GET['ma_sp'] ?? 0);
    if ($ma_sp && isset($_SESSION['cart'][$ma_sp])) {
        unset($_SESSION['cart'][$ma_sp]);
    }
    header('Location: cart.php');
    exit;
}
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] as $ma_sp => $q) {
        $ma_sp = intval($ma_sp);
        $q = max(0, intval($q));
        if ($q === 0) {
            unset($_SESSION['cart'][$ma_sp]);
        } else {
            $_SESSION['cart'][$ma_sp] = $q;
        }
    }
    header('Location: cart.php');
    exit;
}

// Lấy thông tin sản phẩm trong giỏ từ DB
$cartItems = [];
if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $types = str_repeat('i', count($_SESSION['cart']));
    $stmt = $conn->prepare("SELECT ma_sp, ten_sp, gia, hinh_anh, so_luong FROM san_pham WHERE ma_sp IN ($placeholders)");
    $i = 0;
    $vals = [];
    foreach ($_SESSION['cart'] as $k => $v) { $vals[] = $k; }
    // bind_param dynamic
    $stmt->bind_param($types, ...$vals);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $row['qty'] = $_SESSION['cart'][$row['ma_sp']];
        $cartItems[] = $row;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Giỏ hàng - PetSupply</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <h3>Giỏ hàng</h3>

  <?php if (empty($cartItems)): ?>
    <div class="alert alert-info">Giỏ hàng rỗng. <a href="products.php">Tiếp tục mua sắm</a></div>
  <?php else: ?>
    <form method="post" action="cart.php?action=update">
      <table class="table">
        <thead>
          <tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Thành tiền</th><th></th></tr>
        </thead>
        <tbody>
          <?php $total = 0; foreach ($cartItems as $item): 
            $subtotal = $item['gia'] * $item['qty'];
            $total += $subtotal;
            $img = !empty($item['hinh_anh']) ? '../backend/'.$item['hinh_anh'] : 'assets/no-image.png';
          ?>
          <tr>
            <td>
              <img src="<?php echo $img; ?>" style="width:60px; height:60px; object-fit:cover;" alt="">
              <?php echo htmlspecialchars($item['ten_sp']); ?>
            </td>
            <td><?php echo number_format($item['gia']); ?> đ</td>
            <td style="width:120px;">
              <input type="number" name="qty[<?php echo $item['ma_sp']; ?>]" value="<?php echo $item['qty']; ?>" min="0" class="form-control">
            </td>
            <td><?php echo number_format($subtotal); ?> đ</td>
            <td><a href="cart.php?action=remove&ma_sp=<?php echo $item['ma_sp']; ?>" class="btn btn-sm btn-danger">Xóa</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center">
        <div>
          <button class="btn btn-primary">Cập nhật giỏ</button>
          <a href="products.php" class="btn btn-link">Tiếp tục mua sắm</a>
        </div>
        <div>
          <h4>Tổng: <?php echo number_format($total); ?> đ</h4>
          <a href="checkout.php" class="btn btn-success">Thanh toán</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
