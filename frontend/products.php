<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>PetSupply - Sản phẩm 🐾</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
    }
    .page-header {
      background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
      color: #fff;
      text-align: center;
      padding: 80px 20px;
    }
    .page-header h1 {
      font-weight: 700;
      font-size: 2.5rem;
    }
    .product-card {
      transition: 0.3s;
      border-radius: 10px;
      overflow: hidden;
      border: none;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }
    .product-card img {
      height: 220px;
      object-fit: cover;
      background-color: #f1f1f1;
    }
    .product-card .card-body {
      padding: 15px;
    }
    .product-card .card-title {
      font-size: 1.05rem;
      font-weight: 600;
    }
    .product-card .price {
      color: #e63946;
      font-weight: 700;
    }
    [data-animate] {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.6s ease;
    }
    [data-animate].active {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<section class="page-header" data-animate>
  <h1>Danh sách sản phẩm 🛍️</h1>
  <p>Khám phá hàng trăm sản phẩm dành cho thú cưng yêu thương của bạn!</p>
</section>

<section class="container py-5">
  <div class="row g-4" id="product-list">
    <p class="text-center text-muted">Đang tải sản phẩm...</p>
  </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  // 🔸 Hiệu ứng cuộn
  const elements = document.querySelectorAll('[data-animate]');
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add('active');
    });
  }, { threshold: 0.2 });
  elements.forEach(el => observer.observe(el));

  // 🔹 Lấy danh sách sản phẩm từ backend
  axios.get('../backend/api/product_api.php')
    .then(res => {
      console.log("📦 Dữ liệu từ API:", res.data);
      const data = res.data;
      const productList = document.getElementById('product-list');

      if (!Array.isArray(data) || data.length === 0) {
        productList.innerHTML = `<p class="text-center text-muted">Không có sản phẩm nào để hiển thị.</p>`;
        return;
      }

      productList.innerHTML = data.map(sp => {
        let imgPath = sp.hinh_anh;

        // Nếu chỉ là tên file -> thêm đường dẫn chuẩn
        if (imgPath && !imgPath.includes('assets/uploads/')) {
          imgPath = `assets/uploads/${imgPath}`;
        }

        // Nếu không có hình -> dùng ảnh mặc định
        if (!imgPath) imgPath = 'assets/uploads/no_image.png';

        return `
        <div class="col-lg-3 col-md-4 col-sm-6" data-animate>
          <div class="card product-card h-100 shadow-sm">
            <img src="${imgPath}" alt="${sp.ten_sp}" class="card-img-top" 
                 onerror="this.src='assets/uploads/no_image.png'">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-truncate">${sp.ten_sp}</h5>
              <p class="price mb-3">${Number(sp.gia).toLocaleString()} đ</p>
              <a href="product_detail.php?id=${sp.ma_sp}" class="btn btn-primary mt-auto">
                <i class="fa-solid fa-bag-shopping me-1"></i> Xem chi tiết
              </a>
            </div>
          </div>
        </div>`;
      }).join('');
    })
    .catch(err => {
      console.error("❌ Lỗi tải sản phẩm:", err);
      document.getElementById('product-list').innerHTML =
        `<p class="text-danger text-center">Không thể tải sản phẩm. Vui lòng thử lại sau.</p>`;
    });
});
</script>

</body>
</html>
