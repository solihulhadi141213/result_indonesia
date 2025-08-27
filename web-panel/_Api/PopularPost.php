<?php
    header('Content-Type: application/json');

    // Fungsi bantu kirim response
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
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

    // Validasi token
    $validasi_token = validasi_x_token($Conn, $token);
    if ($validasi_token !== "Valid") {
        sendResponse(['status' => 'error', 'message' => $validasi_token], 401);
    }

    // Hanya izinkan metode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(['status' => 'error', 'message' => 'Metode request tidak diizinkan. Gunakan POST'], 405);
    }

    // Tangkap input JSON
    $input = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE || $input === null) {
        sendResponse(['status' => 'error', 'message' => 'Format JSON tidak valid'], 400);
    }

    if (empty($input['top'])) {
        sendResponse(['status' => 'error', 'message' => 'Limit Populer Post Data Harus Diisi'], 400);
    }
    $top = (int)$input['top'];

    try {
        // Query untuk ambil populer post berdasarkan jumlah viewer terbanyak
        $sql = "
            SELECT 
                b.id_blog,
                b.title_blog,
                b.deskripsi,
                b.cover,
                b.datetime_creat,
                b.author_blog,
                COUNT(v.id_blog) AS total_view
            FROM blog b
            LEFT JOIN blog_viewer v ON b.id_blog = v.id_blog
            WHERE b.publish = 1
            GROUP BY b.id_blog
            ORDER BY total_view DESC
            LIMIT :top
        ";
        $stmt = $Conn->prepare($sql);
        $stmt->bindValue(':top', $top, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        sendResponse([
            'status' => 'success',
            'data' => $result
        ]);
    } catch (Exception $e) {
        sendResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
?>
