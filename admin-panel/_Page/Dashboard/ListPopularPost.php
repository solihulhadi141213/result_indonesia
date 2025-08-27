<?php
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
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class=""alert alert-danger">X-Token Gagal Dibuat!</div>
                </div>
            </div>
        ';
        exit();
    }
    $top=5;

    //Buat Playload
    $payload = [
        "top"   => $top
    ];

    //Mulai Mengirim Request Ke End Point
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/PopularPost.php',
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
        echo '
            <div class="row mb-2">
                <div class="col-12">
                    <div class=""alert alert-danger">'.$response_arry['message'].'</div>
                </div>
            </div>
        ';
        exit();
    }else{
        $data=$response_arry['data'];
        if(empty(count($data))){
            echo '
                <div class="row mb-2">
                    <div class="col-12">
                        <div class=""alert alert-danger">Tidak Ada Potingan Yang Ditampilkan</div>
                    </div>
                </div>
            ';
        }else{
            foreach ($data as $list) {
                $id_blog=$list['id_blog'];
                $title_blog=$list['title_blog'];
                $datetime_creat=$list['datetime_creat'];
                $author_blog=$list['author_blog'];

                $tanggal_post=date('d/m/Y H:i', strtotime($datetime_creat));
                echo '
                    <div class="row mb-3">
                        <div class="col-12">
                            <b>
                                <small>
                                    <a href="'.$base_url.'/Blog?id='.$id_blog.'" target="_blank">'.$title_blog.'</a>
                                </small>
                            </b><br>
                            <small>
                                '.$tanggal_post.' - '.$author_blog.'<br>

                            </small>
                        </div>
                    </div>
                ';
            }
        }
    }
?>