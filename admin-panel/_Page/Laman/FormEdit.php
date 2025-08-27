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
    $id_laman=validateAndSanitizeInput($_POST['id']);
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
        CURLOPT_URL => ''.$base_url.'/_Api/DetailLaman.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "id_laman":"'.$id_laman.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'x-token: '.$_SESSION["x-token"].'',
            'Content-Type: text/plain'
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
        //Buka Data
        $judul_laman=$data['judul_laman'];
        $kategori_laman=$data['kategori_laman'];
        $deskripsi=$data['deskripsi'];
        $author=$data['author'];
        $cover_url=$data['cover_url'];
       
        //Tampilkan FORM
        echo '<input type="hidden" name="id_laman" value="'.$id_laman.'">';
        echo '<input type="hidden" name="mode" value="'.$mode.'">';
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="judul_laman_edit">Judul Laman</label>
                    <input type="text" name="judul_laman" id="judul_laman_edit" class="form-control" value="'.$judul_laman.'">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="kategori_laman_edit">Kategori</label>
                    <input type="text" name="kategori_laman" id="kategori_laman_edit" class="form-control" value="'.$kategori_laman.'">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="deskripsi_edit">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi_edit" class="form-control">'.$deskripsi.'</textarea>
                    <small>Ringkasan isi blog</small>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-12">
                    <label for="author_edit">Penulis/Author</label>
                    <input type="text" name="author" id="author_edit" class="form-control" value="'.$author.'">
                </div>
            </div>
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