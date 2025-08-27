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
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Sesi Akses Sudah Berakhir. Silahkan login ulang!
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Validasi id_blog
    if(empty($_POST['id_blog'])){
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            ID Blog Tidak Boleh Kosong!
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Validasi order_id
    if(empty($_POST['order_id'])){
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Order ID Tidak Boleh Kosong!
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Buat Variabel
    $id_blog=validateAndSanitizeInput($_POST['id_blog']);
    $order_id=validateAndSanitizeInput($_POST['order_id']);

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

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $base_url . '/_Api/DetailBlog.php?id='.$id_blog.'',
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
    $response_arry = json_decode($response, true);
    if (empty($response_arry['status']) || $response_arry['status']!=="success") {
        echo '
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Response Dari Server Gagal!<br>
                            <b>Keterangan :</b> '.$response_arry['message'].'<br>
                        </small>
                    </div>
                </div>
            </div>
        ';
    }else{
        $data=$response_arry['data'];
        $title_blog=$data['title_blog'];
        $deskripsi=$data['deskripsi'];
        $cover=$data['cover'];
        $datetime_creat=$data['datetime_creat'];
        $datetime_update=$data['datetime_update'];
        $author_blog=$data['author_blog'];
        $publish=$data['publish'];
        $content_blog=$data['content_blog'];
        $blog_tag=$data['blog_tag'];
        
        //Tampilkan FORM
        echo '<input type="hidden" name="id_blog" value="'.$id_blog.'">';
        echo '<input type="hidden" name="order_id" value="'.$order_id.'">';
        foreach ($content_blog as $content_blog_list) {
            if($content_blog_list['order_id']==$order_id){
                $content_type=$content_blog_list['type'];
                $content=$content_blog_list['content'];
                $content_order_id=$content_blog_list['order_id'];
                echo '
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="isi_content_edit">Isi Konten</label>
                            <textarea name="isi_content" id="isi_content_edit" class="form-control">'.$content.'</textarea>
                        </div>
                    </div>
                ';
            }
        }
    }
?>