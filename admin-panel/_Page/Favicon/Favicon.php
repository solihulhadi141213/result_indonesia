<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'gw5VTLLVsrfg63nfEWX');
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
                <i class="bi bi-gear"></i> Favicon</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Favicon</li>
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
                //Buka Data Favicon
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
                    $favicon16=$detail_layout_arry['layout_static']['favicon']['16x16'];
                    $favicon32=$detail_layout_arry['layout_static']['favicon']['32x32'];
                    $favicon180=$detail_layout_arry['layout_static']['favicon']['180x180'];
                    $manifest=$detail_layout_arry['layout_static']['favicon']['manifest'];
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan favicon website.
                                    Isi beberapa parameter berikut ini. Favicon yang di uplaod terdiri dari beberapa ukuran yang secara dinamis akan ditampilkan.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="javascript:void(0);" id="ProsesUpdateFavicon">
                                <input type="hidden" name="manifest" id="manifest" value="<?php echo $manifest;?>">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">Form Pengaturan Favicon</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="favicon16">Favicon (16 x 16)</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="favicon16" id="favicon16" class="form-control">
                                                <small>
                                                    Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                                    <?php
                                                        if(!empty($favicon16)){
                                                            echo '<a href="'.$base_url.'/assets/img/'.$favicon16.'" target="_blank">View Image Here</a>';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="favicon32">Favicon (32 x 32)</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="favicon32" id="favicon32" class="form-control">
                                                <small>
                                                    Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                                    <?php
                                                        if(!empty($favicon32)){
                                                            echo '<a href="'.$base_url.'/assets/img/'.$favicon32.'" target="_blank">View Image Here</a>';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="favicon180">Favicon (180 x 180)</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="favicon180" id="favicon180" class="form-control">
                                                <small>
                                                    Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                                    <?php
                                                        if(!empty($favicon180)){
                                                            echo '<a href="'.$base_url.'/assets/img/'.$favicon180.'" target="_blank">View Image Here</a>';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9 text-right" id="NotifikasiUpdateFavicon">
                                                <small class="text-dark">Pastikan pengaturan yang anda gunakan sudah sesuai.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-md btn-primary btn-rounded">
                                            <i class="bi bi-save"></i> Simpan Pengaturan
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