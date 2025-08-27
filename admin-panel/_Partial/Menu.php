<?php
    if(empty($_GET['Page'])){
        $PageMenu="";
    }else{
        $PageMenu=$_GET['Page'];
    }
    if(empty($_GET['Sub'])){
        $SubMenu="";
    }else{
        $SubMenu=$_GET['Sub'];
    }
?>
<aside id="sidebar" class="sidebar menu_background">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu==""){echo "";}else{echo "collapsed";} ?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="AksesFitur"||$PageMenu=="AksesEntitas"||$PageMenu=="Akses"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#akses-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-person"></i>
                <span>Akses</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="akses-nav" class="nav-content collapse <?php if($PageMenu=="AksesFitur"||$PageMenu=="AksesEntitas"||$PageMenu=="Akses"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=AksesFitur" class="<?php if($PageMenu=="AksesFitur"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Fitur Aplikasi</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=AksesEntitas" class="<?php if($PageMenu=="AksesEntitas"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Entitas Akses</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Akses" class="<?php if($PageMenu=="Akses"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Akun Akses</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="SettingGeneral"||$PageMenu=="SettingEmail"||$PageMenu=="SettingKoneksiWeb"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-gear"></i>
                    <span>Pengaturan</span><i class="bi bi-chevron-down ms-auto">
                </i>
            </a>
            <ul id="components-nav" class="nav-content collapse <?php if($PageMenu=="SettingGeneral"||$PageMenu=="SettingEmail"||$PageMenu=="SettingKoneksiWeb"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=SettingGeneral" class="<?php if($PageMenu=="SettingGeneral"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Pengaturan Umum</span>
                    </a>
                </li> 
                <li>
                    <a href="index.php?Page=SettingEmail" class="<?php if($PageMenu=="SettingEmail"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Email Gateway</span>
                    </a>
                </li> 
                <li>
                    <a href="index.php?Page=SettingKoneksiWeb" class="<?php if($PageMenu=="SettingKoneksiWeb"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Koneksi Web</span>
                    </a>
                </li> 
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="Metatag"||$PageMenu=="Favicon"||$PageMenu=="Navbar"||$PageMenu=="Menu"||$PageMenu=="Hero"||$PageMenu=="KontakAlamat"||$PageMenu=="MediaSosial"||$PageMenu=="TautanLain"||$PageMenu=="VisiMisi"||$PageMenu=="GoogleMap"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#layout-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-columns"></i>
                <span>Layout</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="layout-nav" class="nav-content collapse <?php if($PageMenu=="Metatag"||$PageMenu=="Favicon"||$PageMenu=="Navbar"||$PageMenu=="Menu"||$PageMenu=="Hero"||$PageMenu=="KontakAlamat"||$PageMenu=="MediaSosial"||$PageMenu=="TautanLain"||$PageMenu=="VisiMisi"||$PageMenu=="GoogleMap"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=Metatag" class="<?php if($PageMenu=="Metatag"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Metatag</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Favicon" class="<?php if($PageMenu=="Favicon"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Favicon & Logo</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Navbar" class="<?php if($PageMenu=="Navbar"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Navbar</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Menu" class="<?php if($PageMenu=="Menu"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Menu</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Hero" class="<?php if($PageMenu=="Hero"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Hero/Slider</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=KontakAlamat" class="<?php if($PageMenu=="KontakAlamat"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Kontak & Alamat</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=MediaSosial" class="<?php if($PageMenu=="MediaSosial"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Media Sosial</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=TautanLain" class="<?php if($PageMenu=="TautanLain"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Tautan Lainnya</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=VisiMisi" class="<?php if($PageMenu=="VisiMisi"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Visi Misi</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=GoogleMap" class="<?php if($PageMenu=="GoogleMap"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Google Map</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu=="BlogList"||$PageMenu=="Laman"||$PageMenu=="Label"||$PageMenu=="BlogStatistik"){echo "";}else{echo "collapsed";} ?>" data-bs-target="#anggota-nav" data-bs-toggle="collapse" href="javascript:void(0);">
                <i class="bi bi-newspaper"></i>
                <span>Blog Posting</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="anggota-nav" class="nav-content collapse <?php if($PageMenu=="BlogList"||$PageMenu=="Laman"||$PageMenu=="Label"||$PageMenu=="BlogStatistik"){echo "show";} ?>" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?Page=BlogList" class="<?php if($PageMenu=="BlogList"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Blog List</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Laman" class="<?php if($PageMenu=="Laman"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Laman Mandiri</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=Label" class="<?php if($PageMenu=="Label"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Label/Tag</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?Page=BlogStatistik" class="<?php if($PageMenu=="BlogStatistik"){echo "active";} ?>">
                        <i class="bi bi-circle"></i><span>Statistik</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-heading">Fitur Lainnya</li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu!=="Newslater"){echo "collapsed";} ?>" href="index.php?Page=Newslater">
                <i class="bi bi-envelope"></i>
                <span>Newslater</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($PageMenu!=="Help"){echo "collapsed";} ?>" href="index.php?Page=Help&Sub=HelpData">
                <i class="bi bi-question"></i>
                <span>Dokumentasi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalLogout">
                <i class="bi bi-box-arrow-in-left"></i>
                <span>Keluar</span>
            </a>
        </li>
    </ul>
</aside> 