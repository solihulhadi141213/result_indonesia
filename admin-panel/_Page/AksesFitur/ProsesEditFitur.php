<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    
    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    try {
        // Dapatkan koneksi PDO
        $db = new Database();
        $Conn = $db->getConnection();
        
        // Validasi input
        if(empty($_POST['nama'])) {
            throw new Exception("Nama fitur tidak boleh kosong");
        }
        if(empty($_POST['kategori'])) {
            throw new Exception("Kategori tidak boleh kosong");
        }
        if(empty($_POST['kode'])) {
            throw new Exception("Kode tidak boleh kosong");
        }
        if(empty($_POST['keterangan'])) {
            throw new Exception("Setidaknya anda harus memberikan keterangan untuk fitur tersebut");
        }
        if(empty($_POST['id_akses_fitur'])) {
            throw new Exception("ID Fitur Tidak Boleh Kosong");
        }
        
        // Validasi panjang kode
        $JumlahKarakterKode = strlen($_POST['kode']);
        if($JumlahKarakterKode > 20 || $JumlahKarakterKode < 6) {
            throw new Exception("Kode terdiri dari 6-20 karakter");
        }
        
        // Sanitasi input
        $id_akses_fitur = $_POST['id_akses_fitur'];
        $nama = validateAndSanitizeInput($_POST['nama']);
        $kategori = validateAndSanitizeInput($_POST['kategori']);
        $kode = validateAndSanitizeInput($_POST['kode']);
        $keterangan = validateAndSanitizeInput($_POST['keterangan']);
        
        // Mulai transaksi
        $Conn->beginTransaction();
        
        // Validasi duplikat kode dan nama
        $NamaFitur = GetDetailData($Conn, 'akses_fitur', 'id_akses_fitur', $id_akses_fitur, 'nama');
        $KodeFitur = GetDetailData($Conn, 'akses_fitur', 'id_akses_fitur', $id_akses_fitur, 'kode');
        
        if($nama != $NamaFitur) {
            $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses_fitur WHERE nama = :nama");
            $stmt->bindParam(':nama', $nama);
            $stmt->execute();
            $ValidasiNamaDuplikat = $stmt->fetchColumn();
            
            if($ValidasiNamaDuplikat > 0) {
                throw new Exception("Nama Fitur tersebut sudah terdaftar");
            }
        }
        
        if($kode != $KodeFitur) {
            $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses_fitur WHERE kode = :kode");
            $stmt->bindParam(':kode', $kode);
            $stmt->execute();
            $ValidasiKodeDuplikat = $stmt->fetchColumn();
            
            if($ValidasiKodeDuplikat > 0) {
                throw new Exception("Kode tersebut sudah terdaftar");
            }
        }
        
        // Update data akses_fitur
        $stmt = $Conn->prepare("UPDATE akses_fitur SET 
            kategori = :kategori,
            nama = :nama,
            kode = :kode,
            keterangan = :keterangan
            WHERE id_akses_fitur = :id_akses_fitur");
        
        $stmt->bindParam(':kategori', $kategori);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':keterangan', $keterangan);
        $stmt->bindParam(':id_akses_fitur', $id_akses_fitur);
        
        $UpdateFiturAkses = $stmt->execute();
        
        if(!$UpdateFiturAkses) {
            throw new Exception("Terjadi kesalahan pada saat update fitur");
        }
        
        // Update akses_ijin jika ada pengguna
        $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses_ijin WHERE id_akses_fitur = :id_akses_fitur");
        $stmt->bindParam(':id_akses_fitur', $id_akses_fitur);
        $stmt->execute();
        $JumlahPengguna = $stmt->fetchColumn();
        
        if($JumlahPengguna > 0) {
            $stmt = $Conn->prepare("UPDATE akses_ijin SET 
                kode = :kode,
                nama = :nama,
                kategori = :kategori
                WHERE id_akses_fitur = :id_akses_fitur");
            
            $stmt->bindParam(':kode', $kode);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':kategori', $kategori);
            $stmt->bindParam(':id_akses_fitur', $id_akses_fitur);
            
            $UpdateAksesIjin = $stmt->execute();
            
            if(!$UpdateAksesIjin) {
                throw new Exception("Terjadi kesalahan pada saat update Akses ijin");
            }
        }
        
        // Input log
        $kategori_log = "Fitur Akses";
        $deskripsi_log = "Edit Fitur Akses";
        $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);
        
        if($InputLog != "Success") {
            throw new Exception("Terjadi kesalahan pada saat menyimpan Log");
        }
        
        // Commit transaksi jika semua berhasil
        $Conn->commit();
        echo '<code class="text-success" id="NotifikasiEditFiturBerhasil">Success</code>';
        
    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        if($Conn->inTransaction()) {
            $Conn->rollBack();
        }
        echo '<code class="text-danger">' . $e->getMessage() . '</code>';
    }
?>