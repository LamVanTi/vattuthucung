<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM `order`";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO `order` (user_id, total_amount, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $data['user_id'], $data['total_amount'], $data['status']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE `order` SET total_amount=?, status=? WHERE order_id=?");
        $stmt->bind_param("dsi", $data['total_amount'], $data['status'], $data['order_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM `order` WHERE order_id=?");
        $stmt->bind_param("i", $data['order_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}
$conn->close();
?>
