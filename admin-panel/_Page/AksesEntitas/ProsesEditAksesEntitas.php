<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    // Validasi
    if (empty($_POST['uuid_akses_entitas'])) {
        echo '<code class="text-danger">ID Akses Entitias Tidak Boleh Kosong</code>';
        exit;
    }
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

    $uuid_akses_entitas = validateAndSanitizeInput($_POST['uuid_akses_entitas']);
    $akses              = validateAndSanitizeInput($_POST['akses']);
    $keterangan         = validateAndSanitizeInput($_POST['keterangan']);
    $standar_fitur      = $_POST['rules'];
    $jumlah_standar_fitur = count($standar_fitur);

    if (strlen($akses) > 20) {
        echo '<code class="text-danger">Nama Entitas Akses Tidak Boleh Lebih Dari 20 Karakter</code>';
        exit;
    }

    try {
        $Conn->beginTransaction();

        // Update entitas
        $stmtUpdate = $Conn->prepare("UPDATE akses_entitas SET akses = :akses, keterangan = :keterangan WHERE uuid_akses_entitas = :uuid");
        $stmtUpdate->execute([
            ':akses' => $akses,
            ':keterangan' => $keterangan,
            ':uuid' => $uuid_akses_entitas
        ]);

        // Hapus semua referensi sebelumnya
        $stmtDelete = $Conn->prepare("DELETE FROM akses_referensi WHERE uuid_akses_entitas = :uuid");
        $stmtDelete->execute([':uuid' => $uuid_akses_entitas]);

        // Tambahkan kembali fitur akses
        $stmtInsert = $Conn->prepare("INSERT INTO akses_referensi (uuid_akses_entitas, id_akses_fitur) VALUES (:uuid, :fitur_id)");
        $JumlahYangBerhasil = 0;
        foreach ($standar_fitur as $id_akses_fitur) {
            $stmtInsert->execute([
                ':uuid' => $uuid_akses_entitas,
                ':fitur_id' => $id_akses_fitur
            ]);
            $JumlahYangBerhasil++;
        }

        if ($JumlahYangBerhasil === $jumlah_standar_fitur) {
            $Conn->commit();

            // Logging
            $kategori_log = "Entitas Akses";
            $deskripsi_log = "Edit Entitas Akses";
            $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);

            if ($InputLog == "Success") {
                echo '<small class="text-success" id="NotifikasiEditAksesEntitasBerhasil">Success</small>';
            } else {
                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
            }
        } else {
            $Conn->rollBack();
            echo '<small class="text-danger">Terjadi kesalahan saat menyimpan data referensi</small>';
        }
    } catch (PDOException $e) {
        $Conn->rollBack();
        echo '<small class="text-danger">Terjadi kesalahan: ' . $e->getMessage() . '</small>';
    }
?>
