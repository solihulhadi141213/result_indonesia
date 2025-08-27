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

    // Validasi input wajib
    $requiredFields = [
        'title'         => 'Title Tidak Boleh Kosong!',
        'sub_title'     => 'Sub Titlw Tidak Boleh Kosong!',
        'show_button'   => 'Tampilan Tombol Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            echo '<span class="text-danger">' . $errorMessage . '</span>';
            exit();
        }
    }

    //Buat Variabel
    $title  = validateAndSanitizeInput($_POST['title']);
    $sub_title = validateAndSanitizeInput($_POST['sub_title']);
    $show_button= validateAndSanitizeInput($_POST['show_button']);

    //Jika Show_button tampilkan
    $button_url="";
    $button_label="";
    if($show_button=="Tampilkan"){
        if(empty($_POST['button_url'])){
            $validasi_tombol="URL Tombol Tidak Boleh Kosong!";
        }else{
            if(empty($_POST['button_label'])){
                $validasi_tombol="Label Tombol Tidak Boleh Kosong!";
            }else{
                $validasi_tombol="Valid";
                $button_url=$_POST['button_url'];
                $button_label=$_POST['button_label'];
            }
        }
    }else{
        $validasi_tombol="Valid";
    }

    // Jika Validasi Tombol Tidak Valid
    if($validasi_tombol!=="Valid"){
        echo '<span class="text-danger">' . $validasi_tombol . '</span>';
        exit();
    }

    // Validasi Latar Belakang
    if (empty($_FILES['image']['name'])) {
        echo '<span class="text-danger">File Latar Belakang Tidak Boleh Kosong!</span>';
        exit();
    }

    // Validasi File
    $allowed_ext = ['png', 'jpg', 'jpeg', 'gif'];
    $file_name  = $_FILES['image']['name'];
    $file_size  = $_FILES['image']['size'];
    $file_tmp   = $_FILES['image']['tmp_name'];
    $file_error = $_FILES['image']['error'];
    $base64="";
    if ($file_error !== UPLOAD_ERR_OK) {
        $validasi_konten="Terjadi kesalahan saat upload file!";
    }else{
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            $validasi_konten="File hanya boleh PNG, JPG, JPEG, atau GIF!";
        }else{
            if ($file_size > 2 * 1024 * 1024) {
                $validasi_konten="Ukuran file tidak boleh lebih dari 2MB!";
            }else{
                $validasi_konten="Valid";
                        
                // Ubah file ke base64
                $file_data = file_get_contents($file_tmp);
                $base64    = base64_encode($file_data);
            }
        }
    }
    if($validasi_konten!=="Valid"){
        echo '<span class="text-danger">' . $validasi_konten . '</span>';
        exit();
    }

    //Routing $show_button
    if($show_button=="Tampilkan"){
        $show_button=true;
    }else{
        $show_button=false;
    }
    $payload = [
        "title"         => $title,
        "sub_title"     => $sub_title,
        "show_button"   => $show_button,
        "button_url"    => $button_url,
        "button_label"  => $button_label,
        "image"         => $base64
    ];
    // Kirim ke API
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/InsertHero.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
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
        echo '<span class="text-success" id="NotifikasiTambahHeroBerhasil">Success</span>';
    } else {
        $msg = $response_arry['message'] ?? "Tidak ada response dari server";
        echo '<span class="text-danger">' . $msg . '</span>';
    }
    exit();
?>
