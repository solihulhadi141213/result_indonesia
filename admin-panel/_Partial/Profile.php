<li class="nav-item dropdown pe-3">
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <?php
            echo '<img src="image_proxy.php?dir=User&filename='.$SessionGambar.'" alt="Profile" class="rounded-circle">';
            echo '<span class="d-none d-md-block dropdown-toggle ps-2 text-white">'.$SessionNama.'</span>';
        ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
            <h6><?php echo $SessionNama;?></h6>
            <span><?php echo $SessionAkses;?></span>
        </li>
        <?php
            echo '<li>';
            echo '  <hr class="dropdown-divider">';
            echo '</li>';
            echo '<li>';
            echo '  <a class="dropdown-item d-flex align-items-center" href="index.php?Page=MyProfile">';
            echo '      <i class="bi bi-person"></i>';
            echo '      <span>Profil Saya</span>';
            echo '  </a>';
            echo '</li>';
            echo '<li>';
            echo '  <hr class="dropdown-divider">';
            echo '</li>';
            echo '<li>';
            echo '  <hr class="dropdown-divider">';
            echo '</li>';
            echo '<li>';
            echo '  <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalLogout">';
            echo '      <i class="bi bi-box-arrow-right"></i>';
            echo '      <span>Keluar</span>';
            echo '  </a>';
            echo '</li>';
        ?>
    </ul>
</li>