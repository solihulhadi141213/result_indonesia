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
            <i class="bi bi-layers"></i> Blog List</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Blog List</li>
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
                                Berikut ini adalah halaman untuk mengelola daftar konten blog. 
                                Mulai posting artikel/berita anda di halaman ini.
                            </small>
                        </div>
                    </div>
                </div>
            ';
            echo '
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="javascript:void(0);" id="ProsesUpdatePengaturanBlog">
                            <div class="card">
                                <div class="card-header">
                                    <b class="card-title">Pengaturan Tampilan</b>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-3">
                                            <label for="title">Judul</label>
                                        </div>
                                        <div class="col-1">:</div>
                                        <div class="col-8">
                                            <input type="text" name="title" id="title" class="form-control" value="'.$berita_artikel_title.'">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-3">
                                            <label for="subtitle">Sub Judul</label>
                                        </div>
                                        <div class="col-1">:</div>
                                        <div class="col-8">
                                            <input type="text" name="subtitle" id="subtitle" class="form-control" value="'.$berita_artikel_subtitle.'">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-3">
                                            <label for="limit">Limit/Halaman</label>
                                        </div>
                                        <div class="col-1">:</div>
                                        <div class="col-8">
                                            <input type="number" name="limit" id="limit" class="form-control" value="'.$berita_artikel_limit.'">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12" id="NotifikasiUpdatePengaturanBlog"></div>
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
                                                <th align="center"><b>Status</b></th>
                                                <th align="center"><b>Opsi</b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="TabelBlog">
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