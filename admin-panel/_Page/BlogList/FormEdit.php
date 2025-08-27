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

    //Validasi ID
    if(empty($_POST['id'])){
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            ID Tidak Boleh Kosong!
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Validasi Mode
    if(empty($_POST['mode'])){
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Mode Tidak Boleh Kosong!
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Buat Variabel
    $id_blog=validateAndSanitizeInput($_POST['id']);
    $mode=validateAndSanitizeInput($_POST['mode']);

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
        $tag=implode(", ", $blog_tag);
        //Tampilkan FORM
        echo '<input type="hidden" name="id_blog" value="'.$id_blog.'">';
        echo '<input type="hidden" name="mode" value="'.$mode.'">';
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="title_blog_edit">Judul Posting</label>
                    <input type="text" name="title_blog" id="title_blog_edit" class="form-control" value="'.$title_blog.'">
                </div>
            </div>
        ';
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="tag_edit">Label/Tag</label>
                    <input type="text" name="tag" id="tag_edit" class="form-control" value="'.$tag.'">
                    <small>Pisahkan dengan koma (,)</small>
                </div>
            </div>
        ';
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control">'.$deskripsi.'</textarea>
                    <small>Ringkasan isi blog</small>
                </div>
            </div>
        ';
        echo '<div class="row mb-2">';
        echo '
            <div class="col-md-6">
                <label for="author_blog_edit">Penulis/Author</label>
                <input type="text" name="author_blog" id="author_blog_edit" class="form-control" value="'.$author_blog.'">
            </div>
        ';
        echo '<div class="col-md-6">';
        echo '  <label for="publish">Publis/Draft</label>';
        echo '  <select name="publish" id="publish" class="form-control">';
        if($publish==0){
            echo '<option selected value="0">Draft</option>';
        }else{
            echo '<option value="0">Draft</option>';
        }
        if($publish==1){
            echo '<option selected value="1">Publish</option>';
        }else{
            echo '<option value="1">Publish</option>';
        }
        echo '  </select>';
        echo '</div>';
        echo '</div>';
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="cover_edit">Cover</label>
                    <input type="file" name="cover" id="cover_edit" class="form-control">
                    <small>PNG, JPG, JPEG</small>
                </div>
            </div>
        ';
    }
?>