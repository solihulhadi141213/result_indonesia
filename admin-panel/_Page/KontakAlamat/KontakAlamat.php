<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'mN6oNs9BlL1pTRGPDoc');
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
                <i class="bi bi-gear"></i> Kontak & Alamat</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Kontak & Alamat</li>
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
                    $alamat_title=$detail_layout_arry['layout_static']['alamat']['title'];
                    $alamat_value=$detail_layout_arry['layout_static']['alamat']['value'];
                    $kontak_title=$detail_layout_arry['layout_static']['kontak']['title'];
                    $kontak_list=$detail_layout_arry['layout_static']['kontak']['list'];
                    
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan kontak dan alamat pada website.
                                    Isi beberapa parameter berikut ini dengan benar.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="javascript:void(0);" id="ProsesUpdateTitleKontak">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">1. Informasi Kontak</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3 border-1 border-bottom">
                                            <div class="col-md-3 mb-2">
                                                <label for="kontak_title">Title Kontak</label>
                                            </div>
                                            <div class="col-md-7 mb-2">
                                                <input type="text" name="kontak_title" id="kontak_title" class="form-control" value="<?php echo "$kontak_title"; ?>">
                                                <small id="NotifikasiUpdateTitleKontak">
                                                    <code class="text-muted">Pastikan Judul Informasi Sudah Sesuai</code>
                                                </small>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <button type="submit" class="btn btn-md btn-primary btn-rounded btn-block">
                                                    <i class="bi bi-save"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-2 mt-3">
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-md btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahKontak">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <div class="table table-responsive">
                                                    <table class="table table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <td><b>No</b></td>
                                                                <td><b>Label</b></td>
                                                                <td><b>Kontak</b></td>
                                                                <td><b>Opsi</b></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(empty(count($kontak_list))){
                                                                   echo '
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">
                                                                                <small class="text-danger">Tidak Ada Data Yang Ditampilkan</small>
                                                                            </td>
                                                                        </tr>
                                                                   ';
                                                                }else{
                                                                    $no=1;
                                                                    foreach ($kontak_list as $kontak_arry) {
                                                                        $kontak_icon=$kontak_arry['icon'];
                                                                        $kontak_value=$kontak_arry['value'];
                                                                        $kontak_order=$kontak_arry['order'];
                                                                        echo '
                                                                            <tr>
                                                                                <td><small>'.$no.'</small></td>
                                                                                <td><small>'.$kontak_icon.'</small></td>
                                                                                <td><small>'.$kontak_value.'</small></td>
                                                                                <td>
                                                                                    <a class="btn btn-sm btn-outline-dark btn-floating" href="#" data-bs-toggle="dropdown">
                                                                                        <i class="bi bi-three-dots-vertical"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                                        <li class="dropdown-header text-start"><h6>Option</h6></li>
                                                                                        <li>
                                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalEditKontak" data-id="'.$kontak_order.'">
                                                                                                <i class="bi bi-pencil"></i> Edit
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalDeleteKontak" data-id="'.$kontak_order.'">
                                                                                                <i class="bi bi-trash"></i> Delete
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </td>
                                                                            </tr>
                                                                        ';
                                                                        $no++;
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <form action="javascript:void(0);" id="ProsesUpdateAlamat">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">2. Informasi Alamat</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-md-2 mb-2">
                                                <label for="alamat_title">Title Alamat</label>
                                            </div>
                                            <div class="col-md-10 mb-2">
                                                <input type="text" name="alamat_title" id="alamat_title" class="form-control" value="<?php echo $alamat_title; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-2 mb-2">
                                                <label for="alamat_value">Value Alamat</label>
                                            </div>
                                            <div class="col-md-10 mb-2">
                                                <textarea name="alamat_value" id="alamat_value" class="form-control"><?php echo $alamat_value; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-2 mb-2"></div>
                                            <div class="col-md-10 mb-2" id="NotifikasiUpdateAlamat">
                                                <small class="text-muted">Pastikan pengaturan informasi alamat sudah sesuai</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-md btn-primary btn-rounded">
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