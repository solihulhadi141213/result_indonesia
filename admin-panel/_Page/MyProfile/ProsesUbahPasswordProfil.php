<?php
    // Konfigurasi awal
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Validasi sesi login
    if (empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
        exit;
    }

    $id_akses = validateAndSanitizeInput($SessionIdAkses);

    // Ambil data akses saat ini (optional, bisa dihapus jika tidak dipakai)
    try {
        $stmt = $Conn->prepare("SELECT kontak_akses, email_akses FROM akses WHERE id_akses = :id_akses");
        $stmt->bindValue(':id_akses', $id_akses, PDO::PARAM_INT);
        $stmt->execute();
        $DataDetailAkses = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<small class="text-danger">Gagal mengambil data akses: ' . $e->getMessage() . '</small>';
        exit;
    }

    // Validasi form input
    if (empty($_POST['password1'])) {
        echo '<small class="text-danger">Password tidak boleh kosong</small>';
    } elseif ($_POST['password1'] !== $_POST['password2']) {
        echo '<small class="text-danger">Password tidak sama</small>';
    } else {
        $password1 = $_POST['password1'];
        $JumlahKarakterPassword = strlen($password1);

        // Validasi panjang dan karakter
        if (
            $JumlahKarakterPassword < 6 ||
            $JumlahKarakterPassword > 20 ||
            !preg_match("/^[a-zA-Z0-9]*$/", $password1)
        ) {
            echo '<small class="text-danger">Password hanya boleh terdiri dari 6-20 karakter huruf dan angka</small>';
        } else {
            $password1 = validateAndSanitizeInput($password1);

            // Enkripsi password (bisa upgrade ke password_hash())
            $passwordHash = md5($password1); // ⚠️ Gantilah ke password_hash() jika memungkinkan

            // Update password
            try {
                $stmt = $Conn->prepare("UPDATE akses SET password = :password WHERE id_akses = :id_akses");
                $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
                $stmt->bindValue(':id_akses', $id_akses, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $_SESSION["NotifikasiSwal"] = "Ubah Password Berhasil";
                    echo '<small class="text-success" id="NotifikasiUbahPasswordProfilBerhasil">Success</small>';
                } else {
                    echo '<small class="text-danger">Terjadi kesalahan saat menyimpan data</small>';
                }
            } catch (PDOException $e) {
                echo '<small class="text-danger">Error: ' . $e->getMessage() . '</small>';
            }
        }
    }
?>
