<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include '../db.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM review";
        $result = $conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        echo json_encode($data);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO review (user_id, product_name, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $data['user_id'], $data['product_name'], $data['rating'], $data['comment']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE review SET product_name=?, rating=?, comment=? WHERE review_id=?");
        $stmt->bind_param("sisi", $data['product_name'], $data['rating'], $data['comment'], $data['review_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM review WHERE review_id=?");
        $stmt->bind_param("i", $data['review_id']);
        echo json_encode(["success" => $stmt->execute()]);
        break;
}
$conn->close();
?>
