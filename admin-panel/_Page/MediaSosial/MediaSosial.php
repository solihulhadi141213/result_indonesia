<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'UmIs7Eqn1Tp0Y1CfVR3');
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
                <i class="bi bi-gear"></i> Media Sosial</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Media Sosial</li>
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
                    $medsos_title=$detail_layout_arry['layout_static']['media_sosial']['title'];
                    $medsos_list=$detail_layout_arry['layout_static']['media_sosial']['list'];
                    
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan media sosial pada website.
                                    Isi beberapa parameter berikut ini dengan benar.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="javascript:void(0);" id="ProsesUpdateMedsos">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">Informasi Media Sosial</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3 border-1 border-bottom">
                                            <div class="col-md-3 mb-2">
                                                <label for="medsos_title">Title Medsos</label>
                                            </div>
                                            <div class="col-md-7 mb-2">
                                                <input type="text" name="medsos_title" id="medsos_title" class="form-control" value="<?php echo "$medsos_title"; ?>">
                                                <small id="NotifikasiUpdateTitleMedsos">
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
                                                <button type="button" class="btn btn-md btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahMedsos">
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
                                                                <td><b>Icon</b></td>
                                                                <td><b>URL</b></td>
                                                                <td><b>Opsi</b></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(empty(count($medsos_list))){
                                                                   echo '
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">
                                                                                <small class="text-danger">Tidak Ada Data Yang Ditampilkan</small>
                                                                            </td>
                                                                        </tr>
                                                                   ';
                                                                }else{
                                                                    $no=1;
                                                                    foreach ($medsos_list as $medsos_arry) {
                                                                        $medsos_icon=$medsos_arry['icon'];
                                                                        $medsos_url=$medsos_arry['url'];
                                                                        $medsos_order=$medsos_arry['order'];
                                                                        echo '
                                                                            <tr>
                                                                                <td><small>'.$no.'</small></td>
                                                                                <td><small>'.$medsos_icon.'</small></td>
                                                                                <td><small>'.$medsos_url.'</small></td>
                                                                                <td>
                                                                                    <a class="btn btn-sm btn-outline-dark btn-floating" href="#" data-bs-toggle="dropdown">
                                                                                        <i class="bi bi-three-dots-vertical"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                                        <li class="dropdown-header text-start"><h6>Option</h6></li>
                                                                                        <li>
                                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$medsos_order.'">
                                                                                                <i class="bi bi-pencil"></i> Edit
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalDelete" data-id="'.$medsos_order.'">
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
        <?php 
                } 
            } 
        ?>
    </section>
<?php } ?>