<?php
    require_once '../_Config/Connection.php';
    require_once "../_Config/Function.php";

    header('Content-Type: application/json');

    // Ambil token dari header
    $headers = getallheaders();
    $token = $headers['x-token'] ?? '';

    if (!$token) {
        http_response_code(401);
        echo json_encode(['status' => false, 'message' => 'Token tidak ditemukan']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Validasi token
    $stmt = $conn->prepare("SELECT * FROM api_session WHERE session_token = :token AND datetime_expired > NOW()");
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $userSession = $stmt->fetch();


    $Conn = (new Database())->getConnection();
    if (validasi_x_token($Conn, $token) !== 'Valid') {
        http_response_code(401);
        echo json_encode(['status' => false, 'message' => 'Token tidak valid atau sudah kedaluwarsa']);
        exit;
    }

    // Ambil request body
    $input = json_decode(file_get_contents('php://input'), true);
    $orderToDelete = $input['order'] ?? null;

    if (!$orderToDelete) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parameter "order" tidak ditemukan']);
        exit;
    }

    // Ambil setting layout_static
    $stmt = $conn->prepare("SELECT * FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1");
    $stmt->execute();
    $setting = $stmt->fetch();

    if (!$setting) {
        http_response_code(404);
        echo json_encode(['status' => false, 'message' => 'Pengaturan layout_static tidak ditemukan']);
        exit;
    }

    $data = json_decode($setting['setting_value'], true);

    // Hapus item berdasarkan order
    $tautanList = $data['tautan_lainnya']['list'];
    $filteredList = array_values(array_filter($tautanList, function($item) use ($orderToDelete) {
        return $item['order'] != $orderToDelete;
    }));

    $data['tautan_lainnya']['list'] = $filteredList;

    // Simpan kembali ke database
    $newSettingValue = json_encode($data, JSON_UNESCAPED_UNICODE);
    $update = $conn->prepare("UPDATE setting SET setting_value = :setting_value WHERE setting_parameter = 'layout_static'");
    $update->bindParam(':setting_value', $newSettingValue);
    $update->execute();

    echo json_encode(['status' => true, 'message' => 'Tautan berhasil dihapus', 'data' => $filteredList]);
?>