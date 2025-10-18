<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // LẤY DANH SÁCH NHÀ CUNG CẤP
    case 'GET':
        $sql = "SELECT * FROM nha_cung_cap";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    // THÊM NHÀ CUNG CẤP
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['ten_ncc']) || !isset($data['so_dien_thoai'])) {
            echo json_encode(["success" => false, "message" => "Thiếu dữ liệu bắt buộc"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO nha_cung_cap (ten_ncc, so_dien_thoai, email, dia_chi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['ten_ncc'], $data['so_dien_thoai'], $data['email'], $data['dia_chi']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // CẬP NHẬT NHÀ CUNG CẤP
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['ma_ncc'])) {
            echo json_encode(["success" => false, "message" => "Thiếu mã nhà cung cấp"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE nha_cung_cap SET ten_ncc=?, so_dien_thoai=?, email=?, dia_chi=? WHERE ma_ncc=?");
        $stmt->bind_param("ssssi", $data['ten_ncc'], $data['so_dien_thoai'], $data['email'], $data['dia_chi'], $data['ma_ncc']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // XÓA NHÀ CUNG CẤP
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['ma_ncc'])) {
            echo json_encode(["success" => false, "message" => "Thiếu mã nhà cung cấp"]);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM nha_cung_cap WHERE ma_ncc=?");
        $stmt->bind_param("i", $data['ma_ncc']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
