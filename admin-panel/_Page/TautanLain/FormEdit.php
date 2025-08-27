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
            $tautan_lainnya_list=$detail_layout_arry['layout_static']['tautan_lainnya']['list'];
            foreach ($tautan_lainnya_list as $tautan_lainnya_arry) {
                $tautan_lainnya_label=$tautan_lainnya_arry['label'];
                $tautan_lainnya_url=$tautan_lainnya_arry['url'];
                $tautan_lainnya_order=$tautan_lainnya_arry['order'];
                $tautan_lainnya_target=$tautan_lainnya_arry['target'];
                if($tautan_lainnya_order==$order){
                    echo '
                        <input type="hidden" name="tautan_lainnya_order" value="'.$tautan_lainnya_order.'">
                        <div class="row mb-2">
                            <div class="col-4 mb-2">
                                <label for="label_tautan_edit">
                                    <small class="text-muted">Label</small>
                                </label>
                            </div>
                            <div class="col-8 mb-2">
                            <input type="text" name="label_tautan" id="label_tautan_edit" class="form-control" value="'.$tautan_lainnya_label.'">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4 mb-2">
                                <label for="url_tautan_edit">
                                    <small class="text-muted">URL</small>
                                </label>
                            </div>
                            <div class="col-8 mb-2">
                            <input type="url" name="url_tautan" id="url_tautan_edit" class="form-control" placeholder="https://" value="'.$tautan_lainnya_url.'">
                            </div>
                        </div>
                    ';
                    echo '<div class="row mb-2">';
                    echo '
                            <div class="col-4 mb-2">
                                <label for="target_tautan_edit">
                                    <small class="text-muted">Target</small>
                                </label>
                            </div>
                    ';
                    echo '  <div class="col-8 mb-2">';
                    echo '      <select name="target_tautan" id="target_tautan_edit" class="form-control">';
                    echo '          <option value="">Pilih</option>';
                    if($tautan_lainnya_target=="_self"){
                        echo '<option value="_self" selected>_self</option>';
                    }else{
                        echo '<option value="_self">_self</option>';
                    }
                    if($tautan_lainnya_target=="_blank"){
                        echo '<option value="_blank" selected>_blank</option>';
                    }else{
                        echo '<option value="_blank">_blank</option>';
                    }
                    if($tautan_lainnya_target=="_parent"){
                        echo '<option value="_parent" selected>_parent</option>';
                    }else{
                        echo '<option value="_parent">_parent</option>';
                    }
                    if($tautan_lainnya_target=="_top"){
                        echo '<option value="_top" selected>_top</option>';
                    }else{
                        echo '<option value="_top">_top</option>';
                    }
                    echo '      </select>';
                    echo '  </div>';
                    echo '</div>';
                }
            }
        }
    }
?>