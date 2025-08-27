<?php
    include "_Page/Logout/ModalLogout.php";
    include "_Page/Dashboard/ModalDashboard.php";
    if(!empty($_GET['Page'])){
        $Page=$_GET['Page'];
        
        // Daftar halaman dan modal yang terkait
        $modals = [
            "MyProfile"             => "_Page/MyProfile/ModalMyProfile.php",
            "AksesFitur"            => "_Page/AksesFitur/ModalAksesFitur.php",
            "AksesEntitas"          => "_Page/AksesEntitas/ModalAksesEntitas.php",
            "Akses"                 => "_Page/Akses/ModalAkses.php",
            "SettingGeneral"        => "_Page/SettingGeneral/ModalSettingGeneral.php",
            "SettingEmail"          => "_Page/SettingEmail/ModalSettingEmail.php",
            "SettingKoneksiWeb"     => "_Page/SettingKoneksiWeb/ModalSettingKoneksiWeb.php",
            "Metatag"               => "_Page/Metatag/ModalMetatag.php",
            "Favicon"               => "_Page/Favicon/ModalFavicon.php",
            "Navbar"                => "_Page/Navbar/ModalNavbar.php",
            "Menu"                  => "_Page/Menu/ModalMenu.php",
            "Hero"                  => "_Page/Hero/ModalHero.php",
            "KontakAlamat"          => "_Page/KontakAlamat/ModalKontakAlamat.php",
            "MediaSosial"           => "_Page/MediaSosial/ModalMediaSosial.php",
            "TautanLain"            => "_Page/TautanLain/ModalTautanLain.php",
            "VisiMisi"              => "_Page/VisiMisi/ModalVisiMisi.php",
            "GoogleMap"             => "_Page/GoogleMap/ModalGoogleMap.php",
            "BlogList"              => "_Page/BlogList/ModalBlogList.php",
            "Laman"                 => "_Page/Laman/ModalLaman.php",
            "Newslater"             => "_Page/Newslater/ModalNewslater.php",
            "Help"                  => "_Page/Help/ModalHelp.php",
            "Aktivitas"             => "_Page/Aktivitas/ModalAktivitas.php"
        ];

        // Cek apakah halaman memiliki modal terkait dan sertakan file modalnya
        if (!empty($_GET['Page']) && isset($modals[$_GET['Page']])) {
            include $modals[$_GET['Page']];
        }
    }
?>