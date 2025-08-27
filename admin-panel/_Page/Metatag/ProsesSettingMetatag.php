<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if(empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
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
        echo '<small class="text-danger">X-Token Gagal Dibuat</small>';
        exit();
    }
    
    // Validasi input
    $requiredFields = [
        'type_website'      => 'Type Website Tidak Boleh Kosong!',
        'title_website'     => 'Title website tidak boleh kosong!',
        'author_website'    => 'Author tidak boleh kosong!',
        'robots_web'        => 'Robots tidak boleh kosong!',
        'base_url_web'      => 'Base URL tidak boleh kosong!',
        'keyword_web'       => 'Keyword tidak boleh kosong!',
        'viewport_web'      => 'Viewport tidak boleh kosong!',
        'description_web'   => 'Deskripsi tidak boleh kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    //Buatkan Variabelnya
    $type_website=validateAndSanitizeInput($_POST['type_website']);
    $title_website=validateAndSanitizeInput($_POST['title_website']);
    $author_website=validateAndSanitizeInput($_POST['author_website']);
    $robots_web=validateAndSanitizeInput($_POST['robots_web']);
    $base_url_web=validateAndSanitizeInput($_POST['base_url_web']);
    $keyword_web=validateAndSanitizeInput($_POST['keyword_web']);
    $viewport_web=validateAndSanitizeInput($_POST['viewport_web']);
    $description_web=validateAndSanitizeInput($_POST['description_web']);
    
    //Inisiasi Variabel tidak wajib
    if(!empty($_POST['url_ogimage'])){
        $url_ogimage=$_POST['url_ogimage'];
    }else{
        $url_ogimage="";
    }
    if(!empty($_POST['url_logoimage'])){
        $url_logoimage=$_POST['url_logoimage'];
    }else{
        $url_logoimage="";
    }

    //Apabila Tidak Ada File Yang Di Upload meta_tag_ogimage
    if(empty($_FILES['meta_tag_ogimage']['name'])){
        
        //Ubah $url_ogimage menjadi base 64
        $image_data_ogimage = file_get_contents($url_ogimage);

        if ($image_data_ogimage === false) {
            die("Gagal mengambil gambar dari URL");
        }

        // Mengkonversi ke base64
        $base64_image_ogimage = base64_encode($image_data_ogimage);

        // Menyimpan dalam variabel (opsional menentukan tipe MIME)

        if ($image_data_ogimage === false) {
            die("Gagal mengambil gambar dari URL");
        }

        // Gunakan finfo untuk deteksi MIME type dari data biner
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type_ogimage = finfo_buffer($finfo, $image_data_ogimage);
        finfo_close($finfo);

        $base64_image_ogimage = base64_encode($image_data_ogimage);
        $base64_string_ogimage = "data:$mime_type_ogimage;base64,$base64_image_ogimage";

    }else{
        //Jika Ada Lakukan Validasi gambar tidak lebih dari 2 mb dan hanya bolaeh png, jpg, jpeg, dan gif kemudian jika valid akan dibuatkan variabe $base64_string_ogimage
        $uploaded_file = $_FILES['meta_tag_ogimage'];
        $max_size = 2 * 1024 * 1024; // 2 MB
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        
        // Validasi ukuran file
        if($uploaded_file['size'] > $max_size) {
            die("Ukuran gambar terlalu besar. Maksimal 2MB.");
        }
        
        // Validasi tipe file
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $uploaded_file['tmp_name']);
        finfo_close($file_info);
        
        if(!in_array($mime_type, $allowed_types)) {
            die("Format gambar tidak valid. Hanya diperbolehkan PNG, JPG, JPEG, atau GIF.");
        }
        
        // Baca file dan konversi ke base64
        $image_data = file_get_contents($uploaded_file['tmp_name']);
        $base64_image = base64_encode($image_data);
        $base64_string_ogimage = "data:$mime_type;base64,$base64_image";
    }
    
    //Apabila Tidak Ada File Yang Di Upload meta_tag_logoimage
    if(empty($_FILES['meta_tag_logoimage']['name'])){
        
        //Ubah $url_logoimage menjadi base 64
        $image_data_logoimage = file_get_contents($url_logoimage);

        if ($image_data_logoimage === false) {
            die("Gagal mengambil gambar dari URL");
        }

        // Mengkonversi ke base64
        $base64_image_logoimage = base64_encode($image_data_logoimage);

        // Mengkonversi ke base64
        $base64_image_logoimage = base64_encode($image_data_logoimage);

        // Menentukan tipe MIME menggunakan finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type_logoimage = finfo_buffer($finfo, $image_data_logoimage);
        finfo_close($finfo);

        // Menyimpan dalam variabel dengan format data URI
        $base64_string_logoimage = "data:$mime_type_logoimage;base64,$base64_image_logoimage";

    }else{
        //Jika Ada Lakukan Validasi gambar tidak lebih dari 2 mb dan hanya bolaeh png, jpg, jpeg, dan gif kemudian jika valid akan dibuatkan variabe $base64_string_ogimage
        $uploaded_file = $_FILES['meta_tag_logoimage'];
        $max_size = 2 * 1024 * 1024; // 2 MB
        $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
        
        // Validasi ukuran file
        if($uploaded_file['size'] > $max_size) {
            die("Ukuran gambar logo terlalu besar. Maksimal 2MB.");
        }
        
        // Validasi tipe file
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $uploaded_file['tmp_name']);
        finfo_close($file_info);
        
        if(!in_array($mime_type, $allowed_types)) {
            die("Format gambar logo tidak valid. Hanya diperbolehkan PNG, JPG, JPEG, atau GIF.");
        }
        
        // Baca file dan konversi ke base64
        $image_data = file_get_contents($uploaded_file['tmp_name']);
        $base64_image = base64_encode($image_data);
        $base64_string_logoimage = "data:$mime_type;base64,$base64_image";
    }

    //Kirim Data Melalui service API
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => ''.$base_url.'/_Api/Metatag.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS =>'{
        "type": "'.$type_website.'",
        "title": "'.$title_website.'",
        "author": "'.$author_website.'",
        "robots": "'.$robots_web.'",
        "base_url": "'.$base_url_web.'",
        "keywords": "'.$keyword_web.'",
        "og-image": "'.$base64_string_ogimage.'",
        "viewport": "'.$viewport_web.'",
        "logo-image": "'.$base64_string_logoimage.'",
        "description": "'.$description_web.'"
    }',
    CURLOPT_HTTPHEADER => array(
        'x-token: '.$_SESSION["x-token"].'',
        'Content-Type: text/plain'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    //Ubah response jadi arry
    $response_arry=json_decode($response, true);
    $status_response=$response_arry['status'];
    if($status_response=="success"){
        $_SESSION['NotifikasiSwal']="Metatag Berhasil Disimpan";
        echo '<span class="text-success" id="NotifikasiSimpanMetatagBerhasil">Success</span>';
        exit();
    }else{
        echo '<span class="text-danger">'.$response_arry['message'].'</span>';
        exit();
    }
?>