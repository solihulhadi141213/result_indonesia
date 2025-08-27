<div class="pagetitle">
    <h1>
        <a href="">
            <i class="bi bi-info-circle"></i> Detail Posting</a>
        </a>
    </h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Detail Posting</li>
        </ol>
    </nav>
</div>
<section class="section dashboard">
    <?php
        //Tangkap ID dengan GET
        if(empty($_GET['id'])){
            echo '
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <small>
                                ID Konten Blog Tidak Boleh Kosong
                            </small>
                        </div>
                    </div>
                </div>
            ';
        }else{
            $id_blog=$_GET['id'];
            
            //Bersihkan variabel
            $id_blog=validateAndSanitizeInput($id_blog);

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

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $base_url . '/_Api/DetailBlog.php?id='.$id_blog.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-token: '.$_SESSION["x-token"].''
            ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response_arry = json_decode($response, true);
            if (empty($response_arry['status']) || $response_arry['status']!=="success") {
                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <small>
                                    Response Dari Server Gagal!<br>
                                    <b>Keterangan :</b> '.$response_arry['message'].'<br>
                                </small>
                            </div>
                        </div>
                    </div>
                ';
            }else{
                $data=$response_arry['data'];
                $title_blog=$data['title_blog'];
                $deskripsi=$data['deskripsi'];
                $cover=$data['cover'];
                $datetime_creat=$data['datetime_creat'];
                $datetime_update=$data['datetime_update'];
                $author_blog=$data['author_blog'];
                $publish=$data['publish'];
                $content_blog=$data['content_blog'];
                $blog_tag=$data['blog_tag'];

                //Ubah Blog Tag menjadi arry
                $tag=implode(", ", $blog_tag);
                //Routing Publish Label
                if($publish==1){
                    $label_status='<span class="badge badge-success">Publish</span>';
                }else{
                    $label_status='<span class="badge badge-danger">Draft</span>';
                }
                //Alert Opening
                echo '
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <small>
                                    Berikut ini adalah halaman untuk yang menampilkan detail posting. 
                                    Pada halaman ini anda bisa mengelola isi dari konten tersebut.
                                </small>
                            </div>
                        </div>
                    </div>
                ';

                //Menampilkan Card
                echo '<div class="card">';
                echo '  <div class="card-header">
                            <div class="row">
                                <div class="col-12 text-end">
                                    <a href="index.php?Page=BlogList" class="btn btn-md btn-dark btn-floating">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                    <button type="button" class="btn btn-md btn-info btn-floating" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="'.$id_blog.'" data-mode="Reload">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-md btn-danger btn-floating" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="' . $id_blog . '" data-mode="Reload">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                ';
                echo ' <div class="card-body">';
                echo '
                        <div class="row mb-3 border-i border-bottom">
                            <div class="col-md-3 mb-3">
                                <img src="'.$base_url.'/image_proxy.php?segment=Artikel&image_name='.$cover.'" width="100%">
                            </div>
                            <div class="col-md-1 mb-3"></div>
                            <div class="col-md-8 mb-3">
                                <div class="row mb-2">
                                    <div class="col-3">Judul</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small>'.$title_blog.'</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3">Label/Tag</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small>'.$tag.'</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3">Deskripsi</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small class="text text-grayish">'.$deskripsi.'</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3">Posting</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small>'.$datetime_creat.'</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3">Update</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small>'.$datetime_update.'</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3">Penulis</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small>'.$author_blog.'</small></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-3">Status</div>
                                    <div class="col-1">:</div>
                                    <div class="col-8"><small>'.$label_status.'</small></div>
                                </div>
                            </div>
                        </div>
                ';
                echo '
                    <div class="row mb-3 mt-3">
                        <div class="col-md-12 mb-2 text-center">
                            <button type="button" class="btn btn-md btn-secondary btn-rounded btn-block" data-bs-toggle="modal" data-bs-target="#ModalTambahKonten" data-id="'.$id_blog.'" data-order="1">
                                <i class="bi bi-plus"></i> Tambah Konten
                            </button>
                        </div>
                    </div>
                ';

                //Menampilkan Blog Posting
                foreach ($content_blog as $content_blog_list) {
                    $content_type=$content_blog_list['type'];
                    $content=$content_blog_list['content'];
                    $content_order_id=$content_blog_list['order_id'];
                    $content_order_id_plus=$content_order_id+1;

                    //Apabila tipe image
                    if($content_type=="image"){
                        $unit=$content_blog_list['unit'];
                        $width=$content_blog_list['width'];
                        $position=$content_blog_list['position'];
                        $content='<img src="'.$base_url.'/image_proxy.php?segment=Artikel&image_name='.$content.'" width="'.$width.''.$unit.'">';
                        echo '
                            <div class="row mb-3 mt-3">
                                <div class="col-md-10 mb-2 text-'.$position.'">'.$content.'</div>
                                <div class="col-md-2 mb-2 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahKonten" data-id="'.$id_blog.'" data-order="'.$content_order_id_plus.'">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating" data-bs-toggle="modal" data-bs-target="#ModalEditContent" data-id="'.$id_blog.'" data-order="'.$content_order_id.'">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-floating" data-bs-toggle="modal" data-bs-target="#ModalHapusContent" data-id="'.$id_blog.'" data-order="'.$content_order_id.'">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        ';
                    }else{
                        echo '
                            <div class="row mb-3 mt-3">
                                <div class="col-md-10 mb-2">'.$content.'</div>
                                <div class="col-md-2 mb-2 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-floating" data-bs-toggle="modal" data-bs-target="#ModalTambahKonten" data-id="'.$id_blog.'" data-order="'.$content_order_id_plus.'">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-dark btn-floating" data-bs-toggle="modal" data-bs-target="#ModalEditContent" data-id="'.$id_blog.'" data-order="'.$content_order_id.'">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-floating" data-bs-toggle="modal" data-bs-target="#ModalHapusContent" data-id="'.$id_blog.'" data-order="'.$content_order_id.'">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        ';
                    }
                }

                echo '</div>';
                echo '</div>';
            }
        }
    ?>
</section>