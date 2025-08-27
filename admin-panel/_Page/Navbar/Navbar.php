<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'ODh6Ic6cUDteqzp2Kdn');
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
                <i class="bi bi-gear"></i> Navbar</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Navbar</li>
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
                    $navbar_title=$detail_layout_arry['layout_static']['navbar']['title'];
                    $navbar_logo=$detail_layout_arry['layout_static']['navbar']['logo-image'];
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan navbar pada website.
                                    Isi beberapa parameter berikut ini. Jangan lupa menerapkan logo untuk website.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="javascript:void(0);" id="ProsesUpdateNavbar">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">Pengaturan Navbar</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="navbar_title">Judul Navbar</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="navbar_title" id="navbar_title" class="form-control" value="<?php echo "$navbar_title"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="navbar_logo">Logo Navbar</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="navbar_logo" id="navbar_logo" class="form-control">
                                                <input type="hidden" name="url_navbar_logo" id="url_navbar_logo" class="form-control" value="<?php if(!empty($navbar_logo)){echo ''.$base_url.'/assets/img/'.$navbar_logo.'';} ?>">
                                                <small>
                                                    Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                                    <?php
                                                        if(!empty($navbar_logo)){
                                                            echo '<a href="'.$base_url.'/assets/img/'.$navbar_logo.'" target="_blank">View Image Here</a>';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9 text-right" id="NotifikasiUpdateNavbar">
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