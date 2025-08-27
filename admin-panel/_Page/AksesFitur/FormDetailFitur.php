<?php
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php"; // Berisi class Database dengan PDO
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Validasi Akses
    if (empty($SessionIdAkses)) {
        echo '
            <div class="alert alert-danger">
                <small>Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small>
            </div>
        ';
        exit;
    }

    if (empty($_POST['id_akses_fitur'])) {
        echo '
            <div class="alert alert-danger">
                <small>ID Fitur Tidak Boleh Kosong!</small>
            </div>
        ';
        exit;
    }

    $id_akses_fitur = validateAndSanitizeInput($_POST['id_akses_fitur']);

    try {
        // Ambil data akses fitur
        $Qry = $Conn->prepare("SELECT * FROM akses_fitur WHERE id_akses_fitur = :id_akses_fitur");
        $Qry->bindParam(':id_akses_fitur', $id_akses_fitur, PDO::PARAM_INT);
        $Qry->execute();
        $Data = $Qry->fetch(PDO::FETCH_ASSOC);

        if (!$Data) {
            echo '
                <div class="alert alert-warning">
                    <small>Data fitur tidak ditemukan.</small>
                </div>
            ';
            exit;
        }

        // Variabel data fitur
        $nama       = $Data['nama'];
        $kategori   = $Data['kategori'];
        $kode       = $Data['kode'];
        $keterangan = $Data['keterangan'];

        // Hitung jumlah pengguna dengan fitur ini
        $Cek = $Conn->prepare("SELECT COUNT(*) FROM akses_ijin WHERE id_akses_fitur = :id_akses_fitur");
        $Cek->bindParam(':id_akses_fitur', $id_akses_fitur, PDO::PARAM_INT);
        $Cek->execute();
        $JumlahPengguna = $Cek->fetchColumn();

        if (empty($JumlahPengguna)) {
            $label_jumlah_pengguna = '<span class="badge badge-danger">NULL</span>';
        } else {
            $label_jumlah_pengguna = '<span class="badge badge-success">'.$JumlahPengguna.' Orang</span>';
        }

        // Tampilkan Data
        echo '
            <div class="row mb-2">
                <div class="col-4"><small>Nama Fitur</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-muted">'.htmlspecialchars($nama).'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kategori</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-muted">'.htmlspecialchars($kategori).'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Kode Fitur</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-muted">'.htmlspecialchars($kode).'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Keterangan</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-muted">'.htmlspecialchars($keterangan).'</small></div>
            </div>
            <div class="row mb-2">
                <div class="col-4"><small>Jumlah Akses/User</small></div>
                <div class="col-1"><small>:</small></div>
                <div class="col-7"><small class="text text-muted">'.$label_jumlah_pengguna.'</small></div>
            </div>
        ';
    } catch (PDOException $e) {
        echo '
            <div class="alert alert-danger">
                <small>Terjadi kesalahan saat mengambil data:<br>Keterangan: '.$e->getMessage().'</small>
            </div>
        ';
    }
?>
