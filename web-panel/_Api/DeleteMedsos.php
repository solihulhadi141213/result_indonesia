<?php
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    header('Content-Type: application/json');

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        http_response_code(405);
        echo json_encode(['status' => false, 'message' => 'Metode tidak diizinkan. Gunakan DELETE']);
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

    // Ambil input JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $orderToDelete = $input['order'] ?? null;

    if (!$orderToDelete || !is_numeric($orderToDelete)) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parameter "order" tidak valid']);
        exit;
    }

    // Ambil data layout_static
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

    // Filter list: hapus semua item dengan order sesuai permintaan
    $filteredList = array_values(array_filter($medsosList, function ($item) use ($orderToDelete) {
        return $item['order'] != $orderToDelete;
    }));

    // Susun ulang order menjadi 1, 2, 3, ...
    foreach ($filteredList as $index => &$item) {
        $item['order'] = $index + 1;
    }
    unset($item);

    $data['media_sosial']['list'] = $filteredList;

    // Simpan kembali
    $updatedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $updateStmt = $conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $updateStmt->bindParam(':val', $updatedJson);
    $updateStmt->execute();

    echo json_encode([
        'status' => true,
        'message' => 'Data media sosial berhasil dihapus dan disusun ulang',
        'data' => $filteredList
    ]);
?>