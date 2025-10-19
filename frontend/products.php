<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách sản phẩm - PetSupply 🐾</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .product-card { border-radius: 12px; overflow: hidden; transition: 0.3s; border: none; background: #fff; box-shadow: 0px 2px 8px rgba(0,0,0,0.1); }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0px 5px 15px rgba(0,0,0,0.15); }
    .product-card img { height: 220px; object-fit: cover; border-bottom: 1px solid #eee; }
    .product-card h5 { font-size: 1.1rem; font-weight: 600; margin-bottom: 5px; }
    .price { color: #e91e63; font-weight: bold; }
    .search-bar { width: 300px; border-radius: 25px; border: 1px solid #ccc; padding: 6px 15px; outline: none; }
  </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary">Danh sách sản phẩm 🐶🐱</h2>
    <input type="text" id="searchInput" class="search-bar" placeholder="🔍 Tìm kiếm sản phẩm...">
  </div>

  <div class="row g-4" id="product-list">
    <p class="text-center text-muted">Đang tải sản phẩm...</p>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
axios.get("../backend/api/product_api.php") // hoặc "../backend/api/product_api.php"
  .then(res => {
    const products = res.data;
    const list = document.getElementById("productList");
    list.innerHTML = "";

    products.forEach(sp => {
      const card = `
        <div class="col-md-3 col-sm-6">
          <div class="card product-card">
            <img src="backend/${sp.hinh_anh}" class="product-img" alt="${sp.ten_sp}">
            <div class="product-info">
              <h6>${sp.ten_sp}</h6>
              <p class="text-muted small">${sp.mo_ta}</p>
              <p class="price">${Number(sp.gia).toLocaleString()} VNĐ</p>
              <p class="small text-secondary">Thương hiệu: ${sp.thuong_hieu}</p>
            </div>
          </div>
        </div>
      `;
      list.innerHTML += card;
    });
  })
  .catch(err => {
    document.getElementById("productList").innerHTML = 
      `<div class='text-center text-danger'>Không thể tải sản phẩm (${err.message})</div>`;
  });
</script>

</body>
</html>
