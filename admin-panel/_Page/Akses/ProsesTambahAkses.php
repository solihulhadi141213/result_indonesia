<?php
    // Koneksi dan konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi nama
    if (empty($_POST['nama_akses'])) {
        exit('<small class="text-danger">Nama tidak boleh kosong</small>');
    }

    // Validasi kontak
    $kontak_akses = $_POST['kontak_akses'] ?? '';
    if (empty($kontak_akses)) {
        exit('<small class="text-danger">Kontak tidak boleh kosong</small>');
    }
    if (strlen($kontak_akses) < 6 || strlen($kontak_akses) > 20 || !preg_match("/^[0-9]+$/", $kontak_akses)) {
        exit('<small class="text-danger">Kontak terdiri dari 6-20 karakter numerik</small>');
    }
    $cekKontak = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE kontak_akses = ?");
    $cekKontak->execute([$kontak_akses]);
    if ($cekKontak->fetchColumn() > 0) {
        exit('<small class="text-danger">Nomor kontak tersebut sudah terdaftar</small>');
    }

    // Validasi email
    $email_akses = $_POST['email_akses'] ?? '';
    if (empty($email_akses)) {
        exit('<small class="text-danger">Email tidak boleh kosong</small>');
    }
    $cekEmail = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE email_akses = ?");
    $cekEmail->execute([$email_akses]);
    if ($cekEmail->fetchColumn() > 0) {
        exit('<small class="text-danger">Email sudah digunakan</small>');
    }

    // Validasi password
    $password1 = $_POST['password1'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    if (empty($password1)) {
        exit('<small class="text-danger">Password tidak boleh kosong</small>');
    }
    if ($password1 !== $password2) {
        exit('<small class="text-danger">Password tidak sama</small>');
    }
    if (strlen($password1) < 6 || strlen($password1) > 20 || !preg_match("/^[a-zA-Z0-9]+$/", $password1)) {
        exit('<small class="text-danger">Password hanya terdiri dari 6-20 karakter alfanumerik</small>');
    }
    $passwordHash = md5($password1); // Disarankan SHA256 atau password_hash() untuk produksi

    // Level akses
    $akses = $_POST['akses'] ?? ($_POST['grup_akses'] ?? '');
    if (empty($akses)) {
        exit('<small class="text-danger">Level akses tidak boleh kosong</small>');
    }

    // Validasi dan upload gambar jika ada
    $namabaru = "";
    if (!empty($_FILES['image_akses']['name'])) {
        $file = $_FILES['image_akses'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $maxSize = 2000000;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = substr(md5(microtime() . rand()), 0, 30) . '.' . $ext;
        $path = "../../assets/img/User/" . $newFileName;

        if (!in_array($file['type'], $allowedTypes)) {
            exit('<small class="text-danger">Tipe file hanya boleh JPG, JPEG, PNG, dan GIF</small>');
        }
        if ($file['size'] > $maxSize) {
            exit('<small class="text-danger">Ukuran file tidak boleh lebih dari 2 MB</small>');
        }
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            exit('<small class="text-danger">Upload file gambar gagal</small>');
        }
        $namabaru = $newFileName;
    }

    // Bersihkan input
    $nama_akses = validateAndSanitizeInput($_POST['nama_akses']);
    $kontak_akses = validateAndSanitizeInput($kontak_akses);
    $email_akses = validateAndSanitizeInput($email_akses);
    $akses = validateAndSanitizeInput($akses);

    // Simpan ke database
    $sql = "INSERT INTO akses (nama_akses, kontak_akses, email_akses, password, image_akses, akses, datetime_daftar, datetime_update) 
            VALUES (:nama_akses, :kontak_akses, :email_akses, :password, :image_akses, :akses, :datetime_daftar, :datetime_update)";
    $stmt = $Conn->prepare($sql);
    $success = $stmt->execute([
        ':nama_akses' => $nama_akses,
        ':kontak_akses' => $kontak_akses,
        ':email_akses' => $email_akses,
        ':password' => $passwordHash,
        ':image_akses' => $namabaru,
        ':akses' => $akses,
        ':datetime_daftar' => $now,
        ':datetime_update' => $now
    ]);

    if ($success) {
        echo '<small class="text-success" id="NotifikasiTambahAksesBerhasil">Success</small>';
    } else {
        echo '<small class="text-danger">Terjadi kesalahan saat menyimpan data</small>';
    }
?>
