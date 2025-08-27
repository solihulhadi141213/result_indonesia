<?php
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    header('Content-Type: application/json');

    // Validasi method
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

    if (validasi_x_token($conn, $token) !== 'Valid') {
        http_response_code(401);
        echo json_encode(['status' => false, 'message' => 'Token tidak valid atau sudah kedaluwarsa']);
        exit;
    }

    // Ambil input body
    $input = json_decode(file_get_contents('php://input'), true);
    $icon = trim($input['icon'] ?? '');
    $value = trim($input['value'] ?? '');

    if (!$icon || !$value) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parameter "icon" dan "value" wajib diisi']);
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

    // Siapkan list kontak
    $kontakList = $data['kontak']['list'] ?? [];

    // Hitung order terakhir
    $maxOrder = 0;
    foreach ($kontakList as $item) {
        if (isset($item['order']) && $item['order'] > $maxOrder) {
            $maxOrder = $item['order'];
        }
    }
    $newOrder = $maxOrder + 1;

    // Tambahkan item baru
    $kontakList[] = [
        'icon' => $icon,
        'value' => $value,
        'order' => $newOrder
    ];
    $data['kontak']['list'] = $kontakList;

    // Encode dan simpan
    $updatedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $updateStmt = $conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $updateStmt->bindParam(':val', $updatedJson);
    $updateStmt->execute();

    // Beri respons
    echo json_encode([
        'status' => true,
        'message' => 'Kontak berhasil ditambahkan',
        'data' => [
            'icon' => $icon,
            'value' => $value,
            'order' => $newOrder
        ]
    ]);
?>