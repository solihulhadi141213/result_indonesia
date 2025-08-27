<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    // Validasi input
    if (empty($_POST['akses'])) {
        echo '<code class="text-danger">Nama Entitas Akses Tidak Boleh Kosong</code>';
        exit;
    }
    if (empty($_POST['keterangan'])) {
        echo '<code class="text-danger">Setidaknya anda harus menulis keterangan mengenai entitias yang anda buat</code>';
        exit;
    }
    if (empty($_POST['rules'])) {
        echo '<code class="text-danger">Fitur Entitas Tidak Boleh Kosong</code>';
        exit;
    }

    // Validasi panjang nama
    $akses = trim($_POST['akses']);
    if (strlen($akses) > 20) {
        echo '<code class="text-danger">Nama Entitas Akses Tidak Boleh Lebih Dari 20 Karakter</code>';
        exit;
    }

    // Bersihkan input
    $akses      = validateAndSanitizeInput($akses);
    $keterangan = validateAndSanitizeInput($_POST['keterangan']);
    $rules      = $_POST['rules'];
    $jumlah_standar_fitur = count($rules);
    if ($jumlah_standar_fitur === 0) {
        echo '<code class="text-danger">Ijin Akses Entitas Tidak Boleh Kosong</code>';
        exit;
    }

    // Cek duplikat akses
    $cekStmt = $Conn->prepare("SELECT COUNT(*) FROM akses_entitas WHERE akses = :akses");
    $cekStmt->execute([':akses' => $akses]);
    if ($cekStmt->fetchColumn() > 0) {
        echo '<code class="text-danger">Data Yang Anda Input Sudah Ada</code>';
        exit;
    }

    // Generate UUID
    $uuid = GenerateToken(32);

    // Simpan entitas
    $insertStmt = $Conn->prepare("
        INSERT INTO akses_entitas (uuid_akses_entitas, akses, keterangan)
        VALUES (:uuid, :akses, :keterangan)
    ");

    if ($insertStmt->execute([
        ':uuid'       => $uuid,
        ':akses'      => $akses,
        ':keterangan' => $keterangan
    ])) {
        // Simpan ke akses_referensi
        $JumlahYangBerhasil = 0;
        $insertRefStmt = $Conn->prepare("
            INSERT INTO akses_referensi (uuid_akses_entitas, id_akses_fitur)
            VALUES (:uuid, :id_akses_fitur)
        ");

        foreach ($rules as $id_akses_fitur) {
            // Pastikan ID-nya numerik
            if (!is_numeric($id_akses_fitur)) continue;

            $result = $insertRefStmt->execute([
                ':uuid'           => $uuid,
                ':id_akses_fitur' => $id_akses_fitur
            ]);

            if ($result) $JumlahYangBerhasil++;
        }

        if ($JumlahYangBerhasil === $jumlah_standar_fitur) {
            // Tambah log
            $kategori_log = "Entitas Akses";
            $deskripsi_log = "Input Entitas Akses";
            $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);
            if ($InputLog === "Success") {
                echo '<small class="text-success" id="NotifikasiTambahAksesEntitiasBerhasil">Success</small>';
            } else {
                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
            }
        } else {
            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data referensi</small>';
        }
    } else {
        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data entitias</small>';
    }
?>
