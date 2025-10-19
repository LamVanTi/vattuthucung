<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Đăng ký - PetSupply</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(135deg, #c8f5f0, #fcd4e1);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow: hidden;
    }

    .register-box {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
      width: 380px;
      position: relative;
      z-index: 1;
    }

    .register-box h2 {
      margin-bottom: 20px;
      font-size: 24px;
      font-weight: 700;
    }

    .register-box .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .register-box .form-group label {
      font-size: 14px;
      display: block;
      margin-bottom: 5px;
    }

    .register-box .form-group input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      outline: none;
      font-size: 14px;
      transition: 0.2s;
    }

    .register-box .form-group input:focus {
      border-color: #4a90e2;
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .register-box button:hover {
      opacity: 0.9;
    }

    .register-box p {
      margin-top: 15px;
      font-size: 14px;
    }

    .register-box p a {
      color: #2575fc;
      text-decoration: none;
      font-weight: 600;
    }

    /* 🐾 Nền hiệu ứng bàn chân */
    .bg-paw {
      position: absolute;
      width: 100%;
      height: 100%;
      overflow: hidden;
      top: 0;
      left: 0;
      z-index: 0;
    }

    .bg-paw i {
      position: absolute;
      color: rgba(255, 255, 255, 0.3);
      font-size: 30px;
      animation: pawMove 10s linear infinite;
      bottom: -100px;
    }

    .bg-paw i:nth-child(1){ left: 10%; font-size: 50px; animation-delay: 0s; }
    .bg-paw i:nth-child(2){ left: 25%; font-size: 30px; animation-delay: 5s; }
    .bg-paw i:nth-child(3){ left: 40%; font-size: 60px; animation-delay: 2s; animation-duration: 20s; }
    .bg-paw i:nth-child(4){ left: 55%; font-size: 45px; animation-delay: 8s; }
    .bg-paw i:nth-child(5){ left: 70%; font-size: 35px; animation-delay: 3s; }
    .bg-paw i:nth-child(6){ left: 85%; font-size: 55px; animation-delay: 6s; animation-duration: 22s; }
    .bg-paw i:nth-child(7){ left: 15%; font-size: 40px; animation-delay: 10s; }
    .bg-paw i:nth-child(8){ left: 50%; font-size: 25px; animation-delay: 12s; animation-duration: 35s; }
    .bg-paw i:nth-child(9){ left: 75%; font-size: 20px; animation-delay: 16s; animation-duration: 40s; }
    .bg-paw i:nth-child(10){ left: 90%; font-size: 65px; animation-delay: 4s; }

    @keyframes pawMove {
      0% { transform: translateY(0) rotate(0deg); opacity: 1; }
      100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }
  </style>
</head>
<body>

  <div class="bg-paw">
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
    <i class="fa-solid fa-paw"></i>
  </div>

  <div class="register-box">
    <h2><i class="fa-solid fa-paw"></i> PetSupply Sign Up </h2>
    <form id="registerForm">
      <div class="form-group">
        <label>Họ và tên</label>
        <input type="text" name="ho_ten" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Mật khẩu</label>
        <input type="password" name="mat_khau" required>
      </div>
      <div class="form-group">
        <label>Số điện thoại</label>
        <input type="text" name="so_dien_thoai" required>
      </div>
      <div class="form-group">
        <label>Địa chỉ</label>
        <input type="text" name="dia_chi" required>
      </div>
      <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
  </div>

  <script>
    document.getElementById('registerForm').addEventListener('submit', async function(e){
      e.preventDefault();
      const formData = {
        ho_ten: this.ho_ten.value,
        email: this.email.value,
        mat_khau: this.mat_khau.value,
        so_dien_thoai: this.so_dien_thoai.value,
        dia_chi: this.dia_chi.value,
        vai_tro: "user"
      };

      const res = await fetch('../backend/api/user_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      const data = await res.json();
      if(data.success){
        alert('Đăng ký thành công 🎉');
        window.location.href = 'login.php';
      } else {
        alert('Đăng ký thất bại 😢');
      }
    });
  </script>
</body>
</html>
