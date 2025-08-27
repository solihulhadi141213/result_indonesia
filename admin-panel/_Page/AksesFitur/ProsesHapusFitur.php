<?php
    //Connection
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    $now = date('Y-m-d H:i:s');
    
    if(empty($_POST['id_akses_fitur'])) {
        echo '<code class="text-danger">ID Akses fitur tidak dapat ditangkap oleh sistem</code>';
        exit();
    }
    
    $id_akses_fitur = $_POST['id_akses_fitur'];
    
    try {
        // Dapatkan koneksi PDO
        $db = new Database();
        $Conn = $db->getConnection();
        
        // Mulai transaksi
        $Conn->beginTransaction();
        
        // 1. Hapus data akses_fitur
        $stmt = $Conn->prepare("DELETE FROM akses_fitur WHERE id_akses_fitur = :id_akses_fitur");
        $stmt->bindParam(':id_akses_fitur', $id_akses_fitur);
        $HapusAksesFitur = $stmt->execute();
        
        if(!$HapusAksesFitur) {
            throw new Exception("Hapus Data Gagal");
        }
        
        // 2. Hapus akses ijin
        $stmt = $Conn->prepare("DELETE FROM akses_ijin WHERE id_akses_fitur = :id_akses_fitur");
        $stmt->bindParam(':id_akses_fitur', $id_akses_fitur);
        $HapusAksesIjin = $stmt->execute();
        
        if(!$HapusAksesIjin) {
            throw new Exception("Hapus Data Ijin Akses Gagal");
        }
        
        // 3. Hapus akses referensi
        $stmt = $Conn->prepare("DELETE FROM akses_referensi WHERE id_akses_fitur = :id_akses_fitur");
        $stmt->bindParam(':id_akses_fitur', $id_akses_fitur);
        $HapusAksesReferensi = $stmt->execute();
        
        if(!$HapusAksesReferensi) {
            throw new Exception("Hapus Data Akses Referensi Gagal");
        }
        
        // 4. Input log
        $kategori_log = "Fitur Akses";
        $deskripsi_log = "Hapus Fitur Akses";
        $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);
        
        if($InputLog != "Success") {
            throw new Exception("Terjadi kesalahan pada saat menyimpan Log");
        }
        
        // Commit transaksi jika semua berhasil
        $Conn->commit();
        echo '<code class="text-success" id="NotifikasiHapusFiturBerhasil">Success</code>';
        
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        if($Conn->inTransaction()) {
            $Conn->rollBack();
        }
        echo '<code class="text-danger">' . $e->getMessage() . '</code>';
    }
?>