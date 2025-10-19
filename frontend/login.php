<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập - PetSupply</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      background: linear-gradient(120deg,#a8edea,#fed6e3);
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }
    /* Hiệu ứng background động */
    .bg-paw {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        top: 0;
        left: 0;
        z-index: -1;
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


    .login-card {
      background: rgba(255, 255, 255, 0.9);
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      max-width: 400px;
      width: 100%;
    }
    .login-card h3 {
      font-weight: bold;
      margin-bottom: 1rem;
      text-align: center;
    }
    .form-control:focus {
      box-shadow: 0 0 8px rgba(0,123,255,0.5);
      border-color: #007bff;
    }
    .btn-login {
      width: 100%;
      background: linear-gradient(90deg,#6a11cb,#2575fc);
      color: white;
      font-weight: bold;
      border: none;
      transition: 0.3s;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(106,17,203,0.3);
    }
  </style>
</head>
<body>
  
<!-- NỀN HIỆU ỨNG BÀN CHÂN -->
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

  <div class="login-card">
    <h3><i class="fa-solid fa-paw"></i> PetSupply Login </h3>
    <form id="loginForm">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" class="form-control" placeholder="Nhập email..." required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input type="password" id="password" class="form-control" placeholder="Nhập mật khẩu..." required>
      </div>
      <button type="submit" class="btn btn-login">Đăng nhập</button>
    </form>
    <div class="text-center mt-3">
      <a href="register.php" class="text-decoration-none">Chưa có tài khoản? Đăng ký</a>
    </div>
  </div>

  <script>
    const API_LOGIN = "http://localhost/vattuthucung/backend/api/user_api.php?action=login";

    document.getElementById('loginForm').addEventListener('submit', async function(e){
      e.preventDefault();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      const res = await fetch(API_LOGIN, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email, mat_khau: password })
      });

      const data = await res.json();
      console.log(data);

      if(data.success){
        alert("Đăng nhập thành công 🎉");
        localStorage.setItem('user', JSON.stringify(data.user));
        window.location.href = "index.php";
      } else {
        alert(data.message || "Sai thông tin đăng nhập ❌");
      }
    });
  </script>
</body>
</html>
