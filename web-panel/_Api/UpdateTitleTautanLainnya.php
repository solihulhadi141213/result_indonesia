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
    if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
        respond(['status' => 'error', 'message' => 'Metode harus PUT'], 405);
    }

    // Validasi token
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';
    if (!$token) respond(['status' => 'error', 'message' => 'Token tidak ditemukan'], 401);

    $Conn = (new Database())->getConnection();
    if (validasi_x_token($Conn, $token) !== 'Valid') {
        respond(['status' => 'error', 'message' => 'Token tidak valid'], 403);
    }

    // Ambil input
    $input = json_decode(file_get_contents("php://input"), true);
    if (!is_array($input)) {
        respond(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    $title = trim(strip_tags($input['title'] ?? ''));
    if ($title === '') {
        respond(['status' => 'error', 'message' => 'Title tidak boleh kosong'], 422);
    }
    if (strlen($title) > 200) {
        respond(['status' => 'error', 'message' => 'Title maksimal 200 karakter'], 422);
    }

    // Ambil data setting layout_static
    $stmt = $Conn->prepare("SELECT setting_value FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        respond(['status' => 'error', 'message' => 'Data layout_static tidak ditemukan'], 404);
    }

    $data = json_decode($row['setting_value'], true);
    if (!isset($data['tautan_lainnya']) || !is_array($data['tautan_lainnya'])) {
        respond(['status' => 'error', 'message' => 'Struktur tautan_lainnya tidak valid'], 500);
    }

    // Update title
    $data['tautan_lainnya']['title'] = $title;

    // Simpan ke database
    $newJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $update = $Conn->prepare("UPDATE setting SET setting_value = :val WHERE setting_parameter = 'layout_static'");
    $update->bindParam(':val', $newJson);

    if ($update->execute()) {
        respond(['status' => 'success', 'message' => 'Title tautan_lainnya berhasil diperbarui']);
    } else {
        respond(['status' => 'error', 'message' => 'Gagal memperbarui data'], 500);
    }
?>