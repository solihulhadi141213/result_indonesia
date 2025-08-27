<?php
    // Koneksi dan setup
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Validasi Sesi
    if(empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
        exit();
    }

    // Validasi Input
    if(empty($_POST['id_akses'])) {
        echo '<small class="text-danger">ID Akses tidak boleh kosong</small>';
        exit();
    }

    // Sanitasi Input
    $id_akses = validateAndSanitizeInput($_POST['id_akses']);

    // Validasi Password
    if(empty($_POST['password1'])) {
        echo '<small class="text-danger">Password tidak boleh kosong</small>';
        exit();
    }

    if($_POST['password1'] !== $_POST['password2']) {
        echo '<small class="text-danger">Password tidak sama</small>';
        exit();
    }

    // Validasi Kompleksitas Password
    $password = $_POST['password1'];
    $JumlahKarakterPassword = strlen($password);
    
    if($JumlahKarakterPassword > 20 || $JumlahKarakterPassword < 6 || !preg_match("/^[a-zA-Z0-9]*$/", $password)) {
        echo '<small class="text-danger">Password hanya boleh terdiri dari 6-20 karakter alfanumerik</small>';
        exit();
    }

    try {
        // Mulai transaksi
        $Conn->beginTransaction();

        // Hash password dengan MD5 (catatan: MD5 sudah tidak aman, pertimbangkan untuk menggunakan password_hash())
        $password_hashed = md5(validateAndSanitizeInput($password));

        // Update password dengan PDO prepared statement
        $stmt = $Conn->prepare("UPDATE akses SET password = :password WHERE id_akses = :id_akses");
        $stmt->bindParam(':password', $password_hashed);
        $stmt->bindParam(':id_akses', $id_akses);
        $stmt->execute();

        // Verifikasi update berhasil
        if($stmt->rowCount() > 0) {
            $Conn->commit();
            echo '<small class="text-success" id="NotifikasiUbahPasswordBerhasil">Success</small>';
        } else {
            $Conn->rollBack();
            echo '<small class="text-danger">Tidak ada perubahan data atau ID tidak ditemukan</small>';
        }

    } catch (PDOException $e) {
        $Conn->rollBack();
        echo '<small class="text-danger">Terjadi kesalahan database: ' . htmlspecialchars($e->getMessage()) . '</small>';
    }
?>