<?php
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    header('Content-Type: application/json');

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        http_response_code(405);
        echo json_encode(['status' => false, 'message' => 'Metode tidak diizinkan. Gunakan PUT']);
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

    // Ambil dan validasi input
    $input = json_decode(file_get_contents('php://input'), true);
    $url = trim($input['url'] ?? '');
    $icon = trim($input['icon'] ?? '');
    $order = $input['order'] ?? null;

    if (!$url || !$icon || !$order) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parameter "url", "icon", dan "order" wajib diisi']);
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

    // Update item yang sesuai dengan order
    $updated = false;
    foreach ($medsosList as &$item) {
        if ($item['order'] == $order) {
            $item['url'] = $url;
            $item['icon'] = $icon;
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        http_response_code(404);
        echo json_encode(['status' => false, 'message' => 'Data dengan order tersebut tidak ditemukan']);
        exit;
    }

    // Simpan kembali
    $data['media_sosial']['list'] = $medsosList;
    $updatedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $updateStmt = $conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $updateStmt->bindParam(':val', $updatedJson);
    $updateStmt->execute();

    echo json_encode([
        'status' => true,
        'message' => 'Data media sosial berhasil diperbarui',
        'data' => [
            'order' => $order,
            'url' => $url,
            'icon' => $icon
        ]
    ]);
?>