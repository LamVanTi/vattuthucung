<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM favorite";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO favorite (user_id, product_name) VALUES (?, ?)");
        $stmt->bind_param("is", $data['user_id'], $data['product_name']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM favorite WHERE favorite_id=?");
        $stmt->bind_param("i", $data['favorite_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}
$conn->close();
?>
