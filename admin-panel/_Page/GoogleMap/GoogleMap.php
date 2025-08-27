<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'dFzuWyaArvLDYtMNl9N');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{
        //Include Pengaturan Koneksi
        include "_Config/SettingKoneksi.php";

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
        
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Google Map</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Google Map</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <?php
            if(empty($_SESSION["x-token"])){
                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <small>
                                   '.$generate_x_token_arry['message'].'
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                ';
            }else{
                //Buka Data Metatag
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
                    $src=$detail_layout_arry['layout_static']['google_map']['src'];
                    $class=$detail_layout_arry['layout_static']['google_map']['class'];
                    $style=$detail_layout_arry['layout_static']['google_map']['style'];
                    $width=$detail_layout_arry['layout_static']['google_map']['width'];
                    $height=$detail_layout_arry['layout_static']['google_map']['height'];
                    $loading=$detail_layout_arry['layout_static']['google_map']['loading'];
                    $referrerpolicy=$detail_layout_arry['layout_static']['google_map']['referrerpolicy'];
                    $allowfullscreen=$detail_layout_arry['layout_static']['google_map']['allowfullscreen'];
                    
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan element embed google map pada halaman web.
                                    Isi beberapa parameter berikut ini dengan benar.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="javascript:void(0);" id="ProsesUpdate">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">Informasi Visi & Misi</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="src">URL</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="src" id="src" class="form-control" value="<?php echo "$src"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="class">Class</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="class" id="class" class="form-control" value="<?php echo "$class"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="style">Style</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="style" id="style" class="form-control" value="<?php echo "$style"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="width">Width</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="width" id="width" class="form-control" value="<?php echo "$width"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="height">Height</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="height" id="height" class="form-control" value="<?php echo "$height"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="loading">Loading</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="loading" id="loading" class="form-control" value="<?php echo "$loading"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="referrerpolicy">Referrer Policy</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="referrerpolicy" id="referrerpolicy" class="form-control" value="<?php echo "$referrerpolicy"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="allowfullscreen">Allow Fullscreen</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="allowfullscreen" id="allowfullscreen" class="form-control" value="<?php echo "$allowfullscreen"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2"></div>
                                            <div class="col-md-9 mb-2">
                                                <small id="NotifikasiUpdate">
                                                    <code class="text-muted">Pastikan Informasi Visi & Misi Sudah Sesuai</code>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-md btn-primary btn-rounded btn-block">
                                            <i class="bi bi-save"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
        <?php 
                } 
            } 
        ?>
    </section>
<?php } ?>