<?php
    //Koneksi Database
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Include Pengaturan Koneksi
    include "../../_Config/SettingKoneksi.php";

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

    if(empty($_SESSION["x-token"])){
        echo '
            <tr>
                <td colspan="5" class="text-center">
                    <small class="text-danger">'.$generate_x_token_arry['message'].'</small>
                </td>
            </tr>
        ';
    }else{
        //Buka Data Metatag
        $detail_layout=DetailLayout($base_url, $_SESSION["x-token"]);

        //Konversi ke arry
        $detail_layout_arry=json_decode($detail_layout, true);

        //Jika Gagal menampilkan detail layout
        if($detail_layout_arry['status']!=="success"){
            echo '
                <tr>
                    <td colspan="5" class="text-center">
                        <small class="text-danger">'.$detail_layout_arry['message'].'</small>
                    </td>
                </tr>
            ';
        }else{
            $no=1;
            $MenuContent=$detail_layout_arry['layout_static']['menu'];
            foreach ($MenuContent as $MenuContentList) {
                $menu_url=$MenuContentList['url'];
                $menu_label=$MenuContentList['label'];
                $menu_order=$MenuContentList['order'];
                $menu_submenu=$MenuContentList['submenu'];
                echo '
                    <tr>
                        <td>'.$no.'</td>
                        <td colspan="2">'.$menu_label.'</td>
                        <td>'.$menu_url.'</td>
                        <td>
                            <a class="btn btn-sm btn-outline-dark btn-floating" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start"><h6>Option</h6></li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalEditMenu" data-order="'.$menu_order.'" data-url="'.$menu_url.'" data-label="'.$menu_label.'">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalDeleteMenu" data-id="'.$menu_order.'">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalTambahSubmenu" data-id="'.$menu_order.'">
                                        <i class="bi bi-plus"></i> Submenu
                                    </a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                ';
                if(!empty(count($menu_submenu))){
                    $no2=1;
                    foreach ($menu_submenu as $menu_submenu_list) {
                        $submenu_url=$menu_submenu_list['url'];
                        $submenu_label=$menu_submenu_list['label'];
                        $submenu_order=$menu_submenu_list['order'];
                        echo '
                            <tr>
                                <td></td>
                                <td><small class="text-muted">'.$no.'.'.$no2.'</small></td>
                                <td><small class="text-muted">'.$submenu_label.'</small></td>
                                <td><small class="text-muted">'.$submenu_url.'</small></td>
                                <td>
                                    <a class="btn btn-sm btn-outline-dark btn-floating" href="#" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start"><h6>Option</h6></li>
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalEditSubmenu" data-order_parent="'.$menu_order.'" data-order_child="'.$submenu_order.'" data-submenu_label="'.$submenu_label.'" data-submenu_url="'.$submenu_url.'">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ModalDeleteSubmenu" data-id="'.$submenu_order.'" data-menuid="'.$menu_order.'">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        ';
                    }
                    $no2++;
                }
                $no++;
            }
        }
    }
?>