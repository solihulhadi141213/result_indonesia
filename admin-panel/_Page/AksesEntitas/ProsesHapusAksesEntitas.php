<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");
    $now = date('Y-m-d H:i:s');

    // Validasi input
    if (empty($_POST['uuid_akses_entitas'])) {
        echo '<code class="text-danger">ID Akses Entitias Tidak Boleh Kosong</code>';
        exit;
    }

    $uuid_akses_entitas = validateAndSanitizeInput($_POST['uuid_akses_entitas']);

    try {
        // Mulai transaksi
        $Conn->beginTransaction();

        // Hapus dari akses_entitas
        $stmtEntitas = $Conn->prepare("DELETE FROM akses_entitas WHERE uuid_akses_entitas = :uuid");
        $stmtEntitas->execute([':uuid' => $uuid_akses_entitas]);

        // Hapus dari akses_referensi
        $stmtReferensi = $Conn->prepare("DELETE FROM akses_referensi WHERE uuid_akses_entitas = :uuid");
        $stmtReferensi->execute([':uuid' => $uuid_akses_entitas]);

        // Commit transaksi
        $Conn->commit();

        // Simpan log
        $kategori_log = "Entitas Akses";
        $deskripsi_log = "Hapus Entitas Akses";
        $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);

        if ($InputLog === "Success") {
            echo '<small class="text-success" id="NotifikasiHapusAksesEntitasBerhasil">Success</small>';
        } else {
            echo '<small class="text-danger">Data terhapus, tapi gagal menyimpan log</small>';
        }

    } catch (PDOException $e) {
        // Rollback jika error
        $Conn->rollBack();
        echo '<small class="text-danger">Terjadi kesalahan: ' . $e->getMessage() . '</small>';
    }
?>
