<?php
    header('Content-Type: application/json');

    // Fungsi bantu untuk kirim response dengan status code
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');

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

    //Validasi X-token Tidak Ada
    if(empty($_SESSION["x-token"])){
        sendResponse(['status' => 'error', 'message' => 'X-Token Gagal Dibuat'], 401);
        exit();
    }

    //Validasi X-token Tidak Ada
    if(empty($_POST["periode"])){
        sendResponse(['status' => 'error', 'message' => 'Periode Tidak Boleh Kosong!'], 401);
        exit();
    }
    $periode=$_POST["periode"];
    if($periode=="Tahun"){
        if(empty($_POST["tahun"])){
            sendResponse(['status' => 'error', 'message' => 'Tahun Tidak Boleh Kosong!'], 401);
            exit();
        }
        $value=$_POST["tahun"];
    }else{
        if(empty($_POST["tahun"])){
            sendResponse(['status' => 'error', 'message' => 'Tahun Tidak Boleh Kosong!'], 401);
            exit();
        }
        if(empty($_POST["bulan"])){
            sendResponse(['status' => 'error', 'message' => 'Bulan Tidak Boleh Kosong!'], 401);
            exit();
        }
        $tahun=$_POST["tahun"];
        $bulan=$_POST["bulan"];
        $value="$bulan-$tahun";
    }

    //Buat Playload
    $payload = [
        "periode"   => $periode,
        "value"     => $value
    ];

    //Mulai Mengirim Request Ke End Point
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/GrafikViewer.php',
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

    //Ubah response jadi arry
    $response_arry=json_decode($response, true);
    $status_response=$response_arry['status'];

    //Apabila Status Gagal
    if($status_response!=="success"){
        sendResponse(['status' => 'error', 'message' => ''.$response_arry['message'].''], 401);
        exit();
    }else{
        echo $response;
    }
?>