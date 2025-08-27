<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if (empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
    }

    // Cek Sesi x-token
    if (empty($_SESSION['x-token'])) {
        $generate_x_token = generate_x_token($base_url, $user_key, $access_key);
        $generate_x_token_arry = json_decode($generate_x_token, true);

        if ($generate_x_token_arry['status'] == 'success') {
            $_SESSION["x-token"]     = $generate_x_token_arry['session_token'];
            $_SESSION["x-expired_at"] = $generate_x_token_arry['expired_at'];
        } else {
            $_SESSION["x-token"] = "";
            $_SESSION["x-expired_at"] = "";
        }
    } else {
        $session_x_token   = $_SESSION['x-token'];
        $session_expired_at = $_SESSION['x-expired_at'];

        // Validasi expired
        if ($session_expired_at <= date('Y-m-d H:i:s')) {
            $generate_x_token = generate_x_token($base_url, $user_key, $access_key);
            $generate_x_token_arry = json_decode($generate_x_token, true);

            if ($generate_x_token_arry['status'] == 'success') {
                $_SESSION["x-token"]     = $generate_x_token_arry['session_token'];
                $_SESSION["x-expired_at"] = $generate_x_token_arry['expired_at'];
            } else {
                $_SESSION["x-token"] = "";
                $_SESSION["x-expired_at"] = "";
            }
        }
    }

    if (empty($_SESSION["x-token"])) {
        echo '<small class="text-danger">X-Token Gagal Dibuat</small>';
        exit();
    }

    // Validasi input
    $requiredFields = [
        'id_laman'      => 'ID Laman Tidak Boleh Kosong!',
        'order_id'      => 'Order ID Tidak Boleh Kosong!',
        'type_konten'   => 'Type Konten Tidak Boleh Kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            echo '<span class="text-danger">' . $errorMessage . '</span>';
            exit();
        }
    }

    //Buat Variabel
    $id_laman  = validateAndSanitizeInput($_POST['id_laman']);
    $order = validateAndSanitizeInput($_POST['order_id']);
    $type= validateAndSanitizeInput($_POST['type_konten']);

    //Routing Berdasarkan Type Konten
    if($type=="html"){
        if(empty($_POST['isi_content'])){
            $payload = [];
            $validasi_konten="Isi Konten Tidak Boleh Kosong!";
        }else{
            $isi_content=$_POST['isi_content'];
            $payload = [
                "id_laman"  => $id_laman,
                "order"     => $order,
                "type"      => $type,
                "content"   => $isi_content
            ];
            $validasi_konten="Valid";
        }
    }else{
        if(empty($_POST['position'])){
            $position="";
        }else{
            $position=$_POST['position']; 
        }
        if(empty($_POST['width'])){
            $width="";
        }else{
            $width=$_POST['width']; 
        }
        if(empty($_POST['unit'])){
            $unit="";
        }else{
            $unit=$_POST['unit']; 
        }
        if(empty($_POST['caption'])){
            $caption="";
        }else{
            $caption=$_POST['caption']; 
        }
        
        // Validasi Cover
        if (empty($_FILES['content']['name'])) {
            $validasi_konten="File content Tidak Boleh Kosong!";
        }else{
            // Validasi File
            $allowed_ext = ['png', 'jpg', 'jpeg', 'gif'];
            $file_name  = $_FILES['content']['name'];
            $file_size  = $_FILES['content']['size'];
            $file_tmp   = $_FILES['content']['tmp_name'];
            $file_error = $_FILES['content']['error'];

            if ($file_error !== UPLOAD_ERR_OK) {
                $validasi_konten="Terjadi kesalahan saat upload file!";
            }else{
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if (!in_array($file_ext, $allowed_ext)) {
                    $validasi_konten="File hanya boleh PNG, JPG, JPEG, atau GIF!";
                }else{
                    if ($file_size > 2 * 1024 * 1024) {
                        $validasi_konten="Ukuran file tidak boleh lebih dari 2MB!";
                    }else{
                        $validasi_konten="Valid";
                        
                        // Ubah file ke base64
                        $file_data = file_get_contents($file_tmp);
                        $base64    = 'data:image/' . $file_ext . ';base64,' . base64_encode($file_data);

                        $payload = [
                            "id_laman"   => $id_laman,
                            "order"     => $order,
                            "type"      => $type,
                            "position"  => $position,
                            "width"     => $width,
                            "unit"      => $unit,
                            "caption"   => $caption,
                            "content"   => $base64
                        ];
                    }
                }
            }
        }
    }
    if($validasi_konten!=="Valid"){
        echo '<span class="text-danger">' . $validasi_konten . '</span>';
    }else{
        // Kirim ke API
       $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/InsertKontenLaman.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => array(
            'x-token: '.$_SESSION['x-token'].'',
            'Content-Type: text/plain'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        // Response
        $response_arry = json_decode($response, true);

        if (!empty($response_arry['status']) && $response_arry['status'] === "success") {
            echo '<span class="text-success" id="NotifikasiTambahKontenBerhasil">Success</span>';
        } else {
            $msg = $response_arry['message'] ?? "Tidak ada response dari server";
            echo '<span class="text-danger">' . $msg . '</span>';
        }
        exit();
    }
?>
