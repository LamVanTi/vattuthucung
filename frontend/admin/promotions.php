<?php
// Không yêu cầu đăng nhập admin
const apiUrl = "../../backend/api/discount_api.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>🎁 Quản lý khuyến mãi</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4 text-center">🎁 Quản lý khuyến mãi</h2>

    <!-- Form thêm/sửa khuyến mãi -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title" id="formTitle">Thêm khuyến mãi</h5>
        <form id="promoForm">
          <input type="hidden" id="ma_km">
          <div class="row g-2">
            <div class="col-md-4">
              <input type="text" id="ma_code" class="form-control" placeholder="Mã khuyến mãi" required>
            </div>
            <div class="col-md-4">
              <input type="number" id="phan_tram_giam" class="form-control" placeholder="Phần trăm giảm (%)" required>
            </div>
            <div class="col-md-4">
              <input type="text" id="mo_ta" class="form-control" placeholder="Mô tả">
            </div>
            <div class="col-md-6">
              <label class="form-label mt-2">Ngày bắt đầu</label>
              <input type="date" id="ngay_bat_dau" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label mt-2">Ngày kết thúc</label>
              <input type="date" id="ngay_ket_thuc" class="form-control" required>
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" id="resetForm">Làm mới</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Bảng danh sách khuyến mãi -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Danh sách khuyến mãi</h5>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Mã Code</th>
              <th>Giảm (%)</th>
              <th>Ngày bắt đầu</th>
              <th>Ngày kết thúc</th>
              <th>Mô tả</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="promoTable"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
const apiUrl = "../../backend/api/discount_api.php";

  // 🔹 Lấy danh sách khuyến mãi
  async function loadPromotions() {
    const res = await fetch(apiUrl);
    const promos = await res.json();
    const table = document.getElementById("promoTable");
    table.innerHTML = "";
    promos.forEach(p => {
      table.innerHTML += `
        <tr>
          <td>${p.ma_km}</td>
          <td>${p.ma_code}</td>
          <td>${p.phan_tram_giam}%</td>
          <td>${p.ngay_bat_dau || ""}</td>
          <td>${p.ngay_ket_thuc || ""}</td>
          <td>${p.mo_ta || ""}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick='editPromo(${JSON.stringify(p)})'>Sửa</button>
            <button class="btn btn-sm btn-danger" onclick='deletePromo(${p.ma_km})'>Xóa</button>
          </td>
        </tr>`;
    });
  }

  // 🔹 Thêm / Cập nhật khuyến mãi
  document.getElementById("promoForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const data = {
      ma_km: document.getElementById("ma_km").value,
      ma_code: document.getElementById("ma_code").value.trim(),
      phan_tram_giam: parseInt(document.getElementById("phan_tram_giam").value),
      ngay_bat_dau: document.getElementById("ngay_bat_dau").value,
      ngay_ket_thuc: document.getElementById("ngay_ket_thuc").value,
      mo_ta: document.getElementById("mo_ta").value.trim()
    };

    // ✅ Kiểm tra hợp lệ
    if (!data.ma_code) return alert("Vui lòng nhập mã khuyến mãi!");
    if (isNaN(data.phan_tram_giam) || data.phan_tram_giam <= 0 || data.phan_tram_giam > 100)
      return alert("Phần trăm giảm phải từ 1 đến 100!");
    if (!data.ngay_bat_dau || !data.ngay_ket_thuc)
      return alert("Vui lòng chọn ngày bắt đầu và kết thúc!");
    if (new Date(data.ngay_ket_thuc) < new Date(data.ngay_bat_dau))
      return alert("Ngày kết thúc phải sau ngày bắt đầu!");

    const method = data.ma_km ? "PUT" : "POST";
    const res = await fetch(apiUrl, {
      method,
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(data)
    });
    const result = await res.json();

    if (result.success) {
      alert("Lưu thành công!");
      loadPromotions();
      e.target.reset();
      document.getElementById("ma_km").value = "";
      document.getElementById("formTitle").textContent = "Thêm khuyến mãi";
    } else alert("Lỗi khi lưu!");
  });

  // 🔹 Chọn khuyến mãi để sửa
  function editPromo(p) {
    document.getElementById("ma_km").value = p.ma_km;
    document.getElementById("ma_code").value = p.ma_code;
    document.getElementById("phan_tram_giam").value = p.phan_tram_giam;
    document.getElementById("ngay_bat_dau").value = p.ngay_bat_dau;
    document.getElementById("ngay_ket_thuc").value = p.ngay_ket_thuc;
    document.getElementById("mo_ta").value = p.mo_ta;
    document.getElementById("formTitle").textContent = "Sửa khuyến mãi";
  }

  // 🔹 Xóa khuyến mãi
  async function deletePromo(id) {
    if (!confirm("Bạn có chắc muốn xóa khuyến mãi này?")) return;
    const res = await fetch(apiUrl, {
      method: "DELETE",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ ma_km: id })
    });
    const result = await res.json();
    if (result.success) {
      alert("Đã xóa!");
      loadPromotions();
    } else alert("Xóa thất bại!");
  }

  // 🔹 Reset form
  document.getElementById("resetForm").addEventListener("click", () => {
    document.getElementById("promoForm").reset();
    document.getElementById("ma_km").value = "";
    document.getElementById("formTitle").textContent = "Thêm khuyến mãi";
  });

  loadPromotions();
  </script>
</body>
</html>
