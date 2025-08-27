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
        'src'       => 'URL Google Map Tidak Boleh Kosong!',
        'width'     => 'Ukuran Lebar Tidak Boleh Kosong!',
        'height'    => 'Ukuran Tinggi Tidak Boleh Kosong!',
        'style'     => 'Atribut Style Tidak Boleh Kosong!',
        'class'     => 'Atribut Class Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    //Buatkan Variabelnya
    $src=validateAndSanitizeInput($_POST['src']);
    $width=validateAndSanitizeInput($_POST['width']);
    $height=validateAndSanitizeInput($_POST['height']);
    $style=validateAndSanitizeInput($_POST['style']);
    $class=validateAndSanitizeInput($_POST['class']);
    
    //Variabel Tidak Wajib
    if(empty($_POST['allowfullscreen'])){
        $allowfullscreen="";
    }else{
        $allowfullscreen=$_POST['allowfullscreen'];
    }
    if(empty($_POST['loading'])){
        $loading="";
    }else{
        $loading=$_POST['loading'];
    }
    if(empty($_POST['referrerpolicy'])){
        $referrerpolicy="";
    }else{
        $referrerpolicy=$_POST['referrerpolicy'];
    }
    
    // Siapkan payload array
    $payload = [
        "src"               => $src,
        "width"             => $width,
        "height"            => $height,
        "style"             => $style,
        "allowfullscreen"   => $allowfullscreen,
        "loading"           => $loading,
        "referrerpolicy"    => $referrerpolicy,
        "class"             => $class
    ];

    // Ubah jadi JSON aman
    $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

    //Kirim Data Melalui service API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => $base_url.'/_Api/UpdateGoogleMap.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING       => '',
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_POSTFIELDS     => $jsonPayload,
        CURLOPT_HTTPHEADER     => [
            'x-token: '.$_SESSION["x-token"],
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    //Ubah response jadi arry
    $response_arry=json_decode($response, true);
    $status_response=$response_arry['status'];
    if($status_response=="success"){
        $_SESSION['NotifikasiSwal']="Google Map Berhasil Disimpan";
        echo '<span class="text-success" id="NotifikasiUpdateBerhasil">Success</span>';
        exit();
    }else{
        echo '<span class="text-danger">'.$response_arry['message'].'</span>';
        exit();
    }
?>