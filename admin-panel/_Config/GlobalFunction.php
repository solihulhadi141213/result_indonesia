<?php
    //Special Captcha
    function GenerateCaptcha($length) {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Menghindari karakter ambigu
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $captcha;
    }
    function GenerateKodeBarang($length){
        $token = "";
        $codeAlphabet= "0123456789";
        $max = strlen($codeAlphabet); // edited
        
        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }
        return $token;
    }
    //Membuat Token
    function GenerateToken($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }

    //Membuat Randome String
    function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }

    //Membersihkan Variabel
    function validateAndSanitizeInput($input) {
        // Menghapus karakter yang tidak diinginkan
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        $input = addslashes($input);
        return $input;
    }

    //Data Detail
    function GetDetailData($Conn, $Tabel, $Param, $Value, $Colom) {
        // Validasi input
        if (empty($Conn)) {
            return "No Database Connection";
        }
        if (empty($Tabel)) {
            return "No Table Selected";
        }
        if (empty($Param)) {
            return "No Parameter Selected";
        }
        if (empty($Value)) {
            return "No Value Provided";
        }
        if (empty($Colom)) {
            return "No Column Selected";
        }

        // Validasi nama kolom dan tabel (hindari SQL Injection - minimal manual whitelist bisa ditambahkan jika perlu)
        // Di PDO tidak bisa bind nama tabel/kolom, jadi perlu divalidasi secara ketat jika input berasal dari user
        $allowedChars = '/^[a-zA-Z0-9_]+$/';
        if (!preg_match($allowedChars, $Tabel) || !preg_match($allowedChars, $Param) || !preg_match($allowedChars, $Colom)) {
            return "Invalid table, column, or parameter name";
        }

        // Buat query dinamis dengan nama kolom dan tabel disisipkan secara langsung (karena tidak bisa dibind)
        $sql = "SELECT `$Colom` FROM `$Tabel` WHERE `$Param` = :value LIMIT 1";

        try {
            $stmt = $Conn->prepare($sql);
            $stmt->bindValue(':value', $Value, PDO::PARAM_STR);
            $stmt->execute();
            $Data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $Data[$Colom] ?? "";
        } catch (PDOException $e) {
            return "Query Error: " . $e->getMessage();
        }
    }

    
    //Loging
    function addLog($Conn, $id_akses, $datetime_log, $kategori_log, $deskripsi_log) {
        try {
            $sql = "INSERT INTO log (
                id_akses,
                datetime_log,
                kategori_log,
                deskripsi_log
            ) VALUES (
                :id_akses,
                :datetime_log,
                :kategori_log,
                :deskripsi_log
            )";
            
            $stmt = $Conn->prepare($sql);
            $stmt->bindParam(':id_akses', $id_akses);
            $stmt->bindParam(':datetime_log', $datetime_log);
            $stmt->bindParam(':kategori_log', $kategori_log);
            $stmt->bindParam(':deskripsi_log', $deskripsi_log);
            
            $stmt->execute();
            
            return "Success";
        } catch (PDOException $e) {
            // Anda bisa menambahkan logging error di sini jika diperlukan
            return "Input Log Gagal: " . $e->getMessage();
        }
    }
    
    //Membuat Randome Number
    function generateRandomNumber($length) {
        $characters = '0123456789';
        $randomString = '';
        $charLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charLength - 1)];
        }
        return $randomString;
    }
    //Send Email
    function SendEmail($NamaTujuan,$EmailTujuan,$Subjek,$Pesan,$email_gateway,$password_gateway,$url_provider,$nama_pengirim,$port_gateway,$url_service) {
        if(empty($NamaTujuan)){
            $Response="Nama tujuan pesan tidak boleh kosong!";
        }else{
            if(empty($EmailTujuan)){
                $Response="Email tujuan pesan tidak boleh kosong!";
            }else{
                if(empty($Subjek)){
                    $Response="Subjek pesan tidak boleh kosong!";
                }else{
                    if(empty($Pesan)){
                        $Response="Isi Pesan Tidak Boleh Kosong!";
                    }else{
                        if(empty($email_gateway)){
                            $Response="Akun Email Gateway Tidak Boleh Kosong!";
                        }else{
                            if(empty($password_gateway)){
                                $Response="Password Tidak Boleh Kosong!";
                            }else{
                                if(empty($url_provider)){
                                    $Response="URL Provider Tidak Boleh Kosong!";
                                }else{
                                    if(empty($nama_pengirim)){
                                        $Response="Nama pengirim Tidak Boleh Kosong!";
                                    }else{
                                        if(empty($port_gateway)){
                                            $Response="Port Tidak Boleh Kosong!";
                                        }else{
                                            if(empty($url_service)){
                                                $Response="Url Service Tidak Boleh Kosong!";
                                            }else{
                                                //Kirim email
                                                $ch = curl_init();
                                                $headers = array(
                                                    'Content-Type: Application/JSON',          
                                                    'Accept: Application/JSON'     
                                                );
                                                $arr = array(
                                                    "subjek" => "$Subjek",
                                                    "email_asal" => "$email_gateway",
                                                    "password_email_asal" => "$password_gateway",
                                                    "url_provider" => "$url_provider",
                                                    "nama_pengirim" => "$nama_pengirim",
                                                    "email_tujuan" => "$EmailTujuan",
                                                    "nama_tujuan" => "$NamaTujuan",
                                                    "pesan" => "$Pesan",
                                                    "port" => "$port_gateway"
                                                );
                                                $json = json_encode($arr);
                                                curl_setopt($ch, CURLOPT_URL, "$url_service");
                                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                                curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                                                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                $content = curl_exec($ch);
                                                $err = curl_error($ch);
                                                curl_close($ch);
                                                $get =json_decode($content, true);
                                                $Response=$content;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $Response;
    }
    //Delete Data
    function DeleteData($Conn,$NamaDb,$NamaParam,$IdParam){
        $HapusData = mysqli_query($Conn, "DELETE FROM $NamaDb WHERE $NamaParam='$IdParam'") or die(mysqli_error($Conn));
        if($HapusData){
            $Response="Success";
        }else{
            $Response="Hapus Data Gagal";
        }
        return $Response;
    }
    function NamaHari($no){
        if($no==1){
            $Response="Senin";
        }else{
            if($no==2){
                $Response="Selasa";
            }else{
                if($no==3){
                    $Response="Rabu";
                }else{
                    if($no==4){
                        $Response="Kamis";
                    }else{
                        if($no==5){
                            $Response="Jumat";
                        }else{
                            if($no==6){
                                $Response="Sabtu";
                            }else{
                                if($no==7){
                                    $Response="Minggu";
                                }else{
                                    $Response="None";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $Response;
    }
    function checkImageGifExists($jsonString,$type) {
        // Mengurai string JSON menjadi array PHP
        $data = json_decode($jsonString, true);
    
        // Pengecekan apakah $type ada dalam salah satu elemen array
        foreach ($data as $item) {
            if ($item['type'] === $type) {
                return true; // Jika ditemukan, kembalikan true
            }
        }
    
        return false; // Jika tidak ditemukan, kembalikan false
    }
    function MimeTiTipe($mim) {
        $Referensi = [
            ['name' => 'PDF', 'type' => 'application/pdf'],
            ['name' => 'XLS', 'type' => 'application/vnd.ms-excel'],
            ['name' => 'XLSX', 'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            ['name' => 'CSV1', 'type' => 'text/csv'],
            ['name' => 'CSV2', 'type' => 'application/csv'],
            ['name' => 'CSV3', 'type' => 'text/plain'],
            ['name' => 'DOC', 'type' => 'application/msword'],
            ['name' => 'DOCX', 'type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            ['name' => 'JPEG', 'type' => 'image/jpeg'],
            ['name' => 'PNG', 'type' => 'image/png'],
            ['name' => 'GIF', 'type' => 'image/gif'],
        ];
        foreach ($Referensi as $item) {
            if ($item['type'] === $mim) {
                $matchedIds[] = $item['name'];
            }
        }
        $NamaFile=implode(', ', $matchedIds);
        return $NamaFile;
    }
    
    function validateUploadedFile($file,$size) {
        // Tipe file yang diperbolehkan
        $allowedMimeTypes = [
            'image/jpeg', // Untuk file .jpg dan .jpeg
            'image/png',  // Untuk file .png
            'image/gif',  // Untuk file .gif
        ];
    
        // Maksimal ukuran file (5MB, misalnya)
        $maxFileSize = $size * 1024 * 1024; // 5MB dalam byte
    
        // Periksa apakah file diunggah tanpa error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Terjadi kesalahan saat mengunggah file.";
        }
    
        // Periksa ukuran file
        if ($file['size'] > $maxFileSize) {
            return "Ukuran file terlalu besar. Maksimal 5MB.";
        }
    
        // Dapatkan MIME type file
        $fileMimeType = mime_content_type($file['tmp_name']);
    
        // Validasi tipe MIME
        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            return "Tipe file tidak valid. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    
        // Jika semua validasi lolos
        return true;
    }
    function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        // Pastikan format sesuai dan tanggal valid (misalnya tidak ada 30 Februari)
        return $d && $d->format($format) === $date;
    }
    function IjinAksesSaya($Conn, $SessionIdAkses, $KodeFitur) {
        try {
            $stmt = $Conn->prepare("SELECT id_akses FROM akses_ijin WHERE id_akses = :id_akses AND kode = :kode LIMIT 1");
            $stmt->bindValue(':id_akses', $SessionIdAkses, PDO::PARAM_INT);
            $stmt->bindValue(':kode', $KodeFitur, PDO::PARAM_STR);
            $stmt->execute();

            $DataParam = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($DataParam['id_akses'])) {
                return "Ada";
            } else {
                return "Tidak Ada";
            }
        } catch (PDOException $e) {
            // Sebaiknya ditangani lebih baik di produksi
            return "Error: " . $e->getMessage();
        }
    }

    function CekFiturEntitias($Conn,$uuid_akses_entitas,$id_akses_fitur){
        $QryParam = mysqli_query($Conn,"SELECT * FROM akses_referensi WHERE uuid_akses_entitas='$uuid_akses_entitas' AND id_akses_fitur='$id_akses_fitur'")or die(mysqli_error($Conn));
        $DataParam = mysqli_fetch_array($QryParam);
        if(empty($DataParam['id_akses_referensi'])){
            $Response="Tidak Ada";
        }else{
            $Response="Ada";
        }
        return $Response;
    }
    function CheckParameterOnJson($jsonString,$type,$parameter) {
        // Mengurai string JSON menjadi array PHP
        $data = json_decode($jsonString, true);
    
        // Pengecekan apakah $type ada dalam salah satu elemen array
        foreach ($data as $item) {
            if ($item[$parameter] === $type) {
                return true; // Jika ditemukan, kembalikan true
            }
        }
    
        return false; // Jika tidak ditemukan, kembalikan false
    }
    function ValidasiKutip($string) {
        // Pola untuk mendeteksi tanda kutip tunggal atau ganda
        $pola = "/['\"]/";
    
        // Menggunakan preg_match untuk mengecek apakah string mengandung tanda kutip
        if (preg_match($pola, $string)) {
            return false; // String mengandung tanda kutip
        } else {
            return true; // String tidak mengandung tanda kutip
        }
    }
    function ValidasiHanyaAngka($string) {
        // Pola untuk mendeteksi hanya angka
        $pola = "/^\d+$/";
    
        // Menggunakan preg_match untuk mengecek apakah string hanya mengandung angka
        return preg_match($pola, $string);
    }
    function formatRupiah($angka,$mata_uang,$zero_padding) {
        return ''.$mata_uang.' ' . number_format($angka, $zero_padding, ',', '.');
    }
    // Function to get value by UID
    function getValueByUid($data, $uid) {
        foreach ($data as $item) {
            if ($item['uid'] == $uid) {
                return $item['value'];
            }
        }
        return null;
    }
    // Function to get value by UID
    function SearchStringIntoArray($data, $keyword) {
        foreach ($data as $item) {
            if ($item== $keyword) {
                return true;
            }
        }
        return false;
    }
    function GetSometingByKeyword($DatArray,$keyword_by,$keyword,$value_parameter) {
        foreach ($DatArray as $item) {
            if ($item[$keyword_by] == $keyword) {
                return $item[$value_parameter];
            }
        }
        return null;
    }
    function ValidasiRekening($input) {
        // Cek apakah input hanya berisi angka
        if (!ctype_digit($input)) {
            return "Nomor Rekening harus berisi angka saja.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Nomor Rekening tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    function ValidasiBank($input) {
        // Cek apakah input hanya berisi huruf dan spasi
        if (!preg_match('/^[a-zA-Z\s]*$/', $input)) {
            return "Nama Bank hanya boleh berisi huruf dan spasi.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Nama Bank tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    function ValidasiKontak($input) {
        // Cek apakah input hanya berisi huruf dan spasi
        if (!preg_match('/^[0-9]*$/', $input)) {
            return "Kontak hanya boleh berisi huruf dan spasi.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Kontak tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    function maskAccountNumber($accountNumber) {
        // Hitung panjang nomor rekening
        $length = strlen($accountNumber);
        
        // Ambil 4 digit terakhir
        $lastFourDigits = substr($accountNumber, -4);
        
        // Buat string bintang dengan panjang sesuai
        $maskedPart = str_repeat('*', $length - 4);
        
        // Gabungkan bagian yang di-mask dengan 4 digit terakhir
        return $maskedPart . $lastFourDigits;
    }
    function TokenValidation($input) {
        // Cek apakah input hanya berisi angka dan huruf
        if (!preg_match('/^[a-zA-Z0-9]*$/', $input)) {
            return "Input hanya boleh berisi huruf dan angka.";
        }
    
        // Cek apakah panjang input tidak lebih dari 20 karakter
        if (strlen($input) > 20) {
            return "Input tidak boleh lebih dari 20 karakter.";
        }
    
        return "Valid";
    }
    //Creat Captcha
    function CreatCaptcha($Conn) {
        date_default_timezone_set("Asia/Jakarta");
        $now=date('Y-m-d H:i:s');
        //hasil kode acak disimpan di $code
        $length=6;
        $Captcha = "";
        $codeAlphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijkmnpqrstuvwxyz";
        $codeAlphabet.= "23456789";
        $max = strlen($codeAlphabet); // edited
        for ($i=0; $i < $length; $i++) {
            $Captcha .= $codeAlphabet[random_int(0, $max-1)];
        }
        //Ubah ke md5
        $CodeMd5=md5($Captcha);
        //Melakukan Pengecekan Kode Captcha Yang expired
        $expired=date('Y-m-d H:i:s', time() - 60);
        $query = mysqli_query($Conn, "SELECT*FROM log_captcha WHERE datetime_creat<'$expired'");
        while ($data = mysqli_fetch_array($query)) {
            $id_log_captcha= $data['id_log_captcha'];
            //Hapus Captcha
            $Hapus = mysqli_query($Conn, "DELETE FROM log_captcha WHERE id_log_captcha='$id_log_captcha'") or die(mysqli_error($Conn));
        }
        //Simpan Ke Database
        $EntryCaptcha="INSERT INTO log_captcha (
            datetime_creat,
            captcha
        ) VALUES (
            '$now',
            '$CodeMd5'
        )";
        $InputCaptcha=mysqli_query($Conn, $EntryCaptcha);
        if($InputCaptcha){
            $CaptchaFix=$Captcha;
        }else{
            $CaptchaFix="";
        }
        return $CaptchaFix;
    }
    function hitung_usia($tanggal_lahir) {
        $birthDate = new DateTime($tanggal_lahir);
        $today = new DateTime("today");
        if ($birthDate > $today) {
            exit("Usia tidak valid");
        }
        $y = $today->diff($birthDate)->y;
        $m = $today->diff($birthDate)->m;
        $d = $today->diff($birthDate)->d;
    
        if ($y > 0) {
            return $y." tahun";
        } else if ($m > 0) {
            return $m." bulan";
        } else {
            return $d." hari";
        }
    }
    function ValidasiTanggal($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    function calculateExpirationTimeFromDateTime($dateTime, $milliseconds) {
        // Membuat objek DateTime dari string input
        $date = new DateTime($dateTime);
    
        // Mengonversi milidetik ke detik dan mikrodetik
        $seconds = floor($milliseconds / 1000);
        $microseconds = ($milliseconds % 1000) * 1000;
    
        // Menambahkan detik dan mikrodetik ke objek DateTime
        $date->add(new DateInterval("PT{$seconds}S"));
        // Menambahkan mikrodetik menggunakan metode modify
        $date->modify("+{$microseconds} microseconds");
    
        // Mengembalikan waktu kedaluwarsa dalam format YYYY-mm-dd HH:ii:ss.uuu
        return $date->format('Y-m-d H:i:s');
    }
    function generateUuidV1() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 36;
        $uuid = '';
        for ($i = 0; $i < $length; $i++) {
            $uuid .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $uuid;
    }
    function getNamaBulan($angkaBulan) {
        // Array dengan nama-nama bulan
        $namaBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
    
        // Mengembalikan nama bulan berdasarkan angka
        return $namaBulan[$angkaBulan] ?? 'Bulan tidak valid';
    }
    function pembulatan_nilai($nilai){
        $nilai = (float) $nilai;
        $nilai = ($nilai == floor($nilai)) ? (int)$nilai : $nilai;
        return $nilai;
    }

    
    function TanggalIndo($tanggal) {
        if (empty($tanggal) || $tanggal == "0000-00-00") {
            return "-";
        }
        
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        
        $pecah = explode('-', $tanggal);
        $tgl = (int)$pecah[2];
        $bln = $bulan[(int)$pecah[1]];
        $thn = $pecah[0];
        
        return $tgl . ' ' . $bln . ' ' . $thn;
    }

    //Generate X-Token
    function generate_x_token($base_url, $user_key, $access_key) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ''.$base_url.'/_Api/_GetToken.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS =>'{
                "user_key" : "'.$user_key.'",
                "access_key" : "'.$access_key.'"
            }'
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    //Detail Layout
    function DetailLayout($base_url, $x_token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => ''.$base_url.'/_Api/DetailLayout.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'x-token: '.$x_token.''
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return $response;
    }

    //Fungsi Delete
    function DeleteServer($url, $x_token, $payload) {
        //Kirim Data Melalui service API
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ''.$url.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                'x-token: '.$x_token.'',
                'Content-Type: text/plain'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

?>