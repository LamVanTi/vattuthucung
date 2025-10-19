<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PetSupply - Thiên đường cho thú cưng 🐶🐾</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        /* Banner */
        .hero {
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            padding: 80px 20px;
            text-align: center;
        }
        .hero h1 {
            font-weight: 700;
            font-size: 2.8rem;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        /* Danh mục */
        .category-card {
            transition: all 0.3s;
            border: none;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .category-card:hover {
            transform: translateY(-6px);
        }

        /* Sản phẩm */
        .product-card {
            transition: 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
        }
        .product-card img {
            height: 220px;
            object-fit: cover;
        }

        /* Feedback */
        .review-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
            height: 100%;
        }

        /* Subscribe */
        .subscribe-section {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            color: #fff;
            padding: 60px 20px;
            text-align: center;
        }
        .subscribe-section input {
            max-width: 400px;
        }

        footer {
            background: #222;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        /* Animation */
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

<!-- Banner -->
<section class="hero" data-animate>
    <h1>Chào mừng đến với PetSupply 🐾</h1>
    <p>Thiên đường phụ kiện và thức ăn cho thú cưng 🐶🐱</p>
    <a href="products.php" class="btn btn-primary btn-lg px-4">Mua ngay</a>
</section>

<!-- Danh mục -->
<section class="container py-5" data-animate>
    <h2 class="text-center mb-4 fw-bold">Danh mục sản phẩm</h2>
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <div class="card category-card text-center p-3">
                <img src="assets/food.png" class="card-img-top mx-auto" style="width:80px;" alt="Thức ăn">
                <div class="card-body">
                    <h5 class="card-title">Thức ăn</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card category-card text-center p-3">
                <img src="assets/toy.png" class="card-img-top mx-auto" style="width:80px;" alt="Đồ chơi">
                <div class="card-body">
                    <h5 class="card-title">Đồ chơi</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card category-card text-center p-3">
                <img src="assets/collar.png" class="card-img-top mx-auto" style="width:80px;" alt="Phụ kiện">
                <div class="card-body">
                    <h5 class="card-title">Phụ kiện</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card category-card text-center p-3">
                <img src="assets/health.png" class="card-img-top mx-auto" style="width:80px;" alt="Sức khỏe">
                <div class="card-body">
                    <h5 class="card-title">Sức khỏe</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sản phẩm nổi bật -->
<section class="container py-5" data-animate>
    <h2 class="text-center mb-4 fw-bold">Sản phẩm nổi bật</h2>
    <div class="row g-4" id="product-list"></div>
</section>

<!-- Feedback -->
<section class="container py-5" data-animate>
    <h2 class="text-center mb-4 fw-bold">Khách hàng nói gì về chúng tôi</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="review-card">
                <p>🐾 “Sản phẩm chất lượng, shop tư vấn dễ thương lắm.”</p>
                <small>- Khách hàng A</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="review-card">
                <p>🐕 “Mua lần đầu mà ưng ghê. Giao hàng nhanh nữa.”</p>
                <small>- Khách hàng B</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="review-card">
                <p>🐶 “Thức ăn ngon, bé cưng nhà mình mê tít 🐾”</p>
                <small>- Khách hàng C</small>
            </div>
        </div>
    </div>
</section>

<!-- Subscribe -->
<section class="subscribe-section" data-animate>
    <h2>Đăng ký nhận khuyến mãi</h2>
    <p class="mb-4">Giảm giá cực sốc mỗi tuần ✨</p>
    <form class="d-flex justify-content-center">
        <input type="email" class="form-control me-2" placeholder="Nhập email của bạn..." required>
        <button class="btn btn-dark">Đăng ký</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>

<script>
    // animation scroll effect
    const elements = document.querySelectorAll('[data-animate]');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.2 });
    elements.forEach(el => observer.observe(el));

    // load sản phẩm từ API
    axios.get('api/product_api.php')
    .then(res => {
        const data = res.data;
        const productList = document.getElementById('product-list');
        productList.innerHTML = data.map(sp => `
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card product-card h-100">
                    <img src="${sp.hinh_anh}" class="card-img-top" alt="${sp.ten_san_pham}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${sp.ten_san_pham}</h5>
                        <p class="text-danger fw-bold">${Number(sp.gia).toLocaleString()} đ</p>
                        <a href="product_detail.php?id=${sp.ma_san_pham}" class="btn btn-primary mt-auto">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        `).join('');
    })
    .catch(err => console.error(err));
</script>

</body>
</html>
