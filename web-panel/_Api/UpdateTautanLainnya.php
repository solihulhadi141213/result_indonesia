<?php
    header("Content-Type: application/json");
    require_once "../_Config/Connection.php";
    require_once "../_Config/Function.php";

    // Fungsi respon
    function respond($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        respond(['status' => 'error', 'message' => 'Metode harus PUT'], 405);
    }

    // Validasi token dari header
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';
    if (!$token) respond(['status' => 'error', 'message' => 'Token tidak ditemukan'], 401);

    $Conn = (new Database())->getConnection();
    if (validasi_x_token($Conn, $token) !== 'Valid') {
        respond(['status' => 'error', 'message' => 'Token tidak valid'], 403);
    }

    // Ambil input JSON
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        respond(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    // Ambil dan sanitasi input
    $order  = isset($input['order']) ? (int)$input['order'] : 0;
    $url    = trim(strip_tags($input['url'] ?? ''));
    $label  = trim(strip_tags($input['label'] ?? ''));
    $target = trim($input['target'] ?? '');

    $allowedTargets = ['_blank', '_self', '_parent', '_top'];

    // Validasi input
    if ($order <= 0) {
        respond(['status' => 'error', 'message' => 'Order tidak valid'], 422);
    }
    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        respond(['status' => 'error', 'message' => 'URL tidak valid'], 422);
    }
    if ($label === '') {
        respond(['status' => 'error', 'message' => 'Label tidak boleh kosong'], 422);
    }
    if (!in_array($target, $allowedTargets)) {
        respond(['status' => 'error', 'message' => 'Target tidak valid'], 422);
    }

    // Ambil setting layout_static
    $stmt = $Conn->prepare("SELECT setting_value FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        respond(['status' => 'error', 'message' => 'Data layout_static tidak ditemukan'], 404);
    }

    $data = json_decode($row['setting_value'], true);
    if (!isset($data['tautan_lainnya']['list']) || !is_array($data['tautan_lainnya']['list'])) {
        respond(['status' => 'error', 'message' => 'Data tautan_lainnya tidak ditemukan'], 404);
    }

    // Cari dan update item berdasarkan order
    $found = false;
    foreach ($data['tautan_lainnya']['list'] as &$item) {
        if ((int)$item['order'] === $order) {
            $item['url']    = $url;
            $item['label']  = $label;
            $item['target'] = $target;
            $found = true;
            break;
        }
    }
    unset($item);

    if (!$found) {
        respond(['status' => 'error', 'message' => 'Tautan dengan order tersebut tidak ditemukan'], 404);
    }

    // Encode dan simpan kembali ke database
    $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $update = $Conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $update->bindParam(':val', $newJson);

    if ($update->execute()) {
        respond(['status' => 'success', 'message' => 'Tautan berhasil diperbarui']);
    } else {
        respond(['status' => 'error', 'message' => 'Gagal menyimpan data'], 500);
    }
?>
