<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM cart";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isid", $data['user_id'], $data['product_name'], $data['quantity'], $data['price']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE cart SET product_name=?, quantity=?, price=? WHERE cart_id=?");
        $stmt->bind_param("sidi", $data['product_name'], $data['quantity'], $data['price'], $data['cart_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id=?");
        $stmt->bind_param("i", $data['cart_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}
$conn->close();
?>
