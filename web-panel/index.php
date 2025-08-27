<?php
    //Koneksi
    require_once '_Config/Connection.php';
    require_once '_Config/Function.php';
    require_once '_Config/log_visitor.php';

    //Zona waktu
    date_default_timezone_set('Asia/Jakarta');

    // Inisialisasi koneksi database
    $db = new Database();
    $Conn = $db->getConnection();

    //Include Pengaturan Website
    require_once '_Config/Setting.php';

    // Mulai logging
    $logger = new VisitorLogger($Conn);
    $logger->logVisit();

    // Tentukan base URL dinamis
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $base_url = $protocol . $host . $base_path . '/';
    define('BASE_URL', $base_url);

    // Tangkap URI dan hilangkan query string
    $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Normalisasi URI dengan menghapus base_path dan index.php
    $relative_uri = preg_replace([
        '#^' . preg_quote($base_path, '#') . '#',  // Hapus base path
        '#/index\.php$#',                          // Hapus /index.php di akhir
        '#/index\.php/#',                          // Hapus /index.php/ di tengah
    ], '', $request_uri);

    // Hilangkan slash di depan/belakang dan kosongkan jika hanya ada slash
    $relative_uri = trim($relative_uri, '/');
    
    // Ambil segment pertama sebagai halaman
    $segments = explode('/', $relative_uri);
    $Page = !empty($segments[0]) ? $segments[0] : 'Home';
    
    // Contoh penggunaan:
    // - URL: example.com/index.php/contact → $Page = 'contact'
    // - URL: example.com/contact → $Page = 'contact'
    // - URL: example.com/ → $Page = 'home'

    //Simpan log pengunjung
?>

<!DOCTYPE html>
<html lang="en">
    <?php
        //Menampilkan Partial
        include "_Partial/Head.php";
        include "_Partial/Preloader.php";
    ?>
    
    <body>
        <!-- HEADER -->
        <header class="container-fluid py-2 px-3">
            <div class="container d-flex flex-wrap justify-content-between align-items-center">
                <!-- Logo dan Judul -->
                <div class="d-flex align-items-center gap-2">
                    <a href="">
                        <img src="<?php echo $base_url; ?>/assets/img/<?php echo $setting_logo_image_navbar; ?>" alt="<?php echo $setting_title_navbar; ?>" style="height: 70px;">
                    </a>
                </div>

                <!-- Tombol Offcanvas (untuk mobile) -->
                <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <!-- Menu biasa di desktop -->
                <nav class="d-none d-lg-block">
                    <ul class="navbar-nav flex-row gap-3">
                        <?php
                            if(!empty($arry_static['menu'])){
                                $arry_static_menu=$arry_static['menu'];
                                foreach($arry_static_menu as $arry_static_list){
                                    $menu_label=$arry_static_list['label'];
                                    $menu_url=$arry_static_list['url'];
                                    $submenu=$arry_static_list['submenu'];
                                    if(empty(count($submenu))){
                                        echo '
                                            <li class="nav-item"><a class="nav-link" href="'.$menu_url.'">'.$menu_label.'</a></li>
                                        ';
                                    }else{
                                        echo '<li class="nav-item dropdown">';
                                        echo '  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">'.$menu_label.'</a>';
                                        echo '  <ul class="dropdown-menu">';
                                        foreach($submenu as $submenu_list){
                                            $submenu_label=$submenu_list['label'];
                                            $submenu_url=$submenu_list['url'];
                                            echo '
                                                <li>
                                                    <a class="dropdown-item" href="'.$submenu_url.'">'.$submenu_label.'</a>
                                                </li>
                                            ';
                                        }
                                        echo '  </ul>';
                                        echo '</li>';
                                    }
                                }
                            }
                        ?>
                    </ul>
                </nav>
            </div>
        </header>
        <!-- Menu (Offcanvas) -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-light" id="offcanvasMenuLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto">
                    
                    <?php
                        if(!empty($arry_static['menu'])){
                            $arry_static_menu=$arry_static['menu'];
                            foreach($arry_static_menu as $arry_static_list){
                                $menu_order=$arry_static_list['order'];
                                $menu_label=$arry_static_list['label'];
                                $menu_url=$arry_static_list['url'];
                                $submenu=$arry_static_list['submenu'];
                                if(empty(count($submenu))){
                                    echo '
                                        <li class="nav-item"><a class="nav-link" href="'.$menu_url.'">'.$menu_label.'</a></li>
                                    ';
                                }else{
                                    echo '<li class="nav-item">';
                                    echo '  <a class="nav-link" data-bs-toggle="collapse" href="#submenu'.$menu_order.'" role="button" aria-expanded="false" aria-controls="submenuTentang">';
                                    echo '      '.$menu_label.' <i class="bi bi-chevron-down float-end"></i>';
                                    echo '  </a>';
                                    echo '  <div class="collapse ps-3" id="submenu'.$menu_order.'">';
                                    foreach($submenu as $submenu_list){
                                        $submenu_label=$submenu_list['label'];
                                        $submenu_url=$submenu_list['url'];
                                        echo '<a class="nav-link" href="'.$submenu_url.'">'.$submenu_label.'</a>';
                                    }
                                    echo '  </div>';
                                    echo '</li>';
                                }
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
        <?php
            // Routing manual
            echo '<h1>'.$Page.'</h1>';
            if($Page=="Home"||$Page==""){
                include "_Page/Home/Home.php";
                include "_Page/Home/ModalHome.php";
            }else{
                if($Page=="Blog"){
                    include "_Page/Blog/Blog.php";
                }else{
                    if($Page=="Laman"){
                        include "_Page/Laman/Laman.php";
                    }else{
                        include "_Page/Home/Home.php";
                        include "_Page/Home/ModalHome.php";
                    }
                }
            }
        ?>
        
        <button id="backToTopBtn" class="btn btn-success rounded-circle shadow" style="position: fixed; bottom: 20px; right: 20px; width: 50px; height: 50px; display: none;">
            <i class="bi bi-arrow-up"></i>
        </button>
        <!-- FOOTER -->
        <footer class="footer py-4">
            <div class="container">
                <div class="row text-white">
                    <!-- Link Eksternal -->
                    <div class="col-md-3 mb-3">
                        <h5 class="text-decoration-underline"><?php echo $setting_tautan_lainnya_title; ?></h5>
                        <ul class="">
                            <?php
                                if(!empty(count($setting_tautan_lainnya_list))){
                                    foreach ($setting_tautan_lainnya_list as $tautan_lainnya_list) {
                                        $label_tautan_lainnya=$tautan_lainnya_list['label'];
                                        $target_tautan_lainnya=$tautan_lainnya_list['target'];
                                        $url_tautan_lainnya=$tautan_lainnya_list['url'];
                                        echo '
                                            <li>
                                                <a href="'.$url_tautan_lainnya.'" class="footer-link" target="'.$target_tautan_lainnya.'">
                                                    '.$label_tautan_lainnya.'
                                                </a>
                                            </li>
                                        ';
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                    <!-- Kontak -->
                    <div class="col-md-3 mb-3">
                        <h5 class="text-decoration-underline"><?php echo $setting_kontak_title; ?></h5>
                        <!-- Tambahan Kontak -->
                        <div class="contact-info">
                            <?php
                                if(!empty(count($setting_kontak_list))){
                                    foreach ($setting_kontak_list as $list_kontak) {
                                        $kontak_icon=$list_kontak['icon'];
                                        $kontak_value=$list_kontak['value'];
                                        echo '
                                            <div class="d-flex align-items-center mb-2">
                                                '.$kontak_icon.'
                                                <span>'.$kontak_value.'</span>
                                            </div>
                                        ';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <!-- Alamat -->
                    <div class="col-md-3 mb-3">
                        <h5 class="text-decoration-underline"><?php echo $setting_alamat_title; ?></h5>
                        <p><?php echo $setting_alamat_value; ?></p>
                    </div>
                    <!-- Media Sosial -->
                    <div class="col-md-3 mb-3">
                        <h5 class="text-decoration-underline"><?php echo $setting_media_sosial_title; ?></h5>
                        <div class="social-media">
                            <?php
                                if(!empty(count($setting_media_sosial_list))){
                                    foreach ($setting_media_sosial_list as $medsos_list) {
                                        $medsos_url=$medsos_list['url'];
                                        $medsos_icon=$medsos_list['icon'];
                                        $medsos_order=$medsos_list['order'];
                                        echo '
                                            <a href="'.$medsos_url.'" class="social-circle">
                                                '.$medsos_icon.'
                                            </a>
                                        ';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <hr class="border-light">
                <div class="text-center text-white small">&copy; 2025 <?php echo $setting_author; ?>. All rights reserved.</div>
            </div>
        </footer>

    </body>
    <script type="text/javascript" src="<?php echo $base_url; ?>/node_modules/jquery/dist/jquery.min.js?v=<?php echo date('YmdHis'); ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const preloader = document.querySelector('.preloader');
            const startTime = Date.now();
            const minimumDisplayTime = 2000; // 2 detik dalam milidetik

            // Fungsi untuk menyembunyikan preloader
            function hidePreloader() {
                const elapsedTime = Date.now() - startTime;
                const remainingTime = Math.max(0, minimumDisplayTime - elapsedTime);

                setTimeout(() => {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 500); // Waktu untuk transition opacity
                }, remainingTime);
            }

            // Pastikan semua aset (gambar, dll) sudah dimuat
            window.addEventListener('load', hidePreloader);

            // Fallback jika event load tidak terpicu
            setTimeout(hidePreloader, minimumDisplayTime + 1000);
        });

        $(document).ready(function(){
            // Fungsi untuk mengecek ketika elemen muncul di viewport
            function tampilkanDenganTransisi() {
                const elements = document.querySelectorAll('.show_transisi');
                
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;
                    
                    if(elementPosition < screenPosition) {
                        element.classList.add('muncul');
                    }
                });
            }

            // Jalankan saat load dan scroll
            window.addEventListener('load', tampilkanDenganTransisi);
            window.addEventListener('scroll', tampilkanDenganTransisi);

            function revealOnScroll() {
                $('.title_segment').each(function () {
                    var top_of_element = $(this).offset().top;
                    var bottom_of_screen = $(window).scrollTop() + $(window).height();

                    if (bottom_of_screen > top_of_element + 30) {
                        $(this).addClass('show');
                    }
                });
                $('.title_segment_dark').each(function () {
                    var top_of_element = $(this).offset().top;
                    var bottom_of_screen = $(window).scrollTop() + $(window).height();

                    if (bottom_of_screen > top_of_element + 30) {
                        $(this).addClass('show');
                    }
                });
                 $('.service_segment').each(function () {
                    var top_of_element = $(this).offset().top;
                    var bottom_of_screen = $(window).scrollTop() + $(window).height();

                    if (bottom_of_screen > top_of_element + 30) {
                        $(this).addClass('show');
                    }
                });
            }
            $('#klik').on('click', function(){
                alert('Berhasil! jQuery aktif.');
            });
            // Sembunyikan full_sambutan saat halaman dimuat
            $('#full_sambutan').hide();

            // Saat link diklik, tampilkan full_sambutan dengan animasi
            let isVisible = false;
            $('#toggle_sambutan').on('click', function(e) {
                e.preventDefault();
                if (!isVisible) {
                    $('#full_sambutan').slideDown('slow');
                    $(this).text('Sembunyikan');
                    isVisible = true;
                } else {
                    $('#full_sambutan').slideUp('slow');
                    $(this).text('Baca Selengkapnya');
                    isVisible = false;
                }
            });

            

            // Jalankan saat load dan scroll
            $(window).on('scroll', revealOnScroll);
            revealOnScroll();
            

            // Swiper
            const swiper = new Swiper('.mySwiper', {
                slidesPerView: 1.2,
                spaceBetween: 16,
                loop: false,
                grabCursor: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    type: 'bullets',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    576: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    992: { slidesPerView: 4 },
                },
            });

            //Infografis
            // const counters = document.querySelectorAll('.info_grafis h4');
            // const speed = 200;

            // counters.forEach(counter => {
            //     const target = +counter.innerText;
            //     const count = +counter.innerText;
            //     const increment = target / speed;
                
            //     if(count < target) {
            //         counter.innerText = Math.ceil(count + increment);
            //         setTimeout(updateCount, 1);
            //     } else {
            //         counter.innerText = target;
            //     }
            // });

            // Cek saat load
            tampilkanDenganTransisi();
            
            // Cek saat scroll
            $(window).scroll(function() {
                tampilkanDenganTransisi();
            });
            
            function tampilkanDenganTransisi() {
                $('.show_transisi').each(function() {
                    const elementPosition = $(this).offset().top;
                    const screenPosition = $(window).scrollTop() + $(window).height()/1.2;
                    
                    if(elementPosition < screenPosition) {
                        $(this).addClass('muncul');
                    }
                });
            }

            //back To top
            $(window).scroll(function() {
                if ($(this).scrollTop() > 200) {
                    $('#backToTopBtn').fadeIn();
                } else {
                    $('#backToTopBtn').fadeOut();
                }
            });

            $('#backToTopBtn').click(function() {
                $('html, body').animate({scrollTop: 0}, 'smooth');
                return false;
            });

            //PARTNER SWIPER
            // Inisialisasi untuk partner swiper
            const partnerSwiper = new Swiper('.partnerSwiper', {
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                slidesPerView: 2,
                spaceBetween: 20,
                centeredSlides: true,
                grabCursor: true,
                
                breakpoints: {
                    576: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: 4,
                        spaceBetween: 30
                    },
                    992: {
                        slidesPerView: 5,
                        spaceBetween: 40
                    }
                },
                
                pagination: {
                    el: '.partner-pagination',
                    clickable: true,
                    dynamicBullets: true
                },
            });

            //ROOM SWIPER
            new Swiper(".room_swiper", {
                slidesPerView: 1.2,
                spaceBetween: 2, // atau kurangi jadi 8 jika terlalu renggang
                breakpoints: {
                    576: {
                        slidesPerView: 1,
                        spaceBetween: 16
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 24
                    }
                },
                pagination: {
                    el: ".room-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });

        });

        // Script untuk toggle class scrolled
        window.addEventListener("scroll", function() {
            const header = document.querySelector("header");
            if (window.scrollY > 50) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        });
    </script>
    <?php
        // Routing manual
        if($Page=="Home"||$Page==""){
          echo '<script type="text/javascript" src="'.$base_url.'/_Page/Home/Home.js?v='.date('YmdHis').'"></script>';
        }elseif($Page=="Contact"){
            
        }elseif($Page=="Struktur-Organisasi"){
           
        }elseif($Page=="Galeri"){
           
        }elseif($Page=="Blog"){
           echo '<script type="text/javascript" src="'.$base_url.'/_Page/Blog/Blog.js?v='.date('YmdHis').'"></script>';
        }elseif($Page=="Dokter"){
           echo '<script type="text/javascript" src="'.$base_url.'/_Page/Dokter/Dokter.js?v='.date('YmdHis').'"></script>';
        }else{
            
        }
    ?>
</html>