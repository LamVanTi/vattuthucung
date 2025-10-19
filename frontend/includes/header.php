<?php
// header.php
//session_start();  để kiểm tra đăng nhập
?>
<header style="background-color:#FFB6C1; padding:15px 30px; display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; z-index:1000;">
  <!-- Logo -->
  <div style="font-size:24px; font-weight:bold; color:#fff; cursor:pointer;">
    🐾 PetSupply
  </div>

  <!-- Navigation -->
  <nav style="display:flex; gap:25px; align-items:center;">
    <a href="index.php" style="color:#fff; text-decoration:none; font-weight:500;">Trang chủ</a>
    <a href="products.php" style="color:#fff; text-decoration:none; font-weight:500;">Sản phẩm</a>
    <a href="profile.php" style="color:#fff; text-decoration:none; font-weight:500;">Tài khoản</a>
    <a href="cart.php" style="color:#fff; text-decoration:none; font-weight:500; position:relative;">
      Giỏ hàng 🛒
      <span style="position:absolute; top:-8px; right:-12px; background:red; color:#fff; font-size:12px; padding:2px 6px; border-radius:50%;">0</span>
    </a>
  </nav>

  <!-- Search bar + Login -->
  <div style="display:flex; gap:10px; align-items:center;">
    <input type="text" placeholder="Tìm kiếm sản phẩm..." style="padding:5px 10px; border-radius:20px; border:none; outline:none; width:200px;">
    <button style="padding:5px 10px; border:none; border-radius:20px; background:#fff; color:#FF69B4; cursor:pointer;">🔍</button>

    <?php if(isset($_SESSION['user'])): ?>
      <!-- Hiển thị khi đã đăng nhập -->
      <div style="color:#fff; font-weight:500; display:flex; align-items:center; gap:10px;">
        Xin chào, <?php echo $_SESSION['user']['name']; ?>
        <a href="logout.php" style="color:#fff; text-decoration:none; font-weight:bold; padding:5px 10px; border:1px solid #fff; border-radius:20px;">Logout</a>
      </div>
    <?php else: ?>
      <!-- Hiển thị khi chưa đăng nhập -->
      <a href="login.php" style="color:#fff; text-decoration:none; font-weight:bold; padding:5px 10px; border:1px solid #fff; border-radius:20px;">Login</a>
      <a href="register.php" style="color:#FF69B4; text-decoration:none; font-weight:bold; padding:5px 10px; background:#fff; border-radius:20px;">Sign Up</a>
    <?php endif; ?>
  </div>
</header>
