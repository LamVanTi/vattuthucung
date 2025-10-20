<?php
const apiUrl = "../../backend/api/product_api.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>📦 Quản lý sản phẩm</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="text-center mb-4">📦 Quản lý sản phẩm</h2>

    <!-- Form thêm / sửa sản phẩm -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 id="formTitle" class="card-title">Thêm sản phẩm</h5>
        <form id="productForm">
          <input type="hidden" id="ma_sp">
          <div class="row g-2">
            <div class="col-md-6">
              <input type="text" id="ten_sp" class="form-control" placeholder="Tên sản phẩm" required>
            </div>
            <div class="col-md-6">
              <input type="text" id="thuong_hieu" class="form-control" placeholder="Thương hiệu">
            </div>
            <div class="col-md-6">
              <input type="number" id="gia" class="form-control" placeholder="Giá" required>
            </div>
            <div class="col-md-6">
              <input type="number" id="so_luong" class="form-control" placeholder="Số lượng" required>
            </div>
            <div class="col-md-6">
              <input type="text" id="loai_sp" class="form-control" placeholder="Loại sản phẩm">
            </div>
            <div class="col-md-6">
              <input type="text" id="hinh_anh" class="form-control" placeholder="Tên file hình ảnh (VD: cat1.jpg)">
            </div>
            <div class="col-md-12">
              <textarea id="mo_ta" class="form-control" placeholder="Mô tả sản phẩm"></textarea>
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" id="resetForm" class="btn btn-secondary">Làm mới</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Danh sách sản phẩm</h5>
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Tên sản phẩm</th>
              <th>Giá (₫)</th>
              <th>Số lượng</th>
              <th>Loại</th>
              <th>Thương hiệu</th>
              <th>Hình ảnh</th>
              <th>Ngày tạo</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="productTable"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
  const apiUrl = "../../backend/api/product_api.php";

  // Lấy danh sách sản phẩm
  async function loadProducts() {
    const res = await fetch(apiUrl);
    const products = await res.json();
    const table = document.getElementById("productTable");
    table.innerHTML = "";
    products.forEach(p => {
      table.innerHTML += `
        <tr>
          <td>${p.ma_sp}</td>
          <td>${p.ten_sp}</td>
          <td>${Number(p.gia).toLocaleString()}₫</td>
          <td>${p.so_luong}</td>
          <td>${p.loai_sp || ""}</td>
          <td>${p.thuong_hieu || ""}</td>
          <td><img src="../../backend/images/${p.hinh_anh || 'no_image.png'}" width="50" height="50"></td>
          <td>${p.ngay_tao || ""}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick='editProduct(${JSON.stringify(p)})'>Sửa</button>
            <button class="btn btn-sm btn-danger" onclick='deleteProduct(${p.ma_sp})'>Xóa</button>
          </td>
        </tr>`;
    });
  }

  // Thêm / Cập nhật sản phẩm
  document.getElementById("productForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
      ma_sp: document.getElementById("ma_sp").value,
      ten_sp: document.getElementById("ten_sp").value,
      mo_ta: document.getElementById("mo_ta").value,
      gia: document.getElementById("gia").value,
      so_luong: document.getElementById("so_luong").value,
      loai_sp: document.getElementById("loai_sp").value,
      thuong_hieu: document.getElementById("thuong_hieu").value,
      hinh_anh: document.getElementById("hinh_anh").value
    };
    const method = data.ma_sp ? "PUT" : "POST";
    const res = await fetch(apiUrl, {
      method,
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.success) {
      alert("Lưu thành công!");
      loadProducts();
      e.target.reset();
      document.getElementById("ma_sp").value = "";
      document.getElementById("formTitle").textContent = "Thêm sản phẩm";
    } else alert("Lỗi khi lưu!");
  });

  // Sửa sản phẩm
  function editProduct(p) {
    document.getElementById("ma_sp").value = p.ma_sp;
    document.getElementById("ten_sp").value = p.ten_sp;
    document.getElementById("mo_ta").value = p.mo_ta;
    document.getElementById("gia").value = p.gia;
    document.getElementById("so_luong").value = p.so_luong;
    document.getElementById("loai_sp").value = p.loai_sp;
    document.getElementById("thuong_hieu").value = p.thuong_hieu;
    document.getElementById("hinh_anh").value = p.hinh_anh;
    document.getElementById("formTitle").textContent = "Sửa sản phẩm";
  }

  // Xóa sản phẩm
  async function deleteProduct(id) {
    if (!confirm("Bạn có chắc muốn xóa sản phẩm này?")) return;
    const res = await fetch(apiUrl, {
      method: "DELETE",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ ma_sp: id })
    });
    const result = await res.json();
    if (result.success) {
      alert("Đã xóa!");
      loadProducts();
    } else alert("Xóa thất bại!");
  }

  // Reset form
  document.getElementById("resetForm").addEventListener("click", () => {
    document.getElementById("productForm").reset();
    document.getElementById("ma_sp").value = "";
    document.getElementById("formTitle").textContent = "Thêm sản phẩm";
  });

  loadProducts();
  </script>
</body>
</html>
