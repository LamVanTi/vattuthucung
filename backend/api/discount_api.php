<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // 🔹 LẤY DANH SÁCH KHUYẾN MÃI
    case 'GET':
        $sql = "SELECT * FROM khuyen_mai";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    // 🔹 THÊM KHUYẾN MÃI
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['ma_code']) || !isset($data['phan_tram_giam'])) {
            echo json_encode(["success" => false, "message" => "Thiếu dữ liệu bắt buộc"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO khuyen_mai (ma_code, phan_tram_giam, ngay_bat_dau, ngay_ket_thuc, mo_ta) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $data['ma_code'], $data['phan_tram_giam'], $data['ngay_bat_dau'], $data['ngay_ket_thuc'], $data['mo_ta']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // 🔹 CẬP NHẬT KHUYẾN MÃI
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['ma_km'])) {
            echo json_encode(["success" => false, "message" => "Thiếu mã khuyến mãi"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE khuyen_mai SET ma_code=?, phan_tram_giam=?, ngay_bat_dau=?, ngay_ket_thuc=?, mo_ta=? WHERE ma_km=?");
        $stmt->bind_param("sisssi", $data['ma_code'], $data['phan_tram_giam'], $data['ngay_bat_dau'], $data['ngay_ket_thuc'], $data['mo_ta'], $data['ma_km']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // 🔹 XÓA KHUYẾN MÃI
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['ma_km'])) {
            echo json_encode(["success" => false, "message" => "Thiếu mã khuyến mãi"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM khuyen_mai WHERE ma_km=?");
        $stmt->bind_param("i", $data['ma_km']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
