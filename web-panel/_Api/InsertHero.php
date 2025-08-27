<?php
    header('Content-Type: application/json');
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    function sendResponse($data, $code = 200) {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Validasi metode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(['status' => 'error', 'message' => 'Metode harus POST'], 405);
    }

    // Validasi token
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';
    if (empty($token)) {
        sendResponse(['status' => 'error', 'message' => 'Token tidak ditemukan'], 401);
    }

    $Conn = (new Database())->getConnection();
    $valid = validasi_x_token($Conn, $token);
    if ($valid !== 'Valid') {
        sendResponse(['status' => 'error', 'message' => $valid], 401);
    }

    // Ambil input
    $raw = file_get_contents("php://input");
    $input = json_decode($raw, true);
    if (!is_array($input)) {
        sendResponse(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    // Validasi Data Wajib Terisi
    if (empty($input['title'])) {
        sendResponse(['status' => 'error', 'message' => 'Title wajib diisi'], 400);
    }
    if (empty($input['sub_title'])) {
        sendResponse(['status' => 'error', 'message' => 'Subtitle wajib diisi'], 400);
    }
    if (empty($input['image'])) {
        sendResponse(['status' => 'error', 'message' => 'File Gambar wajib diisi'], 400);
    }

    // Normalisasi & set variabel
    $title      = trim($input['title']);
    $sub_title  = trim($input['sub_title']);

    // Pastikan show_button benar2 boolean (dukung true/false, "true"/"false", 1/0, yes/no, on/off)
    $show_button = filter_var($input['show_button'] ?? false, FILTER_VALIDATE_BOOLEAN);

    // Jika Tombol Ditampilkan Maka Wajib untuk mengisi atributnya
    if ($show_button === true) {
        if (empty($input['button_url'])) {
            sendResponse(['status' => 'error', 'message' => 'URL tombol wajib diisi'], 400);
        }
        if (empty($input['button_label'])) {
            sendResponse(['status' => 'error', 'message' => 'Label tombol wajib diisi'], 400);
        }
        $button_url   = trim($input['button_url']);
        $button_label = trim($input['button_label']);
    } else {
        $button_url   = "";
        $button_label = "";
    }

    // Ambil data layout_static
    $stmt = $Conn->prepare("SELECT * FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) {
        sendResponse(['status' => 'error', 'message' => 'Data layout_static tidak ditemukan'], 404);
    }

    // Decode JSON dari DB
    $setting_value = json_decode($data['setting_value'], true);
    if (!is_array($setting_value) || json_last_error() !== JSON_ERROR_NONE) {
        sendResponse(['status' => 'error', 'message' => 'Format setting_value tidak valid'], 500);
    }

    // Hitung order baru
    $hero = $setting_value['hero'] ?? [];
    $lastOrder = 0;
    foreach ($hero as $m) {
        if (isset($m['order']) && is_numeric($m['order']) && $m['order'] > $lastOrder) {
            $lastOrder = (int)$m['order'];
        }
    }
    $newOrder = $lastOrder + 1;

    // Generate nama file
    $filename = bin2hex(random_bytes(18)) . '.png';
    // *** Sesuai permintaan, BARIS INI TIDAK DIUBAH ***
    $cover_path = '../assets/img/_component/' . $filename;

    // Tangani base64 (bersihkan prefix "data:image/...;base64,")
    $image = $input['image'];
    if (strpos($image, ',') !== false) {
        $image = explode(',', $image, 2)[1];
    }
    $cover_data = base64_decode($image, true); // strict mode
    if ($cover_data === false) {
        sendResponse(['status' => 'error', 'message' => 'Gagal decode gambar (base64 tidak valid)'], 400);
    }

    // Pastikan folder tujuan ada
    $dir = dirname($cover_path);
    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0775, true) && !is_dir($dir)) {
            sendResponse(['status' => 'error', 'message' => 'Folder penyimpanan tidak dapat dibuat'], 500);
        }
    }

    // Simpan file gambar
    $save_result = @file_put_contents($cover_path, $cover_data);
    if ($save_result === false) {
        sendResponse(['status' => 'error', 'message' => 'Gagal menyimpan gambar cover'], 500);
    }

    // Susun payload hero baru
    $button = [
        'show_button'  => $show_button,
        'button_url'   => $button_url,
        'button_label' => $button_label
    ];
    $newHero = [
        'title'     => $title,
        'sub_title' => $sub_title,
        'button'    => $button,
        'image'     => $filename,
        'order'     => $newOrder
    ];
    $hero[] = $newHero;

    // Simpan kembali ke DB
    $setting_value['hero'] = $hero;
    $newJson = json_encode($setting_value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    // Gunakan transaksi kecil agar konsisten (file sudah tersimpan; rollback => hapus file)
    try {
        $Conn->beginTransaction();

        $stmtUpdate = $Conn->prepare("UPDATE setting SET setting_value = :val WHERE id_setting = :id");
        $stmtUpdate->bindValue(':val', $newJson, PDO::PARAM_STR);
        $stmtUpdate->bindValue(':id',  $data['id_setting'], PDO::PARAM_INT);

        if (!$stmtUpdate->execute()) {
            // gagalkan & bersihkan file yang sudah dibuat
            $Conn->rollBack();
            @unlink($cover_path);
            sendResponse(['status' => 'error', 'message' => 'Gagal memperbarui database'], 500);
        }

        $Conn->commit();

        sendResponse([
            'status'  => 'success',
            'message' => 'Hero berhasil ditambahkan',
            'hero'    => $hero,
            'added'   => $newHero
        ]);
    } catch (Exception $e) {
        // Jika ada error, bersihkan file yang terlanjur tersimpan
        if (file_exists($cover_path)) {
            @unlink($cover_path);
        }
        if ($Conn->inTransaction()) {
            $Conn->rollBack();
        }
        sendResponse(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
    }
?>
