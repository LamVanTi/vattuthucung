<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // LẤY DANH SÁCH NGƯỜI DÙNG
    case 'GET':
        $sql = "SELECT ma_nd, ho_ten, email, so_dien_thoai, dia_chi, vai_tro, ngay_dang_ky FROM nguoi_dung";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    // THÊM NGƯỜI DÙNG
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("
            INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, dia_chi, vai_tro) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssss", 
            $data['ho_ten'], 
            $data['email'], 
            $data['mat_khau'], 
            $data['so_dien_thoai'], 
            $data['dia_chi'], 
            $data['vai_tro']
        );
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // CẬP NHẬT NGƯỜI DÙNG
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("
            UPDATE nguoi_dung 
            SET ho_ten=?, email=?, mat_khau=?, so_dien_thoai=?, dia_chi=?, vai_tro=? 
            WHERE ma_nd=?
        ");
        $stmt->bind_param("ssssssi", 
            $data['ho_ten'], 
            $data['email'], 
            $data['mat_khau'], 
            $data['so_dien_thoai'], 
            $data['dia_chi'], 
            $data['vai_tro'], 
            $data['ma_nd']
        );
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // XÓA NGƯỜI DÙNG
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM nguoi_dung WHERE ma_nd=?");
        $stmt->bind_param("i", $data['ma_nd']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
