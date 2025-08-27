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

    //Mulai Mengirim Request Ke End Point
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/Dashboard.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'x-token: '.$_SESSION["x-token"].''
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

        //Apabila Berhasil Buat Variabelnya
        $total_hit=$response_arry['total_hit'];
        $total_blog=$response_arry['total_blog'];
        $total_laman=$response_arry['total_laman'];
        $total_newslater=$response_arry['total_newslater'];
        sendResponse([
            'status' => 'success',
            'total_hit' => $total_hit,
            'total_blog' => $total_blog,
            'total_laman' => $total_laman,
            'total_newslater' => $total_newslater
        ],200);
    }
?>