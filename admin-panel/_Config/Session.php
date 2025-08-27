<?php
    // Menangkap session kemudian menampilkannya
    session_start();
    date_default_timezone_set('Asia/Jakarta');

    // Fungsi sanitasi
    function validateAndSanitizeInput2($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Fungsi menghitung waktu kedaluwarsa baru (milidetik dari sekarang)
    function calculateExpirationTimeFromDateTime2($datetime, $milisecond) {
        $newTime = strtotime($datetime) + ($milisecond / 1000);
        return date('Y-m-d H:i:s', $newTime);
    }

    // Inisialisasi variabel session
    $SessionIdAkses = "";
    $SessionLoginToken = "";

    if (!empty($_SESSION["id_akses"]) && !empty($_SESSION["login_token"])) {
        // Tangkap dan sanitasi session
        $SessionIdAkses = validateAndSanitizeInput2($_SESSION["id_akses"]);
        $SessionLoginToken = validateAndSanitizeInput2($_SESSION["login_token"]);

        try {
            // Validasi token akses dengan PDO
            $stmt = $Conn->prepare("SELECT * FROM akses_login WHERE id_akses = :id_akses AND token = :token");
            $stmt->bindValue(':id_akses', $SessionIdAkses, PDO::PARAM_INT);
            $stmt->bindValue(':token', $SessionLoginToken, PDO::PARAM_STR);
            $stmt->execute();
            $DataAksesLogin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($DataAksesLogin['id_akses'])) {
                $SessionDateExpired = $DataAksesLogin['date_expired'];
                $DateSekarang = date('Y-m-d H:i:s');

                // Perpanjang jika token masih valid
                if ($SessionDateExpired >= $DateSekarang) {
                    $SessionIdAkses = $DataAksesLogin['id_akses'];
                    $expired_milisecond = 1000 * 60 * 60; // 1 jam
                    $now = date('Y-m-d H:i:s');
                    $date_expired_new = calculateExpirationTimeFromDateTime2($now, $expired_milisecond);

                    // Update waktu kedaluwarsa token
                    $updateStmt = $Conn->prepare("UPDATE akses_login SET date_expired = :date_expired WHERE id_akses = :id_akses AND token = :token");
                    $updateStmt->bindValue(':date_expired', $date_expired_new, PDO::PARAM_STR);
                    $updateStmt->bindValue(':id_akses', $SessionIdAkses, PDO::PARAM_INT);
                    $updateStmt->bindValue(':token', $SessionLoginToken, PDO::PARAM_STR);
                    $updateStmt->execute();

                    // Set ulang variabel session
                    $SessionLoginToken = $DataAksesLogin['token'];
                }
            }
        } catch (PDOException $e) {
            echo "Terjadi kesalahan koneksi: " . $e->getMessage();
        }
    }
?>
