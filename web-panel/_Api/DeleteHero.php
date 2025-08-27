<?php
    header('Content-Type: application/json');
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';

    function sendResponse($data, $code = 200) {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Hanya izinkan DELETE
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        sendResponse(['status' => 'error', 'message' => 'Metode tidak diizinkan. Gunakan DELETE.'], 405);
    }

    // Validasi x-token dari header
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

    // Ambil JSON request body
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true);
    if (!is_array($input)) {
        sendResponse(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    // Validasi parameter "order"
    if (!isset($input['order']) || $input['order'] === '' || !is_numeric($input['order'])) {
        sendResponse(['status' => 'error', 'message' => 'Parameter "order" wajib berupa angka'], 422);
    }
    $targetOrder = (int)$input['order'];

    // Ambil layout_static
    $stmt = $Conn->prepare("SELECT * FROM setting WHERE setting_parameter = 'layout_static' LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        sendResponse(['status' => 'error', 'message' => 'Data layout_static tidak ditemukan'], 404);
    }

    // Decode JSON dari DB
    $setting = json_decode($row['setting_value'], true);
    if (!is_array($setting) || json_last_error() !== JSON_ERROR_NONE) {
        sendResponse(['status' => 'error', 'message' => 'Format setting_value tidak valid'], 500);
    }

    $hero = $setting['hero'] ?? [];
    if (!is_array($hero) || count($hero) === 0) {
        sendResponse(['status' => 'error', 'message' => 'Data hero kosong'], 404);
    }

    // Cari item dengan order yang dimaksud
    $indexToDelete = null;
    $deletedItem = null;
    foreach ($hero as $idx => $item) {
        $itemOrder = isset($item['order']) ? (int)$item['order'] : null;
        if ($itemOrder === $targetOrder) {
            $indexToDelete = $idx;
            $deletedItem = $item;
            break;
        }
    }

    if ($indexToDelete === null) {
        sendResponse(['status' => 'error', 'message' => "Hero dengan order {$targetOrder} tidak ditemukan"], 404);
    }

    // Hapus dari array
    unset($hero[$indexToDelete]);
    // Reindex array numerik
    $hero = array_values($hero);

    // Normalisasi ulang "order" agar berurutan mulai 1
    // (Jika tidak ingin menormalkan, Anda bisa komentar blok ini)
    // Urutkan berdasarkan 'order' lama untuk menjaga konsistensi relatif
    usort($hero, function($a, $b) {
        return ((int)($a['order'] ?? 0)) <=> ((int)($b['order'] ?? 0));
    });
    foreach ($hero as $i => &$h) {
        $h['order'] = $i + 1; // 1..n
    }
    unset($h);

    // Simpan kembali ke DB
    $setting['hero'] = $hero;
    $newJson = json_encode($setting, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    try {
        $Conn->beginTransaction();

        $upd = $Conn->prepare("UPDATE setting SET setting_value = :val WHERE id_setting = :id");
        $upd->bindValue(':val', $newJson, PDO::PARAM_STR);
        $upd->bindValue(':id', $row['id_setting'], PDO::PARAM_INT);

        if (!$upd->execute()) {
            $Conn->rollBack();
            sendResponse(['status' => 'error', 'message' => 'Gagal memperbarui database'], 500);
        }

        $Conn->commit();

    } catch (Exception $e) {
        if ($Conn->inTransaction()) {
            $Conn->rollBack();
        }
        sendResponse(['status' => 'error', 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
    }

    // Setelah DB sukses, coba hapus file gambar terkait (jika ada)
    $fileDeleted = null;
    if (!empty($deletedItem['image']) && is_string($deletedItem['image'])) {
        // Amankan nama file (hindari traversal)
        $imgName = $deletedItem['image'];
        $isSafe = (strpos($imgName, '..') === false) &&
                  (strpos($imgName, '/') === false) &&
                  (strpos($imgName, '\\') === false) &&
                  preg_match('/^[A-Za-z0-9._-]+$/', $imgName);

        if ($isSafe) {
            // Path penyimpanan mengikuti endpoint insert Anda
            $imgPath = '../assets/img/_component/' . $imgName; // jangan diubah
            if (file_exists($imgPath)) {
                $fileDeleted = @unlink($imgPath);
            } else {
                $fileDeleted = false; // file tidak ada; bukan fatal
            }
        } else {
            $fileDeleted = false; // nama file tidak aman; tidak dihapus
        }
    }

    sendResponse([
        'status'       => 'success',
        'message'      => "Hero dengan order {$targetOrder} berhasil dihapus",
        'deleted'      => $deletedItem,
        'reindexed'    => true,
        'file_removed' => $fileDeleted
    ]);
?>