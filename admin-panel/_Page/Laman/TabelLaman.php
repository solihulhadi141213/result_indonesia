<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    //Sesi Akses
    if (empty($SessionIdAkses)) {
        echo '
            <tr>
                <td colspan="6" align="center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }
    
    //Cek Sesi x-token
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
                <td colspan="6" align="center">
                    <small class="text-danger">X-Token Gagal Dibuat!</small>
                </td>
            </tr>
        ';
        exit();
    }

    // Ambil parameter
    $keyword_by = !empty($_POST['keyword_by']) ? $_POST['keyword_by'] : "";
    $keyword    = !empty($_POST['keyword']) ? $_POST['keyword'] : "";
    $batas      = !empty($_POST['batas']) ? $_POST['batas'] : 10;
    $page       = !empty($_POST['page']) ? $_POST['page'] : 1;
    $ShortBy    = !empty($_POST['ShortBy']) ? $_POST['ShortBy'] : "DESC";
    $OrderBy    = !empty($_POST['OrderBy']) ? $_POST['OrderBy'] : "datetime_laman";
    
    if($keyword_by=="publish"){
        if(empty($keyword)){
            $keyword="0";
        }
    }
    //Posisi Sekarang
    $posisi     = ($page - 1) * $batas;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/ListLaman.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "order_by":"'.$OrderBy.'",
            "short_by":"'.$ShortBy.'",
            "keyword_by":"'.$keyword_by.'",
            "keyword":"'.$keyword.'",
            "limit":'.$batas.',
            "page":'.$page.'
        }',
        CURLOPT_HTTPHEADER => array(
            'x-token: '.$_SESSION["x-token"].'',
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);
    if($response === false){
        // Kalau cURL gagal total
        $error_msg = curl_error($curl);
        curl_close($curl);
        echo '
            <tr>
                <td colspan="6" align="center">
                    <small class="text-danger">cURL Error: '.$error_msg.'</small>
                </td>
            </tr>
        ';
        exit();
    }
    curl_close($curl);

    $response_arry=json_decode($response, true);

    if(empty($response_arry['status'])){
         echo '
            <tr>
                <td colspan="6" align="center">
                    <small class="text-danger">CURL Error: '.$response.'</small>
                </td>
            </tr>
        ';
        exit();
    }
    //Apabila Response Status Gagal
    if($response_arry['status']!=="success"){
        echo '
            <tr>
                <td colspan="6" align="center">
                    <small class="text-danger">Response : '.$response_arry['message'].'</small>
                </td>
            </tr>
        ';
    }else{
        //Jika Berhasil
        $JmlHalaman=$response_arry['pagination']['total_pages'];
        $data=$response_arry['data'];

        $no=1;
        foreach($data as $list){
            $id_laman=$list['id_laman'];
            $judul_laman=$list['judul_laman'];
            $kategori_laman=$list['kategori_laman'];
            $datetime_laman=$list['datetime_laman'];
            $deskripsi=$list['deskripsi'];
            $author=$list['author'];
            $cover_url=$list['cover_url'];

            //Tampilkan Baris Tabel
            echo '
                <tr>
                    <td align="center"><small>'.$no.'</small></td>
                    <td align="left"><small>'.date('d F Y', strtotime($datetime_laman)).'</small></td>
                    <td align="left">
                        <small>
                            <a href="index.php?Page=Laman&Sub=DetailLaman&id=' . $id_laman . '" class="text text-decoration-underline">
                                '.$judul_laman.'
                            </a>
                        </small>
                    </td>
                    <td align="left"><small>'.$author.'</small></td>
                    <td align="left"><small>'.$kategori_laman.'</small></td>
                    <td align="left">
                        <a class="btn btn-sm btn-outline-dark btn-floating" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Option</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="index.php?Page=Laman&Sub=DetailLaman&id=' . $id_laman . '">
                                    <i class="bi bi-info-circle"></i> Detail
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEdit" data-id="' . $id_laman . '" data-mode="Refresh">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapus" data-id="' . $id_laman . '" data-mode="Refresh">
                                    <i class="bi bi-x"></i> Hapus
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            ';
            $no++;
        }
    }
?>
<script>
    var page_count = <?php echo $JmlHalaman; ?>;
    var curent_page = <?php echo $page; ?>;
    $('#page_info').html('Page ' + curent_page + ' Of ' + page_count);
    $('#prev_button').prop('disabled', curent_page === 1);
    $('#next_button').prop('disabled', curent_page >= page_count);
</script>