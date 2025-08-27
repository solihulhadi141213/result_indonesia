<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if(empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
    }

    // Validasi input
    $requiredFields = [
        'title_page' => 'Judul/Nama Perusahaan Tidak Boleh Kosong',
        'kata_kunci' => 'Kata kunci tidak boleh kosong!',
        'deskripsi' => 'Setidaknya anda harus mengisi deskripsi aplikasi ini!',
        'alamat_bisnis' => 'Alamat perusahaan tidak boleh kosong!',
        'email_bisnis' => 'Alamat email perusahaan tidak boleh kosong!',
        'telepon_bisnis' => 'Nomor kontak perusahaan tidak boleh kosong!',
        'base_url' => 'Base URL tidak boleh kosong!',
        'author' => 'Author Aplikasi tidak boleh kosong!'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    // Sanitasi input
    $fields = [
        'title_page', 'kata_kunci', 'deskripsi', 'alamat_bisnis', 
        'email_bisnis', 'telepon_bisnis', 'base_url', 'author'
    ];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = validateAndSanitizeInput($_POST[$field]);
    }

    try {
        $Conn->beginTransaction();

        // Proses upload favicon
        $faviconName = null;
        if(!empty($_FILES['favicon']['name'])) {
            $faviconName = handleFileUpload('favicon', '../../assets/img/');
            if($faviconName !== false) {
                $stmt = $Conn->prepare("UPDATE setting_general SET favicon = :favicon WHERE id_setting_general = 1");
                $stmt->bindParam(':favicon', $faviconName);
                $stmt->execute();
            } else {
                throw new Exception("Gagal mengupload favicon");
            }
        }

        // Proses upload logo
        $logoName = null;
        if(!empty($_FILES['logo']['name'])) {
            $logoName = handleFileUpload('logo', '../../assets/img/');
            if($logoName !== false) {
                $stmt = $Conn->prepare("UPDATE setting_general SET logo = :logo WHERE id_setting_general = 1");
                $stmt->bindParam(':logo', $logoName);
                $stmt->execute();
            } else {
                throw new Exception("Gagal mengupload logo");
            }
        }

        // Update data utama
        $stmt = $Conn->prepare("UPDATE setting_general SET 
            title_page = :title_page,
            kata_kunci = :kata_kunci,
            deskripsi = :deskripsi,
            alamat_bisnis = :alamat_bisnis,
            email_bisnis = :email_bisnis,
            telepon_bisnis = :telepon_bisnis,
            base_url = :base_url,
            author = :author
            WHERE id_setting_general = 1");

        foreach ($data as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }

        if($stmt->execute()) {
            $Conn->commit();
            $_SESSION["NotifikasiSwal"] = "Simpan Setting General Berhasil";
            echo '<small class="text-success" id="NotifikasiSimpanSettingGeneralBerhasil">Success</small>';
        } else {
            $Conn->rollBack();
            echo '<small class="text-danger">Terjadi kesalahan pada saat update data pengaturan</small>';
        }

    } catch (PDOException $e) {
        $Conn->rollBack();
        echo '<small class="text-danger">Terjadi kesalahan database: '.htmlspecialchars($e->getMessage()).'</small>';
    } catch (Exception $e) {
        $Conn->rollBack();
        echo '<span class="text-danger">'.htmlspecialchars($e->getMessage()).'</span>';
    }

    // Fungsi untuk handle file upload
    function handleFileUpload($fieldName, $uploadPath) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'];
        $maxSize = 2000000; // 2MB
        
        $file = $_FILES[$fieldName];
        if(!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Tipe file ".$fieldName." hanya boleh JPG, JPEG, PNG dan GIF");
        }
        
        if($file['size'] > $maxSize) {
            throw new Exception("Ukuran file ".$fieldName." maksimal 2 MB");
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30).'.'.$extension;
        $destination = $uploadPath.$filename;
        
        if(move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        } else {
            throw new Exception("Terjadi kesalahan pada saat upload file ".$fieldName);
        }
    }
?>