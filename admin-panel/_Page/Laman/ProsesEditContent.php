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

    // Cek Sesi x-token Apakah ada
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
        'id_laman'      => 'ID Laman Tidak Boleh Kosong!',
        'id_konten'     => 'ID Konten Tidak Boleh Kosong!',
        'order'         => 'Order Tidak Boleh Kosong!',
        'type'          => 'Type Tidak Boleh Kosong!',
        'isi_content'   => 'Isi Konten Tidak Boleh Kosong!',
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            echo '<span class="text-danger">' . $errorMessage . '</span>';
            exit();
        }
    }

    //Buat Variabel
    $id_laman       = $_POST['id_laman'];
    $id_konten      = $_POST['id_konten'];
    $order          = $_POST['order'];
    $type           = $_POST['type'];
    $isi_content    = $_POST['isi_content'];

    //Buat playload
    $payload = [
        "id_laman"      => $id_laman,
        "id_konten"     => $id_konten,
        "order"         => $order,
        "order_new"     => $order,
        "type"          => $type,
        "content"       => $isi_content
    ];
    // Kirim ke API
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/UpdateKontenLaman.php',
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
        $_SESSION['NotifikasiSwal']="Edit Konten Blog Berhasil";
        echo '<span class="text-success" id="NotifikasiEditContentBerhasil">Success</span>';
    } else {
        $msg = $response_arry['message'] ?? "Tidak ada response dari server";
        echo '<span class="text-danger">' . $msg . '</span>';
    }
    exit();
?>
