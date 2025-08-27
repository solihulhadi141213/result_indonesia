<?php
    header('Content-Type: application/json');

    // Fungsi bantu kirim response dengan status code
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // Koneksi Database
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';
    require_once '../_Config/log_visitor.php';

    // Buat Koneksi
    $Conn = (new Database())->getConnection();

    // Tangkap token dari header
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';

    // Validasi Jika Token Kosong
    if (empty($token)) {
        sendResponse(['status' => 'error', 'message' => 'Token tidak ditemukan.'], 401);
    }

    // Validasi token dari tabel api_session
    $validasi_token = validasi_x_token($Conn, $token);
    if ($validasi_token !== "Valid") {
        sendResponse(['status' => 'error', 'message' => $validasi_token], 401);
    }

    // Hanya izinkan metode DELETE
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        sendResponse(['status' => 'error', 'message' => 'Metode request tidak diizinkan. Gunakan DELETE'], 405);
    }

    // Tangkap input JSON
    $input = json_decode(file_get_contents("php://input"), true);

    // Validasi jika JSON tidak valid atau kosong
    if (json_last_error() !== JSON_ERROR_NONE || $input === null) {
        sendResponse(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    // Validasi ID Newslater wajib ada
    if (empty($input['id_newslater'])) {
        sendResponse(['status' => 'error', 'message' => 'ID laman harus diisi'], 400);
    }

    $id_newslater = $input['id_newslater'];

    try {
        // Mulai transaksi
        $Conn->beginTransaction();

        // 1. Ambil informasi id_newslater sebelum menghapus
        $stmt = $Conn->prepare("SELECT id_newslater FROM newslater WHERE id_newslater = :id_newslater");
        $stmt->bindParam(':id_newslater', $id_newslater);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            sendResponse(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        $newslater = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_newslater = $newslater['id_newslater'];

        // 2. Hapus data dari database
        $delete_stmt = $Conn->prepare("DELETE FROM newslater WHERE id_newslater = :id_newslater");
        $delete_stmt->bindParam(':id_newslater', $id_newslater);
        $delete_result = $delete_stmt->execute();

        if (!$delete_result || $delete_stmt->rowCount() === 0) {
            throw new PDOException("Gagal menghapus data Newslater");
        }

        // Commit transaksi jika semua operasi berhasil
        $Conn->commit();

        // Kirim response sukses
        sendResponse([
            'status' => 'success',
            'message' => 'Newslater berhasil dihapus',
            'deleted_id' => $id_newslater
        ]);

    } catch (PDOException $e) {
        // Rollback transaksi jika terjadi error
        $Conn->rollBack();
        sendResponse(['status' => 'error', 'message' => 'Gagal menghapus Newslater: ' . $e->getMessage()], 500);
    }
?>