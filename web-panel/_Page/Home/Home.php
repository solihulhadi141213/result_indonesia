<!-- <section class="hero-section d-flex align-items-center text-white text-center">
    <div class="container">
        <h3 class="lead fs-2">Selamat Datang</h3>
        <h1 class="display-4 fw-bold title_segment">Di RSU El-Syifa Kuningan</h1>
        <h2 class="lead fs-2 mt-3">
            IGD 24 JAM : (0232) 876240 / +6289601154726
        </h2>
        <div class="social-icons mt-4 d-flex justify-content-center gap-3">
            <a href="https://wa.me/62xxxxxxxxxxx" target="_blank" class="btn-social">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://www.instagram.com/rsuelsyifa" target="_blank" class="btn-social">
                <i class="bi bi-instagram"></i>
            </a>
            <a href="https://www.youtube.com/@rsuelsyifa" target="_blank" class="btn-social">
                <i class="bi bi-youtube"></i>
            </a>
            <a href="https://www.facebook.com/rsuelsyifa" target="_blank" class="btn-social">
                <i class="bi bi-facebook"></i>
            </a>
        </div>
    </div>
</section> -->


<div id="carouselHero" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <?php
            // Menampilkan Hero 
            if(!empty($arry_static['hero'])){
                $no=1;
                foreach($arry_static['hero'] as $hero_list){
                    $no_reqal=$no-1;
                    if($no_reqal==0){
                        echo '<button type="button" data-bs-target="#carouselHero" data-bs-slide-to="0" class="active"></button>';
                    }else{
                        echo '<button type="button" data-bs-target="#carouselHero" data-bs-slide-to="'.$no_reqal.'"></button>';
                    }
                    $no++;
                }
            }
        ?>
    </div>
    
    <!-- Slides -->
    <div class="carousel-inner">
        <?php
            // Menampilkan Hero 
            if(!empty($arry_static['hero'])){
                $no=1;
                foreach($arry_static['hero'] as $hero_list){
                    $hero_order=$hero_list['order'];
                    $hero_image=$hero_list['image'];
                    $hero_title=$hero_list['title'];
                    $hero_sub_title=$hero_list['sub_title'];
                    $hero_button=$hero_list['button'];
                    //Buka Button
                    $hero_show_button=$hero_button['show_button'];
                    $hero_button_url=$hero_button['button_url'];
                    $hero_button_label=$hero_button['button_label'];
                    //Jika Baris 1
                    if($no==1){
                        $active="active";
                    }else{
                        $active="";
                    }
                    //Tampilkan Berdasarkan tipe
                    if($hero_show_button==false){
                        echo '
                            <div class="carousel-item '.$active.'" data-bs-interval="5000">
                                <img src="image_proxy.php?segment=Hero&image_name='.$hero_image.'" class="d-block w-100" alt="Slide '.$hero_order.'">
                                <div class="carousel-caption">
                                    <h5 class="hero_title">'.$hero_title.'</h5>
                                    <h3 class="hero_subtitle">'.$hero_sub_title.'</h3></p>
                                </div>
                            </div>
                        ';
                    }else{
                        echo '
                            <div class="carousel-item '.$active.'" data-bs-interval="5000">
                                <img src="image_proxy.php?segment=Hero&image_name='.$hero_image.'" class="d-block w-100" alt="Slide '.$hero_order.'">
                                <div class="carousel-caption">
                                    <h5 class="hero_title">'.$hero_title.'</h5>
                                    <h3 class="hero_subtitle">'.$hero_sub_title.'</h3></p>
                                    <a href="'.$hero_button_url.'" class="hero-jkn mt-3 d-inline-block show_transisi" target-link="">
                                        '.$hero_button_label.'
                                    </a>
                                </div>
                            </div>
                        ';
                    }
                    $no++;
                }
            }
        ?>
    </div>
    
    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHero" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselHero" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>
<!-- TENTANG -->
<div class="section bg-white">
    <div class="container my-5">
        <div class="row mb-3">
            <div class="col-12 text-center">
            </div>
        </div>
        <div class="row mb-6">
            <div class="col-md-6 mb-3">
                <img src="assets\img\_component\20250511_1040062-1920x1080.jpg" alt="Tentang Result Indonesia" width="100%" class="image_tentang">
            </div>
            <div class="col-md-6 mb-3">
                <h2 class="h1 mb-4 title_segment_dark">
                    Tentang Result Indonesia
                </h2>
                <p>
                    Kami membangun Result Indonesia pada tahun 2017 dengan cita-cita untuk memperkuat pengumpulan data dan design monitoring dan evaluasi di Indonesia.
                    Walaupun masih tergolong baru, tetapi staf kami terdiri dari orang-orang yang telah berkecimpung sangat lama di bidang penelitian dan monitoring dan evaluasi di Indonesia.
                </p>
                <p>
                    Tahun 2020, kami mengubah struktur kami agar dapat memenuhi kebutuhan dunia penelitian di Indonesia. 
                    Secara hukum, Result Indonesia berpayung di bawah <b>PT Karya Riset Indonesia</b>. Dengan penguatan struktur ini,kami berharap dapat lebih memperluas cakupan layanan kami
                </p>
            </div>
        </div>
    </div>
</div>
<!-- TIM UTAMA -->
<div class="section">
    <div class="container my-5">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <span class="h1 mb-4 title_segment_dark text-white">Tim Utama</span>
                <p class="text-white">
                    <i>Bagaimana Kami Dapat Mendukung Program Anda?</i>
                </p>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                <div class="card h-100 d-flex flex-column show_transisi" style="width: 100%;">
                    <div class="img-square-wrapper">
                        <img src="image_proxy.php?segment=Team&image_name=Daan-Pattinasarany-2.jpg" class="card-img-top" alt="...">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            G. Daan V. Pattinasarani
                        </h5>
                        <p class="card-text">Peneliti</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                <div class="card h-100 d-flex flex-column show_transisi" style="width: 100%;">
                    <div class="img-square-wrapper">
                        <img src="image_proxy.php?segment=Team&image_name=yuilia_2.jpg" class="card-img-top" alt="...">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            Yulia Herawati
                        </h5>
                        <p class="card-text">Peneliti</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3">
                <div class="card h-100 d-flex flex-column show_transisi" style="width: 100%;">
                    <div class="img-square-wrapper">
                        <img src="image_proxy.php?segment=Team&image_name=Gregorius-Kelik-1.jpg" class="card-img-top" alt="...">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            G. Kelik A. Endarso
                        </h5>
                        <p class="card-text">Peneliti</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- TIM MANAJEMEN -->
<div class="section bg-light">
    <div class="container my-5">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h2 class="h1 mb-4 title_segment_dark">Tim Manajemen</h2>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3 text-center">
                <div class="foto-wrapper rounded-circle mx-auto mb-3" style="width: 200px; overflow: hidden;">
                    <img src="image_proxy.php?segment=Team&image_name=Ichsan.png" alt="Ichsan" class="foto_direktur"/>
                </div>
                <h4 class="h4 mb-1 text-decoration-underline">ICHSAN</h3>
                <i>Lead Data Engineer</i>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3 text-center">
                <div class="foto-wrapper rounded-circle mx-auto mb-3" style="width: 200px; overflow: hidden;">
                    <img src="image_proxy.php?segment=Team&image_name=findi.jpg" alt="Ichsan" class="foto_direktur"/>
                </div>
                <h4 class="h4 mb-1 text-decoration-underline">FINDI FIRMANLIANSYAH</h3>
                <i>Data Engineer</i>
            </div>
           <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-3 text-center">
                <div class="foto-wrapper rounded-circle mx-auto mb-3" style="width: 200px; overflow: hidden;">
                    <img src="image_proxy.php?segment=Team&image_name=siti.jpg" alt="Ichsan" class="foto_direktur"/>
                </div>
                <h4 class="h4 mb-1 text-decoration-underline">SITI ZULVA</h3>
                <i>Data Specialist</i>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3 text-center">
                <div class="foto-wrapper rounded-circle mx-auto mb-3" style="width: 200px; overflow: hidden;">
                    <img src="image_proxy.php?segment=Team&image_name=Fera-Miasari-Diani.jpg" alt="Ichsan" class="foto_direktur"/>
                </div>
                <h4 class="h4 mb-1 text-decoration-underline">FERA DIANI MIASARI</h3>
                <i>Human Resources</i>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3 text-center">
                <div class="foto-wrapper rounded-circle mx-auto mb-3" style="width: 200px; overflow: hidden;">
                    <img src="image_proxy.php?segment=Team&image_name=aulia_aji.jpg" alt="Ichsan" class="foto_direktur" />
                </div>
                <h4 class="h4 mb-1 text-decoration-underline">AULIA AJI</h3>
                <i>Survey Specialist</i>
            </div>
        </div>
    </div>
</div>
<!-- BERITA DAN ARTIKEL -->
<div class="section">
    <div class="container my-5 py-4">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <span class="h1 mb-4 title_segment_dark text-white">
                    <?php echo "$setting_berita_artikel_title"; ?>
                </span>
                <p class="text-white"><i><?php echo "$setting_berita_artikel_subtitle"; ?></i></p>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <?php
                    $limit_berita = $setting_berita_artikel_limit;
                    $sql_berita = "SELECT * FROM  blog WHERE publish=1 ORDER BY datetime_creat DESC LIMIT :limit";
                    $stmt_berita = $Conn->prepare($sql_berita);
                    $stmt_berita->bindParam(':limit', $limit_berita, PDO::PARAM_INT);
                    $stmt_berita->execute();
                    $berita_list = $stmt_berita->fetchAll();
                    if (count($berita_list) > 0) {
                        foreach ($berita_list as $berita_artikel) {
                            $id_blog = $berita_artikel['id_blog'];
                            $title_blog = $berita_artikel['title_blog'];
                            $deskripsi = $berita_artikel['deskripsi'];
                            $cover_image = 'image_proxy.php?segment=Artikel&image_name=' . $berita_artikel['cover'];
                            $date_time_creat_blog=date('d F Y',strtotime($berita_artikel['datetime_creat']));
                            $author_blog = $berita_artikel['author_blog'];

                            //Limit deskripsi
                            $limit = 70;
                            if (strlen($deskripsi) > $limit) {
                                $deskripsi_tampil = substr($deskripsi, 0, $limit) . '...';
                            } else {
                                $deskripsi_tampil = $deskripsi;
                            }
                            echo '
                                <div class="d-flex mb-4 pb-3">
                                    <div class="me-3" style="flex: 0 0 150px;">
                                        <img src="'.$cover_image.'" alt="Cover" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            <a href="Blog?id='.$id_blog.'" class="text text-primary text-decoration-none">
                                                '.$title_blog.'
                                            </a>
                                        </h5>
                                        <small class="text-white"><i class="bi bi-calendar"></i> '.$date_time_creat_blog.'</small><br>
                                        <small class="text-white"><i class="bi bi-person"></i> '.$author_blog.'</small><br>
                                        <p><i class="text-white">'.$deskripsi_tampil.'</i></p>
                                    </div>
                                </div>
                            ';
                        }
                    }
                ?>

            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-center align-content-center">
                <button type="button" class="btn-elegant" target-link="<?php echo $base_url; ?>Blog">
                    Lihat Selengkapnya
                </button>
            </div>
        </div>
    </div>
</div>
<!-- PROJECT -->
<div class="section pendaftaran_antrian bg-light shadow-sm">
    <div class="container my-4 py-4">
        <div class="row mb-3">
            <div class="col-12 text-center">
                <span class="h1 mb-4 title_segment_dark">
                    PROJECT
                </span>
                <p class="text-dark"><i>Riwayat Project Pernah Kami Kerjakan</i></p>
            </div>
        </div>
        <div class="row mb-3">
                <?php
                    $sql_laman = "SELECT * FROM laman WHERE kategori_laman = 'Project' ORDER BY datetime_laman DESC";
                    $stmt_laman = $Conn->prepare($sql_laman);
                    $stmt_laman->execute();
                    $laman_list = $stmt_laman->fetchAll(PDO::FETCH_ASSOC);

                    if ($laman_list && count($laman_list) > 0) {
                        foreach ($laman_list as $laman) {
                            $id_laman     = htmlspecialchars($laman['id_laman']);
                            $judul_laman  = htmlspecialchars($laman['judul_laman']);
                            $cover_laman  = htmlspecialchars($laman['cover']);

                            echo '
                                <div class="col-6 col-sm-6 col-md-3 col-lg-3 mb-3">
                                    <div class="card h-100 d-flex flex-column show_transisi" style="width: 100%;">
                                        <div class="img-square-wrapper">
                                            <img src="image_proxy.php?segment=Laman&image_name='.$cover_laman.'" class="card-img-top" alt="'.$judul_laman.'">
                                        </div>
                                        <div class="card-body">
                                            <h5>
                                                <a href="Laman?id='.$id_laman.'" class="text text-decoration-none">'.$judul_laman.'</a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }else{
                        echo '
                            <div class="col-12">
                                <div class="alert alert-danger">Tidak Ada Laman Project Yang Ditampilkan</div>
                            </div>
                        ';
                    }
                ?>
            </div>
       </div>
    </div>
</div>
<!-- SERVICE -->
<div class="section">
    <div class="container my-5 py-4">
        <div class="row mb-3">
            <div class="col-12 text-center mb-3">
                <span class="h1 mb-4 title_segment_dark text-white">
                    Layanan
                </span>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 text-center mb-3">
                
            </div>
        </div>
        <?php
            $sql_laman = "SELECT * FROM laman WHERE kategori_laman = 'Layanan' ORDER BY datetime_laman DESC";
            $stmt_laman = $Conn->prepare($sql_laman);
            $stmt_laman->execute();
            $laman_list = $stmt_laman->fetchAll(PDO::FETCH_ASSOC);
            if ($laman_list && count($laman_list) > 0) {
                $no=1;
                foreach ($laman_list as $laman) {
                    $id_laman     = htmlspecialchars($laman['id_laman']);
                    $judul_laman  = htmlspecialchars($laman['judul_laman']);
                    $cover_laman  = htmlspecialchars($laman['cover']);
                    $deskripsi_laman  = $laman['deskripsi'];
                    
                    if ($no % 2 == 0) {
                        echo '
                            <div class="row mb-3 mt-3 layanan_row">
                                <div class="col-6 mb-3 text-center">
                                    <img src="image_proxy.php?segment=Laman&image_name='.$cover_laman.'" alt="'.$judul_laman.'" width="100%">
                                </div>
                                <div class="col-6 mb-3">
                                    <h3 class="text text-white">
                                        <a href="Laman?id='.$id_laman.'" class="text-light judul_laman">'.$judul_laman.'</a>
                                    </h3>
                                    <p class="text text-white deskripsi_layanan">'.$deskripsi_laman.'</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 mb-3 border-1 border-bottom"></div>
                            </div>
                        ';
                    }else{
                        echo '
                            <div class="row mb-3 mt-3 layanan_row">
                                <div class="col-6 mb-3">
                                    <h3 class="text text-white">
                                        <a href="Laman?id='.$id_laman.'" class="text-light judul_laman">'.$judul_laman.'</a>
                                    </h3>
                                    <p class="text text-white deskripsi_layanan">'.$deskripsi_laman.'</p>
                                </div>
                                <div class="col-6 mb-3 text-center">
                                    <img src="image_proxy.php?segment=Laman&image_name='.$cover_laman.'" alt="'.$judul_laman.'" width="100%">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 mb-3 border-1 border-bottom"></div>
                            </div>
                        ';
                    }
                    $no++;
                }
            }else{
                echo '
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-danger">Tidak Ada Laman Layanan Yang Ditampilkan</div>
                        </div>
                    </div>
                ';
            }
        ?>
    </div>
</div>
<!-- TAUTAN/LINK PENDAFTARAN -->
<div class="section pendaftaran_antrian bg-light shadow-sm">
    <div class="container my-5 py-4">
        <div class="row align-items-center p-4 rounded-3">
            <!-- Keterangan -->
            <div class="col-md-8 mb-3">
                <h3 class="fw-bold text-dark mb-2">
                    <i class="bi bi-envelope"></i> Dapatkan Update Terbaru
                </h3>
                <p class="mb-2 text-muted">
                    Jangan lewatkan berita, tips, dan update terbaru dari kami. Gratis dan langsung ke email Anda.
                </p>
                <small class="text-secondary fst-italic">
                    Dengan menekan tombol <b>Berlangganan</b>, Anda menyetujui syarat dan ketentuan serta 
                    <a href="#" class="text-decoration-none">Kebijakan Privasi</a> yang berlaku.
                </small>
            </div>

            <!-- Form -->
            <div class="col-md-4 mb-3">
                <form action="javascript:void(0);" id="ProsesBerlangganan" class="p-3 bg-white rounded-3 shadow-sm">
                    <div class="mb-3">
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Anda" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Alamat Email" required>
                    </div>
                    <div class="mb-3" id="NotifikasiBerlangganan">
                        <!-- Notifikasi Proses Kana Tampil Disini -->
                    </div>
                    <button type="submit" class="btn btn-success w-100 tombol_berlangganan">
                        <i class="bi bi-send"></i> Berlangganan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- GOOGLE MAP -->
 <?php
    if(!empty($arry_static['google_map'])){
        echo '
            <div class="container-fluid px-0 g-0 mb-0">
                <iframe src="'.$arry_static['google_map']['src'].'" 
                width="'.$arry_static['google_map']['width'].'" 
                height="'.$arry_static['google_map']['height'].'" 
                style="'.$arry_static['google_map']['style'].'" 
                allowfullscreen="'.$arry_static['google_map']['allowfullscreen'].'" 
                loading="'.$arry_static['google_map']['loading'].'" 
                referrerpolicy="'.$arry_static['google_map']['referrerpolicy'].'" class="'.$arry_static['google_map']['class'].'">
                </iframe>
            </div>
        ';
    }
 ?>
