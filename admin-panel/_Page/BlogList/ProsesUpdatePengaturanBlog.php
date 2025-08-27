<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if (empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
    }

    // Cek Sesi x-token
    if (empty($_SESSION['x-token'])) {
        $generate_x_token = generate_x_token($base_url, $user_key, $access_key);
        $generate_x_token_arry = json_decode($generate_x_token, true);

        if ($generate_x_token_arry['status'] == 'success') {
            $_SESSION["x-token"]     = $generate_x_token_arry['session_token'];
            $_SESSION["x-expired_at"] = $generate_x_token_arry['expired_at'];
        } else {
            $_SESSION["x-token"] = "";
            $_SESSION["x-expired_at"] = "";
        }
    } else {
        $session_x_token   = $_SESSION['x-token'];
        $session_expired_at = $_SESSION['x-expired_at'];

        // Validasi expired
        if ($session_expired_at <= date('Y-m-d H:i:s')) {
            $generate_x_token = generate_x_token($base_url, $user_key, $access_key);
            $generate_x_token_arry = json_decode($generate_x_token, true);

            if ($generate_x_token_arry['status'] == 'success') {
                $_SESSION["x-token"]     = $generate_x_token_arry['session_token'];
                $_SESSION["x-expired_at"] = $generate_x_token_arry['expired_at'];
            } else {
                $_SESSION["x-token"] = "";
                $_SESSION["x-expired_at"] = "";
            }
        }
    }

    if (empty($_SESSION["x-token"])) {
        echo '<small class="text-danger">X-Token Gagal Dibuat</small>';
        exit();
    }

    // Validasi input
    $requiredFields = [
        'title'     => 'Title Tidak Boleh Kosong!',
        'subtitle'  => 'Sub Judul Tidak Boleh Kosong!',
        'limit'     => 'Limit Konten Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            echo '<span class="text-danger">' . $errorMessage . '</span>';
            exit();
        }
    }

    //Buat Variabel
    $title  = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $limit= $_POST['limit'];

    $payload = [
        "title"     => $title,
        "subtitle"  => $subtitle,
        "limit"     => $limit
    ];
    // Kirim ke API
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/UpdateBeritaArtikel.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_HTTPHEADER => array(
        'x-token: '.$_SESSION['x-token'].'',
        'Content-Type: text/plain'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    // Response
    $response_arry = json_decode($response, true);

    if (!empty($response_arry['status']) && $response_arry['status'] === "success") {
        echo '<span class="text-success" id="NotifikasiUpdatePengaturanBlogBerhasil">Success</span>';
    } else {
        $msg = $response_arry['message'] ?? "Tidak ada response dari server";
        echo '<span class="text-danger">' . $msg . '</span>';
    }
    exit();
?>
