<?php
    if(empty($_GET['id'])){
        $id_laman ="";
        $title_page_laman="Laman Mandiri";
    }else{
        $id_laman =$_GET['id'];
        //Buka Detail Laman
        $sql = "SELECT * FROM laman WHERE id_laman ='$id_laman'";
        $stmt = $Conn->prepare($sql);
        $stmt->execute();
        $laman_list = $stmt->fetchAll();
        if (count($laman_list) > 0) {
            foreach ($laman_list as $laman) {
                $judul_laman = $laman['judul_laman'];
                $kategori_laman = $laman['kategori_laman'];
                $datetime_laman = date('d/m/Y H:i T',strtotime($laman['datetime_laman']));
                $cover = $laman['cover'];
                $deskripsi = $laman['deskripsi'];
                $author = $laman['author'];
                $konten = $laman['konten'];
                //Inisiasi Cover
                $cover_image = 'image_proxy.php?segment=Laman&image_name=' . $cover;

                $title_page_laman="Laman Mandiri";
            }
        }else{
            $id_laman ="";
            $title_page_laman="Laman Mandiri";
        }
    }
?>

<section class="breadcrumb-section">
   
</section>
<section class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $title_page_laman; ?></li>
            </ol>
        </nav>
    </div>
    <div class="container">
        <div class="row mt-3 mb-3">
            <div class="col-12">
                <h2 class="h1 mb-4 title_segment text-light"><?php echo $title_page_laman; ?></h2>
            </div>
        </div>
    </div>
</section>
<section class="section bg-white p-4">
    <div class="container">
        <div class="row mb-3 mt-4">
            <div class="col-md-12 mb-4">
                <?php
                    if(empty($_GET['id'])){
                        //Menampilkan List Laman
                        $sql = "SELECT * FROM laman";
                        $stmt = $Conn->prepare($sql);
                        $stmt->execute();
                        $laman_list = $stmt->fetchAll();
                        if (count($laman_list) > 0) {
                            foreach ($laman_list as $laman) {
                                $id_laman = $laman['id_laman'];
                                $judul_laman = $laman['judul_laman'];
                                $kategori_laman = $laman['kategori_laman'];
                                $datetime_laman = date('d/m/Y H:i T',strtotime($laman['datetime_laman']));
                                $cover = $laman['cover'];
                                $deskripsi = $laman['deskripsi'];
                                $author = $laman['author'];
                                $konten = $laman['konten'];

                                //Inisiasi Cover
                                $cover_image = 'image_proxy.php?segment=Laman&image_name=' . $cover;

                                //Tampilkan Data
                                echo '
                                    <div class="d-flex mb-4 border-bottom pb-3">
                                        <div class="me-3" style="flex: 0 0 150px;">
                                            <img src="'.$cover_image.'" alt="Cover" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1">
                                                <a href="Laman?id='.$id_laman.'" class="text text-dark text-decoration-none">'.$judul_laman.'</a>
                                            </h5>
                                            <small class="text-muted">'.$kategori_laman.'</small><br>
                                            <small class="text-muted">Oleh : '.$author.'</small><br>
                                            <small class="text-muted">'.$datetime_laman.'</small><br>
                                        </div>
                                    </div>
                                ';
                            }
                        }else{
                            //Jika Belum Ada Laman Yang Terdaftar Pada Database
                           echo '
                                <div class="alert alert-danger">
                                    Belum Ada Konten Laman Mandiri
                                </div>
                           ';
                        }
                    }else{
                        //Jika Detail Laman
                        echo '
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h2>'.$judul_laman.'</h2>
                                    <small>'.$datetime_laman.' - '.$author.'</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <img src="'.$cover_image.'" width="100%">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12 border-1 mb-3">
                                    <i>'.$deskripsi.'</i>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12 border-1 border-bottom mb-3">
                                </div>
                            </div>
                        ';
                        //Decript Json
                        $konten_array = json_decode($konten, true);

                        if (is_array($konten_array)) {
                            foreach ($konten_array as $item) {
                                $type = $item['type'] ?? '';
                                $order_id = $item['order_id'] ?? '';

                                // Tampilkan konten HTML biasa
                                if ($type === 'html') {
                                    echo '<div class="blog-html" style="margin-bottom: 1rem;">';
                                    echo $item['content']; // Sudah dalam format HTML
                                    echo '</div>';
                                }

                                // Tampilkan konten gambar
                                elseif ($type === 'image') {
                                    $width = $item['width'] ?? '100';
                                    $unit = $item['unit'] === '%' ? '%' : 'px';
                                    $position = $item['position'] ?? 'left';
                                    $caption = $item['caption'] ?? '';
                                    $imageSrc = 'image_proxy.php?segment=Artikel&image_name=' .htmlspecialchars($item['content']);

                                    if($position=="left"){
                                        $text_position="text-left";
                                    }else{
                                        if($position=="right"){
                                            $text_position="text-right";
                                        }else{
                                            if($position=="center"){
                                                $text_position="text-center";
                                            }else{
                                                $text_position="text-left";
                                            }
                                        }
                                    }
                                    echo '
                                        <div class="row mb-3">
                                            <div class="col-md-12 mb-3 '.$text_position.'">
                                                <img src="' . $imageSrc . '" width="' . $width . '' . $unit . '">
                                                <div class="caption" style="font-size: 0.9rem; color: #666;">' . htmlspecialchars($caption) . '</div>
                                            </div>
                                        </div>
                                    ';
                                }
                            }
                        } else {
                            echo "Konten blog tidak valid.";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</section>


