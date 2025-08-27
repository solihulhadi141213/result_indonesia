<?php 
    $date_version=date('YmdHis');
    if(empty($_GET['Page'])){
        //Dafault Javascript Diarahkan Ke Dashboard
        echo '<script type="text/javascript" src="_Page/Dashboard/Dashboard.js?V='.$date_version.'"></script>';
    }else{
        $Page=$_GET['Page'];
        // Routing Javascript Berdasarkan Halaman
        $scripts = [
            "MyProfile"                 => "_Page/MyProfile/MyProfile.js",
            "AksesFitur"                => "_Page/AksesFitur/AksesFitur.js",
            "AksesEntitas"              => "_Page/AksesEntitas/AksesEntitas.js",
            "Akses"                     => "_Page/Akses/Akses.js",
            "SettingGeneral"            => "_Page/SettingGeneral/SettingGeneral.js",
            "SettingEmail"              => "_Page/SettingEmail/SettingEmail.js",
            "SettingKoneksiWeb"         => "_Page/SettingKoneksiWeb/SettingKoneksiWeb.js",
            "Metatag"                   => "_Page/Metatag/Metatag.js",
            "Favicon"                   => "_Page/Favicon/Favicon.js",
            "Navbar"                    => "_Page/Navbar/Navbar.js",
            "Menu"                      => "_Page/Menu/Menu.js",
            "Hero"                      => "_Page/Hero/Hero.js",
            "KontakAlamat"              => "_Page/KontakAlamat/KontakAlamat.js",
            "MediaSosial"               => "_Page/MediaSosial/MediaSosial.js",
            "TautanLain"                => "_Page/TautanLain/TautanLain.js",
            "VisiMisi"                  => "_Page/VisiMisi/VisiMisi.js",
            "GoogleMap"                 => "_Page/GoogleMap/GoogleMap.js",
            "BlogList"                  => "_Page/BlogList/BlogList.js",
            "Laman"                     => "_Page/Laman/Laman.js",
            "Newslater"                 => "_Page/Newslater/Newslater.js",
            "ApiDoc"                    => "_Page/ApiDoc/ApiDoc.js",
            "Help"                      => "_Page/Help/Help.js",
            "Aktivitas"                 => "_Page/Aktivitas/Aktivitas.js"
        ];

        // Cek apakah halaman ada dalam daftar dan sertakan file JS yang sesuai
        if (!empty($_GET['Page']) && isset($scripts[$_GET['Page']])) {
            echo '<script type="text/javascript" src="' . $scripts[$_GET['Page']] . '?V='.$date_version.'"></script>';
        }
    }
    echo '<script type="text/javascript" src="_Partial/Universal.js?V='.$date_version.'"></script>';
?>