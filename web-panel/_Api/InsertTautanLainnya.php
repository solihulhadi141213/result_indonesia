<?php
    header("Content-Type: application/json");
    require_once "../_Config/Connection.php";
    require_once "../_Config/Function.php";

    function respond($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond(['status' => 'error', 'message' => 'Metode harus POST'], 405);
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

    $url = trim(strip_tags($input['url'] ?? ''));
    $label = trim(strip_tags($input['label'] ?? ''));
    $target = trim($input['target'] ?? '');

    $allowedTargets = ['_blank', '_self', '_parent', '_top'];

    // Validasi input
    if ($url === '' || !filter_var('https://' . ltrim($url, '/'), FILTER_VALIDATE_URL)) {
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
        $data['tautan_lainnya']['list'] = [];
    }

    // Hitung order tertinggi
    $currentList = $data['tautan_lainnya']['list'];
    $maxOrder = 0;
    foreach ($currentList as $item) {
        if (isset($item['order']) && is_numeric($item['order'])) {
            $maxOrder = max($maxOrder, (int)$item['order']);
        }
    }

    // Tambahkan item baru
    $data['tautan_lainnya']['list'][] = [
        'order' => $maxOrder + 1,
        'url' => $url,
        'label' => $label,
        'target' => $target
    ];

    // Encode dan simpan kembali
    $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $update = $Conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $update->bindParam(':val', $newJson);

    if ($update->execute()) {
        respond(['status' => 'success', 'message' => 'Tautan berhasil ditambahkan']);
    } else {
        respond(['status' => 'error', 'message' => 'Gagal menyimpan data'], 500);
    }
?>