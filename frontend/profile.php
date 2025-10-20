<?php
session_start();
include '../backend/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$ma_nd = $_SESSION['user']['ma_nd'];
$err = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = trim($_POST['ho_ten'] ?? '');
    $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
    $dia_chi = trim($_POST['dia_chi'] ?? '');
    $mat_khau = trim($_POST['mat_khau'] ?? '');

    $sql = "UPDATE nguoi_dung SET ho_ten = ?, so_dien_thoai = ?, dia_chi = ?";
    $params = [$ho_ten, $so_dien_thoai, $dia_chi];
    $types = "sss";

    if ($mat_khau !== '') {
        $sql .= ", mat_khau = ?";
        $types .= "s";
        $params[] = $mat_khau;
    }
    $sql .= " WHERE ma_nd = ?";
    $types .= "i";
    $params[] = $ma_nd;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        $success = 'Cập nhật thành công.';
        // refresh session data
        $stmt2 = $conn->prepare("SELECT ho_ten, email, so_dien_thoai, dia_chi FROM nguoi_dung WHERE ma_nd = ?");
        $stmt2->bind_param("i", $ma_nd);
        $stmt2->execute();
        $r = $stmt2->get_result()->fetch_assoc();
        $_SESSION['user']['ho_ten'] = $r['ho_ten'];
        $_SESSION['user']['so_dien_thoai'] = $r['so_dien_thoai'];
        $_SESSION['user']['dia_chi'] = $r['dia_chi'];
    } else {
        $err = 'Cập nhật thất bại.';
    }
}

// lấy thông tin user
$stmt = $conn->prepare("SELECT ho_ten, email, so_dien_thoai, dia_chi FROM nguoi_dung WHERE ma_nd = ?");
$stmt->bind_param("i", $ma_nd);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Hồ sơ - PetSupply</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>
<div class="container py-5">
  <h3>Thông tin tài khoản</h3>
  <?php if($err) echo "<div class='alert alert-danger'>".htmlspecialchars($err)."</div>"; ?>
  <?php if($success) echo "<div class='alert alert-success'>".htmlspecialchars($success)."</div>"; ?>

  <form method="post" action="">
    <div class="mb-3">
      <label>Họ và tên</label>
      <input type="text" name="ho_ten" class="form-control" value="<?php echo htmlspecialchars($user['ho_ten']); ?>">
    </div>
    <div class="mb-3">
      <label>Email (không thể thay đổi)</label>
      <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
    </div>
    <div class="mb-3">
      <label>Số điện thoại</label>
      <input type="text" name="so_dien_thoai" class="form-control" value="<?php echo htmlspecialchars($user['so_dien_thoai']); ?>">
    </div>
    <div class="mb-3">
      <label>Địa chỉ</label>
      <input type="text" name="dia_chi" class="form-control" value="<?php echo htmlspecialchars($user['dia_chi']); ?>">
    </div>
    <div class="mb-3">
      <label>Đổi mật khẩu (để trống nếu không thay đổi)</label>
      <input type="password" name="mat_khau" class="form-control">
    </div>
    <button class="btn btn-primary">Cập nhật</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
