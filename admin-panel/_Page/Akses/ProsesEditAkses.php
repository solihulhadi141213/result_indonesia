<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

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

    if(empty($_POST['nama_akses'])) {
        echo '<small class="text-danger">Nama tidak boleh kosong</small>';
        exit();
    }

    if(empty($_POST['kontak_akses'])) {
        echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
        exit();
    }

    // Validasi Format Kontak
    $JumlahKarakterKontak = strlen($_POST['kontak_akses']);
    if($JumlahKarakterKontak > 20 || $JumlahKarakterKontak < 6 || !preg_match("/^[0-9]*$/", $_POST['kontak_akses'])) {
        echo '<small class="text-danger">Kontak hanya boleh terdiri dari 6-20 karakter numerik</small>';
        exit();
    }

    // Sanitasi Input
    $id_akses = validateAndSanitizeInput($_POST['id_akses']);
    $nama_akses = validateAndSanitizeInput($_POST['nama_akses']);
    $kontak_akses = validateAndSanitizeInput($_POST['kontak_akses']);
    $email_akses = validateAndSanitizeInput($_POST['email_akses'] ?? '');

    try {
        // Validasi Email
        if(empty($email_akses)) {
            echo '<small class="text-danger">Email tidak boleh kosong</small>';
            exit();
        }

        // Validasi Duplikat Kontak
        $kontak_akses_lama = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'kontak_akses');
        if($kontak_akses_lama != $kontak_akses) {
            $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE kontak_akses = ?");
            $stmt->execute([$kontak_akses]);
            if($stmt->fetchColumn() > 0) {
                echo '<small class="text-danger">Nomor kontak sudah terdaftar</small>';
                exit();
            }
        }

        // Validasi Duplikat Email
        $email_akses_lama = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'email_akses');
        if($email_akses_lama != $email_akses) {
            $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE email_akses = ?");
            $stmt->execute([$email_akses]);
            if($stmt->fetchColumn() > 0) {
                echo '<small class="text-danger">Email yang anda gunakan sudah terdaftar</small>';
                exit();
            }
        }

        // Update Data
        $stmt = $Conn->prepare("UPDATE akses SET 
            nama_akses = :nama_akses,
            kontak_akses = :kontak_akses,
            email_akses = :email_akses,
            datetime_update = :datetime_update
            WHERE id_akses = :id_akses");

        $stmt->execute([
            ':nama_akses' => $nama_akses,
            ':kontak_akses' => $kontak_akses,
            ':email_akses' => $email_akses,
            ':datetime_update' => $now,
            ':id_akses' => $id_akses
        ]);

        if($stmt->rowCount() > 0) {
            echo '<small class="text-success" id="NotifikasiEditAksesBerhasil">Success</small>';
        } else {
            echo '<small class="text-danger">Tidak ada perubahan data atau terjadi kesalahan</small>';
        }

    } catch (PDOException $e) {
        echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data: ' . htmlspecialchars($e->getMessage()) . '</small>';
    }
?>