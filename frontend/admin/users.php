<?php
// Không yêu cầu đăng nhập admin
const apiUrl = "../../backend/api/user_api.php";



?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý người dùng</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4 text-center">👤 Quản lý người dùng</h2>

    <!-- Form thêm/sửa người dùng -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title" id="formTitle">Thêm người dùng</h5>
        <form id="userForm">
          <input type="hidden" id="ma_nd">
          <div class="row g-2">
            <div class="col-md-6">
              <input type="text" id="ho_ten" class="form-control" placeholder="Họ tên" required>
            </div>
            <div class="col-md-6">
              <input type="email" id="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-6">
              <input type="password" id="mat_khau" class="form-control" placeholder="Mật khẩu" required>
            </div>
            <div class="col-md-6">
              <input type="text" id="so_dien_thoai" class="form-control" placeholder="Số điện thoại">
            </div>
            <div class="col-md-6">
              <input type="text" id="dia_chi" class="form-control" placeholder="Địa chỉ">
            </div>
            <div class="col-md-6">
              <select id="vai_tro" class="form-select">
                <option value="user">Người dùng</option>
                <option value="admin">Quản trị viên</option>
              </select>
            </div>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" id="resetForm">Làm mới</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Bảng danh sách người dùng -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-3">Danh sách người dùng</h5>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>SĐT</th>
              <th>Địa chỉ</th>
              <th>Vai trò</th>
              <th>Ngày đăng ký</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="userTable"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
  const apiUrl = "../../backend/api/user_api.php";

  // Lấy danh sách người dùng
  async function loadUsers() {
    const res = await fetch(apiUrl);
    const users = await res.json();
    const table = document.getElementById("userTable");
    table.innerHTML = "";
    users.forEach(u => {
      table.innerHTML += `
        <tr>
          <td>${u.ma_nd}</td>
          <td>${u.ho_ten}</td>
          <td>${u.email}</td>
          <td>${u.so_dien_thoai || ""}</td>
          <td>${u.dia_chi || ""}</td>
          <td>${u.vai_tro}</td>
          <td>${u.ngay_dang_ky || ""}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick='editUser(${JSON.stringify(u)})'>Sửa</button>
            <button class="btn btn-sm btn-danger" onclick='deleteUser(${u.ma_nd})'>Xóa</button>
          </td>
        </tr>`;
    });
  }

  // Thêm / Cập nhật người dùng
  document.getElementById("userForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = {
      ma_nd: document.getElementById("ma_nd").value,
      ho_ten: document.getElementById("ho_ten").value,
      email: document.getElementById("email").value,
      mat_khau: document.getElementById("mat_khau").value,
      so_dien_thoai: document.getElementById("so_dien_thoai").value,
      dia_chi: document.getElementById("dia_chi").value,
      vai_tro: document.getElementById("vai_tro").value
    };
    const method = data.ma_nd ? "PUT" : "POST";
    const res = await fetch(apiUrl, {
      method,
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify(data)
    });
    const result = await res.json();
    if (result.success) {
      alert("Lưu thành công!");
      loadUsers();
      e.target.reset();
      document.getElementById("ma_nd").value = "";
      document.getElementById("formTitle").textContent = "Thêm người dùng";
    } else alert("Lỗi khi lưu!");
  });

  // Chọn người dùng để sửa
  function editUser(u) {
    document.getElementById("ma_nd").value = u.ma_nd;
    document.getElementById("ho_ten").value = u.ho_ten;
    document.getElementById("email").value = u.email;
    document.getElementById("mat_khau").value = u.mat_khau;
    document.getElementById("so_dien_thoai").value = u.so_dien_thoai;
    document.getElementById("dia_chi").value = u.dia_chi;
    document.getElementById("vai_tro").value = u.vai_tro;
    document.getElementById("formTitle").textContent = "Sửa người dùng";
  }

  // Xóa người dùng
  async function deleteUser(id) {
    if (!confirm("Bạn có chắc muốn xóa người dùng này?")) return;
    const res = await fetch(apiUrl, {
      method: "DELETE",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ ma_nd: id })
    });
    const result = await res.json();
    if (result.success) {
      alert("Đã xóa!");
      loadUsers();
    } else alert("Xóa thất bại!");
  }

  // Reset form
  document.getElementById("resetForm").addEventListener("click", () => {
    document.getElementById("userForm").reset();
    document.getElementById("ma_nd").value = "";
    document.getElementById("formTitle").textContent = "Thêm người dùng";
  });

  loadUsers();
  </script>
</body>
</html>
