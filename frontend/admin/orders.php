<?php
const apiUrl = "../../backend/api/order_api.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>📦 Quản lý đơn hàng</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="text-center mb-4">📦 Quản lý đơn hàng</h2>

    <!-- Form thêm / sửa đơn hàng -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 id="formTitle" class="card-title">Thêm đơn hàng</h5>
        <form id="orderForm">
          <input type="hidden" id="ma_dh">
          <div class="row g-3">
            <div class="col-md-6">
              <input type="number" id="ma_nd" class="form-control" placeholder="Mã người dùng" required>
            </div>
            <div class="col-md-6">
              <input type="number" id="tong_tien" class="form-control" placeholder="Tổng tiền" required>
            </div>
            <div class="col-md-6">
              <select id="trang_thai" class="form-select" required>
                <option value="">-- Chọn trạng thái --</option>
                <option value="cho_xac_nhan">🕓 Chờ xác nhận</option>
                <option value="dang_giao">🚚 Đang giao</option>
                <option value="hoan_tat">✅ Hoàn tất</option>
                <option value="huy">❌ Hủy</option>
              </select>
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" id="resetForm" class="btn btn-secondary">Làm mới</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Danh sách đơn hàng -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Danh sách đơn hàng</h5>
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Mã ĐH</th>
              <th>Mã ND</th>
              <th>Tổng tiền (₫)</th>
              <th>Trạng thái</th>
              <th>Ngày đặt</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="orderTable"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
  const apiUrl = "../../backend/api/order_api.php";

  // Hiển thị danh sách đơn hàng
  async function loadOrders() {
    const res = await fetch(apiUrl);
    const orders = await res.json();
    const table = document.getElementById("orderTable");
    table.innerHTML = "";

    orders.forEach(o => {
      let badge = "";
      switch (o.trang_thai) {
        case "cho_xac_nhan": badge = '<span class="badge bg-warning text-dark">Chờ xác nhận</span>'; break;
        case "dang_giao": badge = '<span class="badge bg-info text-dark">Đang giao</span>'; break;
        case "hoan_tat": badge = '<span class="badge bg-success">Hoàn tất</span>'; break;
        case "huy": badge = '<span class="badge bg-danger">Đã hủy</span>'; break;
        default: badge = o.trang_thai;
      }

      table.innerHTML += `
        <tr>
          <td>${o.ma_dh}</td>
          <td>${o.ma_nd}</td>
          <td>${Number(o.tong_tien).toLocaleString()}₫</td>
          <td>${badge}</td>
          <td>${o.ngay_dat || ""}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick='editOrder(${JSON.stringify(o)})'>Sửa</button>
            <button class="btn btn-sm btn-danger" onclick='deleteOrder(${o.ma_dh})'>Xóa</button>
          </td>
        </tr>`;
    });
  }

  // Thêm / cập nhật đơn hàng
  document.getElementById("orderForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
      ma_dh: document.getElementById("ma_dh").value,
      ma_nd: document.getElementById("ma_nd").value,
      tong_tien: document.getElementById("tong_tien").value,
      trang_thai: document.getElementById("trang_thai").value
    };
    const method = data.ma_dh ? "PUT" : "POST";

    const res = await fetch(apiUrl, {
      method,
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(data)
    });
    const result = await res.json();

    if (result.success) {
      alert("Lưu thành công!");
      loadOrders();
      e.target.reset();
      document.getElementById("ma_dh").value = "";
      document.getElementById("formTitle").textContent = "Thêm đơn hàng";
    } else {
      alert("Lỗi khi lưu!");
    }
  });

  // Sửa đơn hàng
  function editOrder(o) {
    document.getElementById("ma_dh").value = o.ma_dh;
    document.getElementById("ma_nd").value = o.ma_nd;
    document.getElementById("tong_tien").value = o.tong_tien;
    document.getElementById("trang_thai").value = o.trang_thai;
    document.getElementById("formTitle").textContent = "Sửa đơn hàng";
  }

  // Xóa đơn hàng
  async function deleteOrder(id) {
    if (!confirm("Bạn có chắc muốn xóa đơn hàng này?")) return;
    const res = await fetch(apiUrl, {
      method: "DELETE",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ ma_dh: id })
    });
    const result = await res.json();
    if (result.success) {
      alert("Đã xóa!");
      loadOrders();
    } else alert("Xóa thất bại!");
  }

  // Reset form
  document.getElementById("resetForm").addEventListener("click", () => {
    document.getElementById("orderForm").reset();
    document.getElementById("ma_dh").value = "";
    document.getElementById("formTitle").textContent = "Thêm đơn hàng";
  });

  loadOrders();
  </script>
</body>
</html>
