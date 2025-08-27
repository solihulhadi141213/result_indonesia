<?php
    header('Content-Type: application/json');

    // Fungsi bantu kirim response JSON
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Koneksi Database
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';
    require_once '../_Config/log_visitor.php';

    // Tentukan base URL dinamis
    $protocol   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host       = $_SERVER['HTTP_HOST'];
    $full_path  = dirname($_SERVER['SCRIPT_NAME']);
    $base_path  = substr($full_path, 0, strpos($full_path, '/_Api'));
    $base_url   = $protocol . $host . $base_path;
    define('BASE_URL', $base_url);

    // Buat koneksi
    try {
        $Conn = (new Database())->getConnection();
    } catch (Exception $e) {
        sendResponse(['status' => 'error', 'message' => 'Koneksi DB gagal: '.$e->getMessage()], 500);
    }

    // Tangkap token dari header
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';

    if (empty($token)) {
        sendResponse(['status' => 'error', 'message' => 'Token tidak ditemukan.'], 401);
    }

    // Validasi token
    $validasi_token = validasi_x_token($Conn, $token);
    if ($validasi_token !== "Valid") {
        sendResponse(['status' => 'error', 'message' => $validasi_token], 401);
    }

    // Tangkap input JSON
    $input      = json_decode(file_get_contents("php://input"), true);
    $order_by   = $input['order_by'] ?? "datetime_creat";
    $short_by   = strtoupper($input['short_by'] ?? "DESC");
    $keyword_by = $input['keyword_by'] ?? "";
    $keyword    = $input['keyword'] ?? "";
    $limit      = (int)($input['limit'] ?? 10);
    $page       = (int)($input['page'] ?? 1);

    // Validasi nilai default
    if ($limit <= 0) $limit = 10;
    if ($page <= 0) $page = 1;
    $offset = ($page - 1) * $limit;

    // --- 🔒 Whitelist kolom dan sort ---
    $allowedColumns = ['id_blog','title_blog','deskripsi','cover','datetime_creat','datetime_update','author_blog','publish'];
    $allowedSort    = ['ASC','DESC'];

    if (!in_array($order_by, $allowedColumns)) $order_by = 'datetime_creat';
    if (!in_array($short_by, $allowedSort)) $short_by = 'DESC';

    // --- 📌 Buat query ---
    $sql = "SELECT * FROM blog";
    $params = [];

    if (!empty($keyword_by) && in_array($keyword_by, $allowedColumns) && $keyword !== '') {
        if ($keyword_by === 'datetime_creat') {
            $sql .= " WHERE $keyword_by = :keyword";
            $params[':keyword'] = $keyword;
        } elseif ($keyword_by === 'publish') {
            // khusus publish → nilai 0 atau 1
            $sql .= " WHERE $keyword_by = :keyword";
            $params[':keyword'] = (int)$keyword;
        } else {
            $sql .= " WHERE $keyword_by LIKE :keyword";
            $params[':keyword'] = "%$keyword%";
        }
    }

    $sql .= " ORDER BY $order_by $short_by LIMIT :limit OFFSET :offset";

    try {
        $stmtGaleri = $Conn->prepare($sql);

        // Bind parameter pencarian
        foreach ($params as $key => $val) {
            if ($keyword_by === 'publish') {
                $stmtGaleri->bindValue($key, $val, PDO::PARAM_INT);
            } else {
                $stmtGaleri->bindValue($key, $val, PDO::PARAM_STR);
            }
        }

        // Bind limit & offset
        $stmtGaleri->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmtGaleri->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmtGaleri->execute();
        $blog_list = $stmtGaleri->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        sendResponse(['status' => 'error', 'message' => 'Query gagal: '.$e->getMessage()], 500);
    }

    // Format data
    $data = [];
    foreach ($blog_list as $row) {
        $content_blog = json_decode($row['content_blog'], true);
        if (!is_array($content_blog)) {
            $content_blog = [];
        }
        $data[] = [
            'id_blog'         => $row['id_blog'],
            'title_blog'      => $row['title_blog'],
            'deskripsi'       => $row['deskripsi'],
            'cover'           => $row['cover'],
            'datetime_creat'  => $row['datetime_creat'],
            'datetime_update' => $row['datetime_update'],
            'author_blog'     => $row['author_blog'],
            'content_blog'    => $content_blog,
            'publish'         => (int)$row['publish']
        ];
    }

    // Kirim response
    sendResponse([
        'status'  => 'success',
        'message' => 'Blog berhasil ditampilkan.',
        'page'    => $page,
        'limit'   => $limit,
        'data'    => $data
    ], 200);
?>
