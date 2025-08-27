<?php
    // Koneksi dan session
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if(empty($SessionIdAkses)) {
        echo '<span class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</span>';
        exit();
    }

    try {
        // Mengambil data dari POST dengan default empty string
        $url_provider = $_POST['url_provider'] ?? '';
        $port_gateway = $_POST['port_gateway'] ?? '';
        $email_gateway = $_POST['email_gateway'] ?? '';
        $password_gateway = $_POST['password_gateway'] ?? '';
        $nama_pengirim = $_POST['nama_pengirim'] ?? '';
        $url_service = $_POST['url_service'] ?? '';

        // Sanitasi input
        $url_provider = validateAndSanitizeInput($url_provider);
        $port_gateway = validateAndSanitizeInput($port_gateway);
        $email_gateway = validateAndSanitizeInput($email_gateway);
        $password_gateway = validateAndSanitizeInput($password_gateway);
        $nama_pengirim = validateAndSanitizeInput($nama_pengirim);
        $url_service = validateAndSanitizeInput($url_service);

        // Mulai transaksi
        $Conn->beginTransaction();

        // Update setting email gateway
        $stmt = $Conn->prepare("UPDATE setting_email_gateway SET 
            email_gateway = :email_gateway,
            password_gateway = :password_gateway,
            url_provider = :url_provider,
            port_gateway = :port_gateway,
            nama_pengirim = :nama_pengirim,
            url_service = :url_service
            WHERE id_setting_email_gateway = 1");

        $stmt->bindParam(':email_gateway', $email_gateway);
        $stmt->bindParam(':password_gateway', $password_gateway);
        $stmt->bindParam(':url_provider', $url_provider);
        $stmt->bindParam(':port_gateway', $port_gateway);
        $stmt->bindParam(':nama_pengirim', $nama_pengirim);
        $stmt->bindParam(':url_service', $url_service);

        if($stmt->execute()) {
            // Input log
            $kategori_log = "Setting";
            $deskripsi_log = "Setting Email";
            $InputLog = addLog($Conn, $SessionIdAkses, $now, $kategori_log, $deskripsi_log);
            
            if($InputLog == "Success") {
                $Conn->commit();
                $_SESSION["NotifikasiSwal"] = "Simpan Setting Email Berhasil";
                echo '<span class="text-success" id="NotifikasiSimpanSettingEmailBerhasil">Success</span>';
            } else {
                $Conn->rollBack();
                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan Log</small>';
            }
        } else {
            $Conn->rollBack();
            echo '<span class="text-danger">Save Email Gateway settings Failed</span>';
        }

    } catch (PDOException $e) {
        if(isset($Conn) && $Conn->inTransaction()) {
            $Conn->rollBack();
        }
        error_log("PDO Error: " . $e->getMessage());
        echo '<span class="text-danger">Terjadi kesalahan database: ' . htmlspecialchars($e->getMessage()) . '</span>';
    } catch (Exception $e) {
        if(isset($Conn) && $Conn->inTransaction()) {
            $Conn->rollBack();
        }
        error_log("Error: " . $e->getMessage());
        echo '<span class="text-danger">Terjadi Kesalahan : ' . htmlspecialchars($e->getMessage()) . '</span>';
    }
?>