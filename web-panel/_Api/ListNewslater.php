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

    try {
        $Conn = (new Database())->getConnection();
    } catch (Exception $e) {
        sendResponse(['status' => 'error', 'message' => 'Koneksi DB gagal: '.$e->getMessage()], 500);
    }

    // Token
    $headers = getallheaders();
    $token = $headers['x-token'] ?? $headers['X-Token'] ?? '';
    if (empty($token)) {
        sendResponse(['status' => 'error', 'message' => 'Token tidak ditemukan.'], 401);
    }

    $validasi_token = validasi_x_token($Conn, $token);
    if ($validasi_token !== "Valid") {
        sendResponse(['status' => 'error', 'message' => $validasi_token], 401);
    }

    // Input JSON
    $input      = json_decode(file_get_contents("php://input"), true);
    $order_by   = $input['order_by'] ?? "id_newslater";
    $short_by   = strtoupper($input['short_by'] ?? "DESC");
    $keyword_by = $input['keyword_by'] ?? "";
    $keyword    = $input['keyword'] ?? "";
    $limit      = (int)($input['limit'] ?? 10);
    $page       = (int)($input['page'] ?? 1);

    if ($limit <= 0) $limit = 10;
    if ($page <= 0) $page = 1;
    $offset = ($page - 1) * $limit;

    // Whitelist
    $allowedColumns = ['id_newslater','datetime','nama','email'];
    $allowedSort    = ['ASC','DESC'];

    if (!in_array($order_by, $allowedColumns)) $order_by = 'datetime';
    if (!in_array($short_by, $allowedSort)) $short_by = 'DESC';

    // Base SQL
    $sqlBase = "FROM newslater";
    $params = [];

    if (!empty($keyword_by) && in_array($keyword_by, $allowedColumns) && $keyword !== '') {
        if ($keyword_by === 'datetime') {
            $sqlBase .= " WHERE $keyword_by = :keyword";
            $params[':keyword'] = $keyword;
        } else {
            $sqlBase .= " WHERE $keyword_by LIKE :keyword";
            $params[':keyword'] = "%$keyword%";
        }
    }

    // Hitung total data
    try {
        $countSql = "SELECT COUNT(*) AS total ".$sqlBase;
        $stmtCount = $Conn->prepare($countSql);
        foreach ($params as $key => $val) {
            $stmtCount->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmtCount->execute();
        $totalData = (int)$stmtCount->fetchColumn();
    } catch (PDOException $e) {
        sendResponse(['status' => 'error', 'message' => 'Hitung total gagal: '.$e->getMessage()], 500);
    }

    $totalPages = ceil($totalData / $limit);

    // Query data dengan limit
    $sql = "SELECT * ".$sqlBase." ORDER BY $order_by $short_by LIMIT :limit OFFSET :offset";

    try {
        $stmt = $Conn->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $blog_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        sendResponse(['status' => 'error', 'message' => 'Query gagal: '.$e->getMessage()], 500);
    }

    // Format response
    $data = [];
    foreach ($blog_list as $row) {
        $data[] = [
            'id_newslater' => $row['id_newslater'],
            'datetime'     => $row['datetime'],
            'nama'         => $row['nama'],
            'email'        => $row['email']
        ];
    }

    sendResponse([
        'status'      => 'success',
        'message'     => 'Data berhasil ditampilkan.',
        'page'        => $page,
        'limit'       => $limit,
        'total_data'  => $totalData,
        'total_pages' => $totalPages,
        'data'        => $data
    ], 200);
?>
