<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    
    // Validasi Sesi
    if(empty($SessionIdAkses)) {
        echo '<span class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</span>';
        exit();
    }

    // Validasi Input
    if(empty($_POST['id_akses'])) {
        echo '<span class="text-danger">ID Akses tidak boleh kosong</span>';
        exit();
    }

    // Sanitasi Input
    $id_akses = validateAndSanitizeInput($_POST['id_akses']);

    try {
        // Mulai transaksi
        $Conn->beginTransaction();

        // Ambil data gambar sebelum menghapus
        $image_akses = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'image_akses');

        // Hapus data dari database
        $stmt = $Conn->prepare("DELETE FROM akses WHERE id_akses = ?");
        $stmt->execute([$id_akses]);
        
        // Cek apakah data berhasil dihapus
        if($stmt->rowCount() > 0) {
            // Jika ada gambar, hapus file gambar
            if(!empty($image_akses)) {
                $file = '../../assets/img/User/'.$image_akses;
                if(file_exists($file)) {
                    if(!unlink($file)) {
                        throw new Exception("Gagal menghapus file gambar");
                    }
                }
            }
            
            // Commit transaksi jika semua berhasil
            $Conn->commit();
            echo '<span class="text-success" id="NotifikasiHapusAksesBerhasil">Success</span>';
        } else {
            // Rollback jika tidak ada data yang terhapus
            $Conn->rollBack();
            echo '<span class="text-danger">Data tidak ditemukan atau gagal dihapus</span>';
        }

    } catch (PDOException $e) {
        // Rollback jika terjadi error PDO
        $Conn->rollBack();
        echo '<span class="text-danger">Terjadi kesalahan database: ' . htmlspecialchars($e->getMessage()) . '</span>';
    } catch (Exception $e) {
        // Rollback jika terjadi error lainnya
        $Conn->rollBack();
        echo '<span class="text-danger">' . htmlspecialchars($e->getMessage()) . '</span>';
    }
?>