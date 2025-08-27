<?php
    session_start();
    include "../../_Config/Connection.php";
    date_default_timezone_set('Asia/Jakarta');

    $date_creat = date('Y-m-d H:i:s');
    $expired_seconds = 60 * 60; // 1 jam
    $date_expired = date('Y-m-d H:i:s', strtotime($date_creat) + $expired_seconds);

    // Fungsi untuk membuat token
    function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }

    // Fungsi validasi dan sanitasi input
    function validateAndSanitizeInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Validasi input email dan password
    if (empty($_POST["email"])) {
        echo '<code>Email tidak boleh kosong</code>';
    } elseif (empty($_POST["password"])) {
        echo '<code>Password tidak boleh kosong</code>';
    } else {
        $email = validateAndSanitizeInput($_POST["email"]);
        $password = validateAndSanitizeInput($_POST["password"]);
        $passwordMd5 = md5($password); // Disarankan nanti diganti ke password_hash()

        try {
            // Cek apakah email & password cocok
            $stmt = $Conn->prepare("SELECT * FROM akses WHERE email_akses = :email AND password = :password");
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $passwordMd5, PDO::PARAM_STR);
            $stmt->execute();

            $DataAkses = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($DataAkses) {
                $id_akses = $DataAkses["id_akses"];
                $mode_akses = "Pengurus";
                $token = generateToken();

                // Hapus token login lama
                $deleteTokenStmt = $Conn->prepare("DELETE FROM akses_login WHERE id_akses = :id_akses");
                $deleteTokenStmt->bindValue(':id_akses', $id_akses, PDO::PARAM_INT);
                $deleteTokenStmt->execute();

                // Buat token login baru
                $insertTokenStmt = $Conn->prepare("INSERT INTO akses_login (id_akses, kategori, token, date_creat, date_expired) VALUES (:id_akses, :kategori, :token, :date_creat, :date_expired)");
                $insertTokenStmt->bindValue(':id_akses', $id_akses, PDO::PARAM_INT);
                $insertTokenStmt->bindValue(':kategori', $mode_akses, PDO::PARAM_STR);
                $insertTokenStmt->bindValue(':token', $token, PDO::PARAM_STR);
                $insertTokenStmt->bindValue(':date_creat', $date_creat, PDO::PARAM_STR);
                $insertTokenStmt->bindValue(':date_expired', $date_expired, PDO::PARAM_STR);

                $InputAksesLogin = $insertTokenStmt->execute();

                if ($InputAksesLogin) {
                    echo '<span id="NotifikasiProsesLoginBerhasil">Success</span>';
                    $_SESSION["id_akses"] = $id_akses;
                    $_SESSION["login_token"] = $token;
                    $_SESSION["NotifikasiSwal"] = "Login Berhasil";
                } else {
                    echo '<code>Terjadi kesalahan pada saat membuat sesi login</code>';
                }
            } else {
                echo '<code>Kombinasi password dan email yang Anda gunakan tidak valid</code>';
            }
        } catch (PDOException $e) {
            echo '<code>Kesalahan server: ' . $e->getMessage() . '</code>';
        }
    }
?>
