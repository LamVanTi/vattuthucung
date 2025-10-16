<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // 🟢 LẤY DANH SÁCH SẢN PHẨM
    case 'GET':
    $sql = "SELECT * FROM san_pham";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode([
            "error" => true,
            "message" => "Lỗi truy vấn SQL: " . $conn->error
        ]);
        break;
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
    break;

    // 🟡 THÊM SẢN PHẨM
   case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);

    $ten_sp = $data['ten_sp'] ?? '';
    $mo_ta = $data['mo_ta'] ?? '';
    $gia = $data['gia'] ?? 0;
    $so_luong = $data['so_luong'] ?? 0;
    $loai_sp = $data['loai_sp'] ?? '';
    $thuong_hieu = $data['thuong_hieu'] ?? '';
    $hinh_anh = $data['hinh_anh'] ?? '';

    $sql = "INSERT INTO san_pham (ten_sp, mo_ta, gia, so_luong, loai_sp, thuong_hieu, hinh_anh)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => true, "message" => "Lỗi SQL: " . $conn->error]);
        break;
    }

    $stmt->bind_param("ssdiiss", $ten_sp, $mo_ta, $gia, $so_luong, $loai_sp, $thuong_hieu, $hinh_anh);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Thêm sản phẩm thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi thêm sản phẩm: " . $stmt->error]);
    }
    break;

    // 🟠 CẬP NHẬT SẢN PHẨM
    case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);

    // Lấy dữ liệu từ JSON
    $ma_sp = $data['ma_sp'] ?? 0;
    $ten_sp = $data['ten_sp'] ?? '';
    $mo_ta = $data['mo_ta'] ?? '';
    $gia = $data['gia'] ?? 0;
    $so_luong = $data['so_luong'] ?? 0;
    $loai_sp = $data['loai_sp'] ?? '';
    $thuong_hieu = $data['thuong_hieu'] ?? '';
    $hinh_anh = $data['hinh_anh'] ?? '';

    // Kiểm tra đầu vào hợp lệ
    if ($ma_sp == 0) {
        echo json_encode(["error" => true, "message" => "Thiếu mã sản phẩm để cập nhật"]);
        break;
    }

    $sql = "UPDATE san_pham 
            SET ten_sp = ?, mo_ta = ?, gia = ?, so_luong = ?, loai_sp = ?, thuong_hieu = ?, hinh_anh = ? 
            WHERE ma_sp = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["error" => true, "message" => "Lỗi SQL: " . $conn->error]);
        break;
    }

    $stmt->bind_param("ssdiissi", $ten_sp, $mo_ta, $gia, $so_luong, $loai_sp, $thuong_hieu, $hinh_anh, $ma_sp);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Cập nhật sản phẩm thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật sản phẩm: " . $stmt->error]);
    }
    break;
    // 🔴 XÓA SẢN PHẨM
    case 'DELETE':
    $data = json_decode(file_get_contents("php://input"), true);
    $ma_sp = intval($data['ma_sp'] ?? 0);

    if ($ma_sp > 0) {
        // Xóa dữ liệu liên quan trước
        $conn->query("DELETE FROM chi_tiet_don_hang WHERE ma_sp = $ma_sp");
        $conn->query("DELETE FROM danh_gia WHERE ma_sp = $ma_sp");
        $conn->query("DELETE FROM gio_hang WHERE ma_sp = $ma_sp");
        $conn->query("DELETE FROM yeu_thich WHERE ma_sp = $ma_sp");

        // Sau đó xóa sản phẩm chính
        $stmt = $conn->prepare("DELETE FROM san_pham WHERE ma_sp = ?");
        if ($stmt) {
            $stmt->bind_param("i", $ma_sp);
            $success = $stmt->execute();
            echo json_encode([
                "success" => $success,
                "message" => $success ? "Xóa sản phẩm thành công" : "Xóa sản phẩm thất bại"
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi chuẩn bị truy vấn: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Thiếu mã sản phẩm"]);
    }
    break;



}

$conn->close();
?>
