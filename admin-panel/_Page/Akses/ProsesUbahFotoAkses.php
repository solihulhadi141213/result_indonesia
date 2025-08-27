<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi Sesi
    if(empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
    }

    // Validasi Input
    if(empty($_POST['id_akses'])) {
        echo '<small class="text-danger">ID Akses tidak boleh kosong</small>';
        exit();
    }

    // Sanitasi Input
    $id_akses = validateAndSanitizeInput($_POST['id_akses']);

    // Validasi File Upload
    if(empty($_FILES['image_akses']['name'])) {
        echo '<small class="text-danger">File Foto tidak boleh kosong</small>';
        exit();
    }

    try {
        // Mulai transaksi
        $Conn->beginTransaction();

        // Ambil data gambar lama
        $ImageLama = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'image_akses');

        // Proses file upload
        $nama_gambar = $_FILES['image_akses']['name'];
        $ukuran_gambar = $_FILES['image_akses']['size']; 
        $tipe_gambar = $_FILES['image_akses']['type']; 
        $tmp_gambar = $_FILES['image_akses']['tmp_name'];

        // Generate nama file unik
        $timestamp = time() - strtotime('1970-01-01 00:00:00');
        $key = substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30);
        $FileNameRand = implode('-', str_split($key, 6));
        $Pecah = explode(".", $nama_gambar);
        $Ext = end($Pecah);
        $namabaru = "$FileNameRand.$Ext";
        $path = "../../assets/img/User/".$namabaru;

        // Validasi tipe file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/gif', 'image/png'];
        if(!in_array($tipe_gambar, $allowedTypes)) {
            throw new Exception('Tipe file hanya boleh JPG, JPEG, PNG dan GIF');
        }

        // Validasi ukuran file
        if($ukuran_gambar > 2000000) {
            throw new Exception('File gambar tidak boleh lebih dari 2 MB');
        }

        // Pindahkan file upload
        if(!move_uploaded_file($tmp_gambar, $path)) {
            throw new Exception('Upload file gambar gagal');
        }

        // Update database dengan PDO
        $stmt = $Conn->prepare("UPDATE akses SET 
            image_akses = :image_akses,
            datetime_update = :datetime_update
            WHERE id_akses = :id_akses");
        
        $stmt->execute([
            ':image_akses' => $namabaru,
            ':datetime_update' => $now,
            ':id_akses' => $id_akses
        ]);

        // Hapus file lama jika ada
        if(!empty($ImageLama)) {
            $fileLama = '../../assets/img/User/'.$ImageLama;
            if(file_exists($fileLama)) {
                if(!unlink($fileLama)) {
                    throw new Exception('Terjadi kesalahan pada saat menghapus foto lama');
                }
            }
        }

        // Commit transaksi jika semua berhasil
        $Conn->commit();
        echo '<small class="text-success" id="NotifikasiUbahFotoAksesBerhasil">Success</small>';

    } catch (PDOException $e) {
        // Rollback jika terjadi error PDO
        $Conn->rollBack();
        
        // Hapus file baru yang sudah diupload jika ada
        if(isset($path) && file_exists($path)) {
            unlink($path);
        }
        
        echo '<small class="text-danger">Terjadi kesalahan database: ' . htmlspecialchars($e->getMessage()) . '</small>';
    } catch (Exception $e) {
        // Rollback jika terjadi error lainnya
        $Conn->rollBack();
        
        // Hapus file baru yang sudah diupload jika ada
        if(isset($path) && file_exists($path)) {
            unlink($path);
        }
        
        echo '<small class="text-danger">' . htmlspecialchars($e->getMessage()) . '</small>';
    }
?>