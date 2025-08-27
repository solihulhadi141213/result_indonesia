<?php
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
            <i class="bi bi-layers"></i> Laman Mandiri</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Laman Mandiri</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <?php
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
            $berita_artikel_limit=$detail_layout_arry['layout_static']['berita_artikel']['limit'];
            $berita_artikel_title=$detail_layout_arry['layout_static']['berita_artikel']['title'];
            $berita_artikel_subtitle=$detail_layout_arry['layout_static']['berita_artikel']['subtitle'];

            //Tampilkan Konten
            echo '
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <small>
                                Berikut ini adalah halaman untuk mengelola daftar laman mandiri. 
                                Mulai buat halaman mandiri untuk ditampilkan pada website secara dinamis.
                            </small>
                        </div>
                    </div>
                </div>
            ';
            echo '
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 mb-3 text-end">
                                        <button type="button" class="btn btn-md btn-outline-dark btn-floating" data-bs-toggle="modal" data-bs-target="#ModalFilter">
                                            <i class="bi bi-funnel"></i>
                                        </button>
                                        <button type="button" class="btn btn-md btn-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambah">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" >
                                <div class="table table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th align="center"><b>No</b></th>
                                                <th align="center"><b>Tanggal</b></th>
                                                <th align="center"><b>Judul</b></th>
                                                <th align="center"><b>Penulis</b></th>
                                                <th align="center"><b>Kategori</b></th>
                                                <th align="center"><b>Opsi</b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="TabelLaman">
                                            <tr>
                                                <td colspan="6" align="center">
                                                    <small class="text-danger">Tidak Ada Data Fitur Yang Ditampilkan!</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-6">
                                        <small id="page_info">
                                            Page 1 Of 100
                                        </small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="prev_button">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info btn-floating" id="next_button">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            ';
        }
    ?>
</section>