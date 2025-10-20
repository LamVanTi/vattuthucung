<?php
include_once __DIR__ . '/../../backend/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT d.ma_dg, d.ma_nd, d.ma_sp, d.so_sao, d.noi_dung, d.ngay_danh_gia,
                       n.ho_ten AS ten_nd, s.ten_sp
                FROM danh_gia d
                LEFT JOIN nguoi_dung n ON d.ma_nd = n.ma_nd
                LEFT JOIN san_pham s ON d.ma_sp = s.ma_sp
                ORDER BY d.ma_dg DESC";
        $result = $conn->query($sql);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['ma_nd'], $input['ma_sp'], $input['so_sao'], $input['noi_dung'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Thiếu dữ liệu bắt buộc']);
            exit;
        }

        $ma_nd = (int)$input['ma_nd'];
        $ma_sp = (int)$input['ma_sp'];
        $so_sao = (int)$input['so_sao'];
        $noi_dung = $conn->real_escape_string($input['noi_dung']);

        if ($so_sao < 1 || $so_sao > 5) {
            http_response_code(400);
            echo json_encode(['error' => 'Số sao phải từ 1 đến 5']);
            exit;
        }

        $sql = "INSERT INTO danh_gia (ma_nd, ma_sp, so_sao, noi_dung, ngay_danh_gia)
                VALUES ($ma_nd, $ma_sp, $so_sao, '$noi_dung', NOW())";

        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => $conn->error]);
        }
        break;

    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || !isset($input['ma_dg'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Thiếu mã đánh giá']);
            exit;
        }

        $ma_dg = (int)$input['ma_dg'];
        $ma_nd = (int)$input['ma_nd'];
        $ma_sp = (int)$input['ma_sp'];
        $so_sao = (int)$input['so_sao'];
        $noi_dung = $conn->real_escape_string($input['noi_dung']);

        if ($so_sao < 1 || $so_sao > 5) {
            http_response_code(400);
            echo json_encode(['error' => 'Số sao phải từ 1 đến 5']);
            exit;
        }

        $sql = "UPDATE danh_gia
                SET ma_nd=$ma_nd, ma_sp=$ma_sp, so_sao=$so_sao, noi_dung='$noi_dung'
                WHERE ma_dg=$ma_dg";

        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => $conn->error]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents('php://input'), $_DELETE);
        $ma_dg = (int)($_DELETE['ma_dg'] ?? 0);

        if ($ma_dg <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Thiếu mã đánh giá']);
            exit;
        }

        $sql = "DELETE FROM danh_gia WHERE ma_dg=$ma_dg";
        if ($conn->query($sql)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => $conn->error]);
        }
        break;
}
?>
