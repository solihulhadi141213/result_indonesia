<?php
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'pBB9mGcl7DBaSkynA75');
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
                <i class="bi bi-layers"></i> Hero/Slider</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Hero/Slider</li>
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
                $hero=$detail_layout_arry['layout_static']['hero'];

                //Tampilkan Konten
                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk mengelola tampilan Hero/Slider. 
                                    Pada halaman ini anda bisa menambahkan daftar slider secara dinamis.
                                </small>
                            </div>
                        </div>
                    </div>
                ';
                echo '
                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-lg btn-primary btn-rounded btn-block" data-bs-toggle="modal" data-bs-target="#ModalTambahHero">
                                <i class="bi bi-plus"></i> Tambah Slider
                            </button>
                        </div>
                    </div>
                ';
                // Urutkan array $hero berdasarkan key 'order' secara DESC
                usort($hero, function($a, $b) {
                    return $b['order'] <=> $a['order'];
                });

                $no=1;
                foreach ($hero as $hero_list) {
                    $order=$hero_list['order'];
                    $title=$hero_list['title'];
                    $sub_title=$hero_list['sub_title'];
                    $image=$hero_list['image'];
                    $button_url=$hero_list['button']['button_url'];
                    $show_button=$hero_list['button']['show_button'];
                    $button_label=$hero_list['button']['button_label'];

                    if($show_button==1){
                        $label_show='<span class="badge badge-success">Show</span>';
                    }else{
                        $label_show='<span class="badge badge-danger">None</span>';
                    }
                    if(empty($button_url)){
                        $button_url="-";
                    }
                    if(empty($button_label)){
                        $button_label="-";
                    }
                    //Tampilkan Daftar Slider
                    echo '
                        <div class="row mb-2">
                            <div class="col-md-12">
                               <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-10">
                                                <b class="card-title">'.$no.'. '.$title.'</b>
                                            </div>
                                            <div class="col-2 text-end">
                                                <button type="button" class="btn btn-md btn-floating btn-danger" data-bs-toggle="modal" data-bs-target="#ModalHapusHero" data-id="'.$order.'">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2 text-center mb-3">
                                                <img src="'.$base_url.'/image_proxy.php?segment=Hero&image_name='.$image.'" width="70%" class="rounded-circle">
                                            </div>
                                            <div class="col-md-10 mb-2">
                                                <div class="row mb-2">
                                                    <div class="col-4">Order ID</div>
                                                    <div class="col-1">:</div>
                                                    <div class="col-7"><small>'.$order.'</small></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4">Title</div>
                                                    <div class="col-1">:</div>
                                                    <div class="col-7"><small>'.$title.'</small></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4">Sub Title</div>
                                                    <div class="col-1">:</div>
                                                    <div class="col-7"><small>'.$sub_title.'</small></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4">Show Button</div>
                                                    <div class="col-1">:</div>
                                                    <div class="col-7"><small>'.$label_show.'</small></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4">Button URL</div>
                                                    <div class="col-1">:</div>
                                                    <div class="col-7"><small>'.$button_url.'</small></div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-4">Button Label</div>
                                                    <div class="col-1">:</div>
                                                    <div class="col-7"><small>'.$button_label.'</small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                            </div>
                        </div>
                    ';
                    $no++;
                }
            }
        ?>
    </section>
<?php } ?>