<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'TSeinPEmNJZd8e0ZCOn');
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
                <i class="bi bi-gear"></i> Visi & Misi</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Visi & Misi</li>
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
                    $title_visi_misi=$detail_layout_arry['layout_static']['visi_misi']['title'];
                    $visi=$detail_layout_arry['layout_static']['visi_misi']['visi'];
                    $misi=$detail_layout_arry['layout_static']['visi_misi']['misi'];
                    $motto=$detail_layout_arry['layout_static']['visi_misi']['motto'];
                    
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan visi, misi dan moto pada halaman web.
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
                                                <label for="title_visi_misi">Title/Judul</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <input type="text" name="title_visi_misi" id="title_visi_misi" class="form-control" value="<?php echo "$title_visi_misi"; ?>">
                                                
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="visi">Visi</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <textarea name="visi" id="visi" class="form-control"><?php echo "$visi"; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="misi">Misi</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <textarea name="misi" id="misi" class="form-control"><?php echo "$misi"; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3 mb-2">
                                                <label for="motto">Motto</label>
                                            </div>
                                            <div class="col-md-9 mb-2">
                                                <textarea name="motto" id="motto" class="form-control"><?php echo "$motto"; ?></textarea>
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