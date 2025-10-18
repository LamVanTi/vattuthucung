<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lấy danh sách yêu thích kèm thông tin sản phẩm và người dùng
        $sql = "SELECT y.ma_yt, y.ma_nd, y.ma_sp, y.ngay_them,
                       n.ho_ten AS ten_nguoi_dung,
                       s.ten_sp, s.gia, s.hinh_anh
                FROM yeu_thich y
                JOIN nguoi_dung n ON y.ma_nd = n.ma_nd
                JOIN san_pham s ON y.ma_sp = s.ma_sp";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'POST':
        // Thêm sản phẩm vào danh sách yêu thích
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO yeu_thich (ma_nd, ma_sp) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['ma_nd'], $data['ma_sp']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        // Xóa sản phẩm khỏi danh sách yêu thích
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM yeu_thich WHERE ma_yt=?");
        $stmt->bind_param("i", $data['ma_yt']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
