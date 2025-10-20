<?php
// Không yêu cầu đăng nhập admin
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý đánh giá</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4 text-center">⭐ Quản lý đánh giá</h2>

    <!-- Form thêm/sửa đánh giá -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title" id="formTitle">Thêm đánh giá</h5>
        <form id="reviewForm">
          <input type="hidden" id="ma_dg">
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Người dùng</label>
              <select id="ma_nd" class="form-select" required></select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Sản phẩm</label>
              <select id="ma_sp" class="form-select" required></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Số sao (1–5)</label>
              <input type="number" id="so_sao" class="form-control" min="1" max="5" required>
            </div>
            <div class="col-md-8">
              <label class="form-label">Nội dung</label>
              <input type="text" id="noi_dung" class="form-control" placeholder="Nội dung đánh giá" required>
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" id="resetForm">Làm mới</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Bảng danh sách đánh giá -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Danh sách đánh giá</h5>
        <table class="table table-bordered table-striped">
          <thead class="table-dark text-center">
            <tr>
              <th>ID</th>
              <th>Người dùng</th>
              <th>Sản phẩm</th>
              <th>Số sao</th>
              <th>Nội dung</th>
              <th>Ngày đánh giá</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="reviewTable"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
  const apiUrl = "../../backend/api/review_api.php";
  const userApi = "../../backend/api/user_api.php";
  const productApi = "../../backend/api/product_api.php";

  // 🧩 Load dropdown người dùng & sản phẩm
  async function loadOptions() {
    const users = await (await fetch(userApi)).json();
    const products = await (await fetch(productApi)).json();

    document.getElementById("ma_nd").innerHTML =
      users.map(u => `<option value="${u.ma_nd}">${u.ho_ten}</option>`).join('');

    document.getElementById("ma_sp").innerHTML =
      products.map(p => `<option value="${p.ma_sp}">${p.ten_sp}</option>`).join('');
  }

  // 📋 Load danh sách đánh giá
  async function loadReviews() {
    const res = await fetch(apiUrl);
    const reviews = await res.json();
    const table = document.getElementById("reviewTable");
    table.innerHTML = "";
    reviews.forEach(r => {
      table.innerHTML += `
        <tr>
          <td>${r.ma_dg}</td>
          <td>${r.ten_nd ?? '—'}</td>
          <td>${r.ten_sp ?? '—'}</td>
          <td>${r.so_sao} ⭐</td>
          <td>${r.noi_dung}</td>
          <td>${r.ngay_danh_gia}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick='editReview(${JSON.stringify(r)})'>Sửa</button>
            <button class="btn btn-sm btn-danger" onclick='deleteReview(${r.ma_dg})'>Xóa</button>
          </td>
        </tr>`;
    });
  }

  // 💾 Thêm / Cập nhật đánh giá
  document.getElementById("reviewForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const soSao = parseInt(document.getElementById("so_sao").value);
    if (soSao < 1 || soSao > 5) {
      alert("Số sao phải từ 1 đến 5!");
      return;
    }

    const data = {
      ma_dg: document.getElementById("ma_dg").value,
      ma_nd: document.getElementById("ma_nd").value,
      ma_sp: document.getElementById("ma_sp").value,
      so_sao: soSao,
      noi_dung: document.getElementById("noi_dung").value
    };

    const method = data.ma_dg ? "PUT" : "POST";
    const res = await fetch(apiUrl, {
      method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.success) {
      alert("Lưu thành công!");
      loadReviews();
      e.target.reset();
      document.getElementById("formTitle").textContent = "Thêm đánh giá";
      document.getElementById("ma_dg").value = "";
    } else {
      alert("Lỗi khi lưu!");
    }
  });

  // ✏️ Sửa đánh giá
  function editReview(r) {
    document.getElementById("ma_dg").value = r.ma_dg;
    document.getElementById("ma_nd").value = r.ma_nd;
    document.getElementById("ma_sp").value = r.ma_sp;
    document.getElementById("so_sao").value = r.so_sao;
    document.getElementById("noi_dung").value = r.noi_dung;
    document.getElementById("formTitle").textContent = "Sửa đánh giá";
  }

  // 🗑️ Xóa đánh giá
  async function deleteReview(id) {
    if (!confirm("Bạn có chắc muốn xóa đánh giá này?")) return;
    const res = await fetch(apiUrl, {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ma_dg: id })
    });
    const result = await res.json();
    if (result.success) {
      alert("Đã xóa!");
      loadReviews();
    } else {
      alert("Xóa thất bại!");
    }
  }

  // 🔄 Làm mới form
  document.getElementById("resetForm").addEventListener("click", () => {
    document.getElementById("reviewForm").reset();
    document.getElementById("ma_dg").value = "";
    document.getElementById("formTitle").textContent = "Thêm đánh giá";
  });

  // 🚀 Khởi chạy khi tải trang
  loadOptions();
  loadReviews();
  </script>
</body>
</html>
