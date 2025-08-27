<?php
    // Aktifkan error reporting untuk debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    header('Content-Type: application/json');

    // Fungsi response JSON
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Koneksi dan fungsi
    require_once '../_Config/Connection.php';
    require_once '../_Config/Function.php';
    require_once '../_Config/log_visitor.php';

    // Validasi koneksi DB
    try {
        $Conn = (new Database())->getConnection();
    } catch (Exception $e) {
        sendResponse(['status' => 'error', 'message' => 'Koneksi DB gagal: ' . $e->getMessage()], 500);
    }

    // Ambil token dari header
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

    //Menghitung jumlah hit page
    $stm_hit = $Conn->prepare("SELECT COUNT(*) FROM visitor_logs");
    $stm_hit->execute(); 
    $total_hit = $stm_hit->fetchColumn();

    //Menghitung jumlah posting
    $stm_blog = $Conn->prepare("SELECT COUNT(*) FROM blog WHERE publish=1");
    $stm_blog->execute(); 
    $total_blog = $stm_blog->fetchColumn();

    //Menghitung Laman Mandiri
    $stm_laman = $Conn->prepare("SELECT COUNT(*) FROM  laman ");
    $stm_laman->execute(); 
    $total_laman = $stm_laman->fetchColumn();

    //Menghitung Newslatter
    $stm_newslater = $Conn->prepare("SELECT COUNT(*) FROM  newslater ");
    $stm_newslater->execute(); 
    $total_newslater = $stm_newslater->fetchColumn();

    sendResponse([
        'status' => 'success',
        'total_hit' => $total_hit,
        'total_blog' => $total_blog,
        'total_laman' => $total_laman,
        'total_newslater' => $total_newslater
    ],200);
?>