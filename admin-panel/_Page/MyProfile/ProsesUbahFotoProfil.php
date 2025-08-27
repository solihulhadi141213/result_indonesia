<?php
    // Koneksi dan konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    if (empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Login Sudah Berakhir, Silahkan Login Ulang</small>';
    } else {
        $id_akses = validateAndSanitizeInput($SessionIdAkses);
        $ImageLama = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'image_akses');

        if (empty($_FILES['image_akses']['name'])) {
            echo '<small class="text-danger">File Foto tidak boleh kosong</small>';
        } else {
            $nama_gambar = $_FILES['image_akses']['name'];
            $ukuran_gambar = $_FILES['image_akses']['size'];
            $tipe_gambar = $_FILES['image_akses']['type'];
            $tmp_gambar = $_FILES['image_akses']['tmp_name'];

            // Generate nama file acak
            $key = implode('', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
            $ext = pathinfo($nama_gambar, PATHINFO_EXTENSION);
            $namabaru = $key . "." . $ext;
            $path = "../../assets/img/User/" . $namabaru;

            // Validasi tipe file
            $allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
            if (!in_array($tipe_gambar, $allowedTypes)) {
                echo '<small class="text-danger">Tipe file hanya boleh JPG, JPEG, PNG, dan GIF</small>';
                exit;
            }

            // Validasi ukuran file
            if ($ukuran_gambar > 2000000) {
                echo '<small class="text-danger">File gambar tidak boleh lebih dari 2 MB</small>';
                exit;
            }

            // Proses upload file
            if (!move_uploaded_file($tmp_gambar, $path)) {
                echo '<small class="text-danger">Upload file gambar gagal</small>';
                exit;
            }

            // Simpan ke database dengan PDO
            try {
                $stmt = $Conn->prepare("UPDATE akses SET image_akses = :image_akses, datetime_update = :datetime_update WHERE id_akses = :id_akses");
                $stmt->bindValue(':image_akses', $namabaru, PDO::PARAM_STR);
                $stmt->bindValue(':datetime_update', $now, PDO::PARAM_STR);
                $stmt->bindValue(':id_akses', $id_akses, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    // Hapus gambar lama jika ada
                    if (!empty($ImageLama)) {
                        $fileLama = "../../assets/img/User/" . $ImageLama;
                        if (file_exists($fileLama)) {
                            if (!unlink($fileLama)) {
                                echo '<span class="text-danger">Gagal menghapus foto lama</span>';
                                exit;
                            }
                        }
                    }

                    $_SESSION["NotifikasiSwal"] = "Ubah Foto Profil Berhasil";
                    echo '<small class="text-success" id="NotifikasiUbahFotoProfilBerhasil">Success</small>';
                } else {
                    echo '<small class="text-danger">Terjadi kesalahan saat menyimpan ke database</small>';
                }
            } catch (PDOException $e) {
                echo '<small class="text-danger">Error: ' . $e->getMessage() . '</small>';
            }
        }
    }
?>
