<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'X7PZTkMcIQgbKnjXHgh');
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
                <i class="bi bi-gear"></i> Metatag</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Metatag</li>
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
                    $meta_tag_type=$detail_layout_arry['layout_static']['meta_tag']['type'];
                    $meta_tag_title=$detail_layout_arry['layout_static']['meta_tag']['title'];
                    $meta_tag_author=$detail_layout_arry['layout_static']['meta_tag']['author'];
                    $meta_tag_robots=$detail_layout_arry['layout_static']['meta_tag']['robots'];
                    $meta_tag_base_url=$detail_layout_arry['layout_static']['meta_tag']['base_url'];
                    $meta_tag_keywords=$detail_layout_arry['layout_static']['meta_tag']['keywords'];
                    $meta_tag_ogimage=$detail_layout_arry['layout_static']['meta_tag']['og-image'];
                    $meta_tag_viewport=$detail_layout_arry['layout_static']['meta_tag']['viewport'];
                    $meta_tag_logoimage=$detail_layout_arry['layout_static']['meta_tag']['logo-image'];
                    $meta_tag_description=$detail_layout_arry['layout_static']['meta_tag']['description'];
        ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk pengaturan metatag website.
                                    Isi beberapa parameter berikut ini.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="javascript:void(0);" id="ProsesSettingMetatag">
                                <div class="card">
                                    <div class="card-header">
                                        <b class="card-title">Form Pengaturan Metatag</b>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="type_website">Type/Website</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="type_website" id="type_website" class="form-control" value="<?php echo "$meta_tag_type"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="title_website">Title Website</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="title_website" id="title_website" class="form-control" value="<?php echo "$meta_tag_title"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="author_website">Author</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="author_website" id="author_website" class="form-control" value="<?php echo "$meta_tag_author"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="robots_web">Robots</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="robots_web" id="robots_web" class="form-control" value="<?php echo "$meta_tag_robots"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="base_url_web">Base URL</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="base_url_web" id="base_url_web" class="form-control" value="<?php echo "$meta_tag_base_url"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="keyword_web">Keyword</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="keyword_web" id="keyword_web" class="form-control" value="<?php echo "$meta_tag_keywords"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="viewport_web">Viewport</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="text" name="viewport_web" id="viewport_web" class="form-control" value="<?php echo "$meta_tag_viewport"; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="description_web">Description</label>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea name="description_web" id="description_web" class="form-control"><?php echo "$meta_tag_description"; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="meta_tag_ogimage">OG-Image</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="meta_tag_ogimage" id="meta_tag_ogimage" class="form-control">
                                                <input type="hidden" name="url_ogimage" id="url_ogimage" class="form-control" value="<?php if(!empty($meta_tag_ogimage)){echo ''.$base_url.'/assets/img/'.$meta_tag_ogimage.'';} ?>">
                                                <small>
                                                    Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                                    <?php
                                                        if(!empty($meta_tag_ogimage)){
                                                            echo '<a href="'.$base_url.'/assets/img/'.$meta_tag_ogimage.'" target="_blank">View Image Here</a>';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="meta_tag_logoimage">Logo-Image</label>
                                            </div>
                                            <div class="col-md-9">
                                                <input type="file" name="meta_tag_logoimage" id="meta_tag_logoimage" class="form-control">
                                                <input type="hidden" name="url_logoimage" id="url_logoimage" class="form-control" value="<?php if(!empty($meta_tag_logoimage)){echo ''.$base_url.'/assets/img/'.$meta_tag_logoimage.'';} ?>">
                                                <small>
                                                    Maksimal File 2 Mb (JPG, JPEG, PNG and GIF)
                                                    <?php
                                                        if(!empty($meta_tag_logoimage)){
                                                            echo '<a href="'.$base_url.'/assets/img/'.$meta_tag_logoimage.'" target="_blank">View Image Here</a>';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9 text-right" id="NotifikasiSimpanMetatag">
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