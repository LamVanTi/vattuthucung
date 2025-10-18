<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lấy tất cả đánh giá kèm tên người dùng và sản phẩm
        $sql = "SELECT dg.ma_dg, dg.ma_nd, dg.ma_sp, nd.ho_ten, sp.ten_sp, dg.so_sao, dg.noi_dung, dg.ngay_danh_gia
                FROM danh_gia dg
                JOIN nguoi_dung nd ON dg.ma_nd = nd.ma_nd
                JOIN san_pham sp ON dg.ma_sp = sp.ma_sp
                ORDER BY dg.ngay_danh_gia DESC";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        // Thêm đánh giá mới
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO danh_gia (ma_nd, ma_sp, so_sao, noi_dung, ngay_danh_gia) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $data['ma_nd'], $data['ma_sp'], $data['so_sao'], $data['noi_dung']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'PUT':
        // Cập nhật đánh giá
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE danh_gia SET so_sao=?, noi_dung=? WHERE ma_dg=?");
        $stmt->bind_param("isi", $data['so_sao'], $data['noi_dung'], $data['ma_dg']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        // Xóa đánh giá
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM danh_gia WHERE ma_dg=?");
        $stmt->bind_param("i", $data['ma_dg']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
