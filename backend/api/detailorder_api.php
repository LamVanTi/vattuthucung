<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // 🔹 LẤY DANH SÁCH CHI TIẾT ĐƠN HÀNG
    case 'GET':
        $sql = "SELECT * FROM chi_tiet_don_hang";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    // 🔹 THÊM CHI TIẾT ĐƠN HÀNG
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['ma_dh']) || !isset($data['ma_sp']) || !isset($data['so_luong']) || !isset($data['gia'])) {
            echo json_encode(["success" => false, "message" => "Thiếu dữ liệu bắt buộc"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO chi_tiet_don_hang (ma_dh, ma_sp, so_luong, gia) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $data['ma_dh'], $data['ma_sp'], $data['so_luong'], $data['gia']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // 🔹 CẬP NHẬT CHI TIẾT ĐƠN HÀNG
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['ma_ctdh'])) {
            echo json_encode(["success" => false, "message" => "Thiếu mã chi tiết đơn hàng"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE chi_tiet_don_hang SET ma_dh=?, ma_sp=?, so_luong=?, gia=? WHERE ma_ctdh=?");
        $stmt->bind_param("iiidi", $data['ma_dh'], $data['ma_sp'], $data['so_luong'], $data['gia'], $data['ma_ctdh']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // 🔹 XÓA CHI TIẾT ĐƠN HÀNG
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['ma_ctdh'])) {
            echo json_encode(["success" => false, "message" => "Thiếu mã chi tiết đơn hàng"]);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM chi_tiet_don_hang WHERE ma_ctdh=?");
        $stmt->bind_param("i", $data['ma_ctdh']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
