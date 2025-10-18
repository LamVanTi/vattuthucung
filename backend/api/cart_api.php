<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lấy toàn bộ giỏ hàng kèm thông tin sản phẩm và người dùng
        $sql = "SELECT g.ma_gh, g.ma_nd, g.ma_sp, g.so_luong, 
                       n.ho_ten AS ten_nguoi_dung, 
                       s.ten_sp, s.gia, (s.gia * g.so_luong) AS tong_tien
                FROM gio_hang g
                JOIN nguoi_dung n ON g.ma_nd = n.ma_nd
                JOIN san_pham s ON g.ma_sp = s.ma_sp";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'POST':
        // Thêm sản phẩm vào giỏ hàng
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO gio_hang (ma_nd, ma_sp, so_luong) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $data['ma_nd'], $data['ma_sp'], $data['so_luong']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'PUT':
        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE gio_hang SET so_luong=? WHERE ma_gh=?");
        $stmt->bind_param("ii", $data['so_luong'], $data['ma_gh']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        // Xóa sản phẩm khỏi giỏ hàng
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM gio_hang WHERE ma_gh=?");
        $stmt->bind_param("i", $data['ma_gh']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
