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
        'judul_laman'       => 'Judul Laman Tidak Boleh Kosong!',
        'kategori_laman'    => 'Kategori Laman Tidak Boleh Kosong!',
        'deskripsi'         => 'Deskripsi Laman Tidak Boleh Kosong!',
        'author'            => 'Author Laman Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            echo '<span class="text-danger">' . $errorMessage . '</span>';
            exit();
        }
    }
    
    // Variabel Input
    $judul_laman    = validateAndSanitizeInput($_POST['judul_laman']);
    $kategori_laman = validateAndSanitizeInput($_POST['kategori_laman']);
    $deskripsi      = validateAndSanitizeInput($_POST['deskripsi']);
    $author         = validateAndSanitizeInput($_POST['author']);

    // Validasi Cover
    if (empty($_FILES['cover']['name'])) {
        echo '<span class="text-danger">File Cover Tidak Boleh Kosong!</span>';
        exit();
    }

    // Validasi File
    $allowed_ext = ['png', 'jpg', 'jpeg', 'gif'];
    $file_name  = $_FILES['cover']['name'];
    $file_size  = $_FILES['cover']['size'];
    $file_tmp   = $_FILES['cover']['tmp_name'];
    $file_error = $_FILES['cover']['error'];

    if ($file_error !== UPLOAD_ERR_OK) {
        echo '<span class="text-danger">Terjadi kesalahan saat upload file!</span>';
        exit();
    }

    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_ext)) {
        echo '<span class="text-danger">File hanya boleh PNG, JPG, JPEG, atau GIF!</span>';
        exit();
    }

    if ($file_size > 2 * 1024 * 1024) {
        echo '<span class="text-danger">Ukuran file tidak boleh lebih dari 2MB!</span>';
        exit();
    }

    // Ubah file ke base64
    $file_data = file_get_contents($file_tmp);
    $base64    = base64_encode($file_data);

    // JSON data untuk API
    $payload = [
        "judul_laman"       => $judul_laman,
        "kategori_laman"    => $kategori_laman,
        "cover"             => $base64,
        "deskripsi"         => $deskripsi,
        "author"            => $author
    ];

    // Kirim ke API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $base_url . '/_Api/InsertLaman.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_POST           => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER     => [
            'x-token: ' . $_SESSION["x-token"],
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    // Response
    $response_arry = json_decode($response, true);

    if (!empty($response_arry['status']) && $response_arry['status'] === "success") {
        echo '<span class="text-success" id="NotifikasiTambahBerhasil">Success</span>';
    } else {
        $msg = $response_arry['message'] ?? "Tidak ada response dari server";
        echo '<span class="text-danger">' . $msg . '</span>';
    }
    exit();
?>
