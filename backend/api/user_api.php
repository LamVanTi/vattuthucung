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
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    // THÊM NGƯỜI DÙNG
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO user (username, password, email, phone, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['username'], $data['password'], $data['email'], $data['phone'], $data['address']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // CẬP NHẬT NGƯỜI DÙNG
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE user SET username=?, password=?, email=?, phone=?, address=? WHERE user_id=?");
        $stmt->bind_param("sssssi", $data['username'], $data['password'], $data['email'], $data['phone'], $data['address'], $data['user_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    // XÓA NGƯỜI DÙNG
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM user WHERE user_id=?");
        $stmt->bind_param("i", $data['user_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}

$conn->close();
?>
