<?php
session_start();
include '../backend/db.php';

$ma_sp = intval($_GET['id'] ?? 0);
if ($ma_sp <= 0) {
    header('Location: products.php');
    exit;
}

$stmt = $conn->prepare("SELECT ma_sp, ten_sp, mo_ta, gia, so_luong, loai_sp, thuong_hieu, hinh_anh FROM san_pham WHERE ma_sp = ?");
$stmt->bind_param("i", $ma_sp);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    echo "Sản phẩm không tồn tại.";
    exit;
}
$sp = $res->fetch_assoc();
$sp['hinh_anh'] = !empty($sp['hinh_anh']) ? '../backend/' . $sp['hinh_anh'] : 'assets/no-image.png';
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars($sp['ten_sp']); ?> - PetSupply</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <div class="row">
    <div class="col-md-5">
      <img src="<?php echo htmlspecialchars($sp['hinh_anh']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($sp['ten_sp']); ?>">
    </div>
    <div class="col-md-7">
      <h2><?php echo htmlspecialchars($sp['ten_sp']); ?></h2>
      <p class="text-muted">Thương hiệu: <?php echo htmlspecialchars($sp['thuong_hieu']); ?></p>
      <h4 class="text-danger"><?php echo number_format($sp['gia']); ?> đ</h4>
      <p><?php echo nl2br(htmlspecialchars($sp['mo_ta'])); ?></p>
      <p>Số lượng còn: <?php echo (int)$sp['so_luong']; ?></p>

      <form method="post" action="cart.php">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="ma_sp" value="<?php echo $sp['ma_sp']; ?>">
        <div class="mb-3 w-25">
          <label>Số lượng</label>
          <input type="number" name="qty" value="1" min="1" max="<?php echo (int)$sp['so_luong']; ?>" class="form-control">
        </div>
        <button class="btn btn-primary">Thêm vào giỏ</button>
      </form>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
