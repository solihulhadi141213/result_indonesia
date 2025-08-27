<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if(empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
    }

    //Cek Sesi x-token apakah ada
    if(empty($_SESSION['x-token'])){
        
        //Jika belum maka buat/generate
        $generate_x_token=generate_x_token($base_url, $user_key, $access_key);

        //Konversi ke arry
        $generate_x_token_arry=json_decode($generate_x_token,true);
        if($generate_x_token_arry['status']=='success'){
            $_SESSION["x-token"] = $generate_x_token_arry['session_token'];
            $_SESSION["x-expired_at"] = $generate_x_token_arry['expired_at'];
        }else{
            $_SESSION["x-token"] = "";
            $_SESSION["x-expired_at"] ="";
        }
    }else{
        
        //Jika sudah maka buat dalam bentuk variabel
        $session_x_token=$_SESSION['x-token'];
        $session_expired_at=$_SESSION['x-expired_at'];
        
        //Validasi x token masih berlaku atau tidak
        if($session_expired_at<=date('Y-m-d H:i:s')){
            //Jika belum maka buat/generate
            $generate_x_token=generate_x_token($base_url, $user_key, $access_key);

            //Konversi ke arry
            $generate_x_token_arry=json_decode($generate_x_token,true);
            if($generate_x_token_arry['status']=='success'){
                $_SESSION["x-token"] = $generate_x_token_arry['session_token'];
                $_SESSION["x-expired_at"] = $generate_x_token_arry['expired_at'];
            }else{
                $_SESSION["x-token"] = "";
                $_SESSION["x-expired_at"] ="";
            }
        }
    }
    if(empty($_SESSION["x-token"])){
        echo '<small class="text-danger">X-Token Gagal Dibuat</small>';
        exit();
    }
    
    // Validasi input
    $requiredFields = [
        'id_laman'          => 'ID Laman Tidak Boleh Kosong!',
        'mode'              => 'Mode Proses Tidak Boleh Kosong!',
        'judul_laman'       => 'Judul Laman Tidak Boleh Kosong!',
        'kategori_laman'    => 'Kategori Laman Tidak Boleh Kosong!',
        'deskripsi'         => 'Deskripsi Laman Tidak Boleh Kosong!',
        'author'            => 'Author Laman Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    //Buatkan Variabelnya
    $id_laman=validateAndSanitizeInput($_POST['id_laman']);
    $mode=validateAndSanitizeInput($_POST['mode']);

    // Variabel Input
    $judul_laman    = validateAndSanitizeInput($_POST['judul_laman']);
    $kategori_laman = validateAndSanitizeInput($_POST['kategori_laman']);
    $deskripsi      = validateAndSanitizeInput($_POST['deskripsi']);
    $author         = validateAndSanitizeInput($_POST['author']);

    //Jika Ada File
    if (!empty($_FILES['cover']['name'])) {
        
        // Validasi File
        $allowed_ext = ['png', 'jpg', 'jpeg', 'gif'];
        $file_name  = $_FILES['cover']['name'];
        $file_size  = $_FILES['cover']['size'];
        $file_tmp   = $_FILES['cover']['tmp_name'];
        $file_error = $_FILES['cover']['error'];

        if ($file_error !== UPLOAD_ERR_OK) {
            $validasi_cover="Terjadi kesalahan saat upload file!";
            $base64="";
        }else{
             $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_ext)) {
                $validasi_cover="File hanya boleh PNG, JPG, JPEG, atau GIF!";
                $base64="";
            }else{
                if ($file_size > 2 * 1024 * 1024) {
                    $validasi_cover="Ukuran file tidak boleh lebih dari 2MB!";
                    $base64="";
                }else{
                    // Ubah file ke base64
                    $validasi_cover="Success";
                    $file_data = file_get_contents($file_tmp);
                    $base64    = base64_encode($file_data);
                }
            }
        }
    }else{
        $validasi_cover="Success";
        $base64="";
    }

    //Jika Validasi Cover Gagal
    if($validasi_cover!=="Success"){
        echo '<span class="text-danger">'.$validasi_cover.'</span>';
        exit();
    }

    //Buat Playload
    $payload = [
        "id_laman"          => $id_laman,
        "judul_laman"       => $judul_laman,
        "kategori_laman"    => $kategori_laman,
        "deskripsi"         => $deskripsi,
        "author"            => $author,
        "cover"             => $base64
    ];
    //Kirim Data Melalui service API
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $base_url . '/_Api/UpdateLaman.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_HTTPHEADER => array(
        'x-token: ' . $_SESSION["x-token"],
        'Content-Type: text/plain'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    //Ubah response jadi arry
    $response_arry=json_decode($response, true);
    $status_response=$response_arry['status'];
    if($status_response=="success"){
        echo '<span class="text-success" id="NotifikasiEditBerhasil">Success</span>';
        echo '<span class="text-success" id="ModeReloadEdit">'.$mode.'</span>';
        exit();
    }else{
        echo '<span class="text-danger">'.$response_arry['message'].'</span>';
        exit();
    }
?>