<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // 🔹 Lấy toàn bộ đơn hàng hoặc theo người dùng
    case 'GET':
        if (isset($_GET['ma_nd'])) {
            $ma_nd = intval($_GET['ma_nd']);
            $stmt = $conn->prepare("SELECT * FROM don_hang WHERE ma_nd = ?");
            $stmt->bind_param("i", $ma_nd);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $conn->query("SELECT * FROM don_hang");
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    // 🔹 Thêm đơn hàng mới
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO don_hang (ma_nd, tong_tien, trang_thai, ngay_dat) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ids", $data['ma_nd'], $data['tong_tien'], $data['trang_thai']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // 🔹 Cập nhật đơn hàng
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE don_hang SET tong_tien = ?, trang_thai = ? WHERE ma_dh = ?");
        $stmt->bind_param("dsi", $data['tong_tien'], $data['trang_thai'], $data['ma_dh']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // 🔹 Xóa đơn hàng
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM don_hang WHERE ma_dh = ?");
        $stmt->bind_param("i", $data['ma_dh']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
