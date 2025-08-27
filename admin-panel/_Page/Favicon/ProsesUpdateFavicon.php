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
        'manifest'          => 'Nama File Manifest Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    //Buatkan Variabelnya
    $manifest=validateAndSanitizeInput($_POST['manifest']);

    //Apabila Tidak Ada File Yang Di Upload favicon16
    if(empty($_FILES['favicon16']['name'])){
        echo '<span class="text-danger">File Favicon 16 x 16 tidak boleh kosong</span>';
        exit();
    }

    //Apabila Tidak Ada File Yang Di Upload favicon32
    if(empty($_FILES['favicon32']['name'])){
        echo '<span class="text-danger">File Favicon 32 x 32 tidak boleh kosong</span>';
        exit();
    }

    //Apabila Tidak Ada File Yang Di Upload favicon180
    if(empty($_FILES['favicon180']['name'])){
        echo '<span class="text-danger">File Favicon 180 x 180 tidak boleh kosong</span>';
        exit();
    }
    //Jika Ada Lakukan Validasi gambar tidak lebih dari 2 mb dan hanya bolaeh png, jpg, jpeg, dan gif kemudian jika valid akan dibuatkan variabe $base64_string_avicon
    $uploaded_file_favicon16 = $_FILES['favicon16'];
    $uploaded_file_favicon32 = $_FILES['favicon32'];
    $uploaded_file_favicon180 = $_FILES['favicon180'];

    //Batas Maksimal Ukuran File
    $max_size = 2 * 1024 * 1024;

    //Tipe Extension File Yang diisiznkan
    $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
    
    // Validasi ukuran file
    if($uploaded_file_favicon16['size'] > $max_size) {
        die("Ukuran gambar favicon 16 x 16 terlalu besar. Maksimal 2MB.");
    }
    if($uploaded_file_favicon32['size'] > $max_size) {
        die("Ukuran gambar favicon 32 x 32 terlalu besar. Maksimal 2MB.");
    }
    if($uploaded_file_favicon180['size'] > $max_size) {
        die("Ukuran gambar favicon 180 x 180 terlalu besar. Maksimal 2MB.");
    }
    
    // Validasi tipe file
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type_favicon16 = finfo_file($file_info, $uploaded_file_favicon16['tmp_name']);
    $mime_type_favicon32 = finfo_file($file_info, $uploaded_file_favicon32['tmp_name']);
    $mime_type_favicon180 = finfo_file($file_info, $uploaded_file_favicon180['tmp_name']);
    finfo_close($file_info);
    
    if(!in_array($mime_type_favicon16, $allowed_types)) {
        die("Format gambar 16 x 16 tidak valid. Hanya diperbolehkan PNG, JPG, JPEG, atau GIF.");
    }
    if(!in_array($mime_type_favicon32, $allowed_types)) {
        die("Format gambar 32 x 32 tidak valid. Hanya diperbolehkan PNG, JPG, JPEG, atau GIF.");
    }
    if(!in_array($mime_type_favicon180, $allowed_types)) {
        die("Format gambar 180 x 180 tidak valid. Hanya diperbolehkan PNG, JPG, JPEG, atau GIF.");
    }
    
    // Baca file dan konversi ke base64
    $image_data_favicon16 = file_get_contents($uploaded_file_favicon16['tmp_name']);
    $image_data_favicon32 = file_get_contents($uploaded_file_favicon32['tmp_name']);
    $image_data_favicon180 = file_get_contents($uploaded_file_favicon180['tmp_name']);

    $base64_image_favicon16 = base64_encode($image_data_favicon16);
    $base64_image_favicon32 = base64_encode($image_data_favicon32);
    $base64_image_favicon180 = base64_encode($image_data_favicon180);

    //Buat Base64 string
    $base64_string_favicon16 = "data:$mime_type_favicon16;base64,$base64_image_favicon16";
    $base64_string_favicon32 = "data:$mime_type_favicon32;base64,$base64_image_favicon32";
    $base64_string_favicon180 = "data:$mime_type_favicon180;base64,$base64_image_favicon180";
    

    //Kirim Data Melalui service API
    
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/Favicon.php',
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
        "180x180" : "'.$base64_string_favicon180.'",
        "32x32" : "'.$base64_string_favicon32.'",
        "16x16" : "'.$base64_string_favicon16.'",
        "manifest" : "'.$manifest.'"
}',
  CURLOPT_HTTPHEADER => array(
    'x-token: '.$_SESSION["x-token"].'',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

    //Ubah response jadi arry
    $response_arry=json_decode($response, true);
    $status_response=$response_arry['status'];
    if($status_response=="success"){
        $_SESSION['NotifikasiSwal']="Favicon Berhasil Disimpan";
        echo '<span class="text-success" id="NotifikasiUpdateFaviconBerhasil">Success</span>';
        exit();
    }else{
        echo '<span class="text-danger">'.$response_arry['message'].'</span>';
        exit();
    }
?>