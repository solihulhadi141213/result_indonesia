<?php
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    header('Content-Type: application/json');

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['status' => false, 'message' => 'Metode tidak diizinkan. Gunakan POST']);
        exit;
    }

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
    if (validasi_x_token($conn, $token) !== 'Valid') {
        http_response_code(401);
        echo json_encode(['status' => false, 'message' => 'Token tidak valid atau sudah kedaluwarsa']);
        exit;
    }

    // Ambil input
    $input = json_decode(file_get_contents('php://input'), true);
    $url  = trim($input['url'] ?? '');
    $icon = trim($input['icon'] ?? '');

    if (!$url || !$icon) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parameter "url" dan "icon" wajib diisi']);
        exit;
    }

    // Ambil data setting layout_static
    $stmt = $conn->prepare("SELECT * FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1");
    $stmt->execute();
    $setting = $stmt->fetch();

    if (!$setting) {
        http_response_code(404);
        echo json_encode(['status' => false, 'message' => 'Data layout_static tidak ditemukan']);
        exit;
    }

    $data = json_decode($setting['setting_value'], true);
    $medsosList = $data['media_sosial']['list'] ?? [];

    // Tentukan order baru
    $lastOrder = 0;
    foreach ($medsosList as $item) {
        if ($item['order'] > $lastOrder) {
            $lastOrder = $item['order'];
        }
    }
    $newOrder = $lastOrder + 1;

    // Tambahkan item baru
    $medsosList[] = [
        'url' => $url,
        'icon' => $icon,
        'order' => $newOrder
    ];
    $data['media_sosial']['list'] = $medsosList;

    // Simpan kembali
    $updatedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $updateStmt = $conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $updateStmt->bindParam(':val', $updatedJson);
    $updateStmt->execute();

    echo json_encode([
        'status' => true,
        'message' => 'Media sosial berhasil ditambahkan',
        'data' => [
            'url' => $url,
            'icon' => $icon,
            'order' => $newOrder
        ]
    ]);
?>