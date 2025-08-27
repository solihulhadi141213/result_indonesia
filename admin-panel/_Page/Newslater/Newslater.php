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
            <i class="bi bi-envelope"></i> Newsletter</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Newsletter</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <?php
        //Tampilkan Konten
        echo '
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <small>
                            Berikut ini adalah halaman untuk mengelola daftar newslatter yang dikirim oleh pengunjung web.
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
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="javascript:void(0);" id="ProsesMultipleData">
                                <div class="table table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th align="center">
                                                    <input class="form-check-input" type="checkbox" id="CheckAll">
                                                </th>
                                                <th align="center"><b>Tanggal</b></th>
                                                <th align="center"><b>Nama</b></th>
                                                <th align="center"><b>Email</b></th>
                                                <th align="center"><b>Opsi</b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="TabelNewslater">
                                            <tr>
                                                <td colspan="5" align="center">
                                                    <small class="text-danger">Tidak Ada Data Newslatter Yang Ditampilkan!</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm btn-rounded">
                                    <i class="bi bi-send"></i> Kirim Email
                                </button>
                            </form>
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
    ?>
</section>