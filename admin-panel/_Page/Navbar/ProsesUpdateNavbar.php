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

    //Cek Sesi x-token
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
        'navbar_title'      => 'Judul Navbar Tidak Boleh Kosong!',
        'url_navbar_logo'      => 'URL Logo Navbar Tidak Boleh Kosong!',
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    //Buatkan Variabelnya
    $navbar_title=validateAndSanitizeInput($_POST['navbar_title']);
    $url_navbar_logo=validateAndSanitizeInput($_POST['url_navbar_logo']);

    //Apabila Tidak Ada File Yang Di Upload
    if(empty($_FILES['navbar_logo']['name'])){
        
        //Ubah $url_navbar_logo menjadi base 64
        $image_data= file_get_contents($url_navbar_logo);

        if ($image_data === false) {
            die("Gagal mengambil gambar dari URL");
        }

        // Mengkonversi ke base64
        $base64_image = base64_encode($image_data);

        // Menyimpan dalam variabel (opsional menentukan tipe MIME)

        if ($image_data === false) {
            die("Gagal mengambil gambar dari URL");
        }

        // Gunakan finfo untuk deteksi MIME type dari data biner
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_buffer($finfo, $image_data);
        finfo_close($finfo);

        $base64_image = base64_encode($image_data);
        $base64_string = "data:$mime_type;base64,$base64_image";

    }else{
        //Jika Ada Lakukan Validasi gambar tidak lebih dari 2 mb dan hanya bolaeh png, jpg, jpeg, dan gif kemudian jika valid akan dibuatkan variabe $base64_string_ogimage
        $uploaded_file = $_FILES['navbar_logo'];
        $max_size = 2 * 1024 * 1024; // 2 MB
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        
        // Validasi ukuran file
        if($uploaded_file['size'] > $max_size) {
            die("Ukuran gambar terlalu besar. Maksimal 2MB.");
        }
        
        // Validasi tipe file
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $uploaded_file['tmp_name']);
        finfo_close($file_info);
        
        if(!in_array($mime_type, $allowed_types)) {
            die("Format gambar tidak valid. Hanya diperbolehkan PNG, JPG, JPEG, atau GIF.");
        }
        
        // Baca file dan konversi ke base64
        $image_data = file_get_contents($uploaded_file['tmp_name']);
        $base64_image = base64_encode($image_data);
        $base64_string = "data:$mime_type;base64,$base64_image";
    }

    //Kirim Data Melalui service API
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/UpdateNavbar.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS =>'{
        "logo-image": "'.$base64_string.'",
        "title": "'.$navbar_title.'"
    }',
    CURLOPT_HTTPHEADER => array(
        'x-token: '.$_SESSION["x-token"] .'',
        'Content-Type: application/json'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    
    //Ubah response jadi arry
    $response_arry=json_decode($response, true);
    $status_response=$response_arry['status'];
    if($status_response=="success"){
        $_SESSION['NotifikasiSwal']="Navbar Berhasil Disimpan";
        echo '<span class="text-success" id="NotifikasiUpdateNavbarBerhasil">Success</span>';
        exit();
    }else{
        echo '<span class="text-danger">'.$response_arry['message'].'</span>';
        exit();
    }
?>