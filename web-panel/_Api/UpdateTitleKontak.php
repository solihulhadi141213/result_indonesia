<?php
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    header('Content-Type: application/json');

    // Validasi method
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

    // Koneksi DB
    $db = new Database();
    $conn = $db->getConnection();

    // Validasi token via fungsi
    if (validasi_x_token($conn, $token) !== 'Valid') {
        http_response_code(401);
        echo json_encode(['status' => false, 'message' => 'Token tidak valid atau sudah kedaluwarsa']);
        exit;
    }

    // Ambil dan validasi body JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $newTitle = trim($input['title'] ?? '');

    if (!$newTitle) {
        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Parameter "title" tidak boleh kosong']);
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

    // Ubah title pada bagian "kontak"
    $data['kontak']['title'] = $newTitle;

    // Simpan kembali ke DB
    $updatedJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $updateStmt = $conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $updateStmt->bindParam(':val', $updatedJson);
    $updateStmt->execute();

    echo json_encode([
        'status' => true,
        'message' => 'Judul kontak berhasil diperbarui',
        'new_title' => $newTitle
    ]);
?>