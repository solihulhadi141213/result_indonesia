<?php
    //Koneksi
    include "../../_Config/Connection.php";

    //Function
    include "../../_Config/GlobalFunction.php";
   
    //Setting General
    include "../../_Config/SettingGeneral.php";
    
    //Koneksi
    include "../../_Config/SettingKoneksi.php";

    //Session Akses
    include "../../_Config/Session.php";

    //Cek Apakah sudah mempunyai sesi token sebelumnya
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

    if(empty($_POST['order_id'])){
        echo '
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Order ID Tidak Boleh Kosong
                        </small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        ';
    }else{
        $order=$_POST['order_id'];
        
        //Buka Data Medsos
        $detail_layout=DetailLayout($base_url, $_SESSION["x-token"]);

        //Konversi ke arry
        $detail_layout_arry=json_decode($detail_layout, true);

        //Jika Gagal menampilkan detail layout
        if($detail_layout_arry['status']!=="success"){
            echo '
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <small>
                            '.$detail_layout_arry['message'].'
                            </small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            ';
        }else{
            $medsos_list=$detail_layout_arry['layout_static']['media_sosial']['list'];
            foreach ($medsos_list as $medsos_arry) {
                $medsos_icon=$medsos_arry['icon'];
                $medsos_url=$medsos_arry['url'];
                $medsos_order=$medsos_arry['order'];
                $medsos_icon2 = addslashes($medsos_icon);
                $medsos_icon2=validateAndSanitizeInput($medsos_icon2);
                if($medsos_order==$order){
                    echo '
                        <input type="hidden" name="medsos_order" value="'.$medsos_order.'">
                        <div class="row mb-2">
                            <div class="col-4 mb-2">
                                <label for="icon_medsos_edit">
                                    <small class="text-muted">Label/Icon</small>
                                </label>
                            </div>
                            <div class="col-8 mb-2">
                                <input type="text" name="icon_medsos" id="icon_medsos_edit" class="form-control" value="'.$medsos_icon2.'">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 mb-2">
                                <label for="url_medsos_edit">
                                    <small class="text-muted">URL Medsos</small>
                                </label>
                            </div>
                            <div class="col-8 mb-2">
                                <input type="text" name="url_medsos" id="url_medsos_edit" class="form-control" value="'.$medsos_url.'">
                            </div>
                        </div>
                    ';
                }
            }
        }
    }
?>