<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if(empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang</small>';
        exit();
    }

    // Validasi input
    $requiredFields = [
        'base_url' => 'Base URL Website Boleh Kosong',
        'user_key' => 'User Key tidak boleh kosong!',
        'access_key' => 'Access Key Tidak Boleh Kosong!',
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if(empty($_POST[$field])) {
            echo '<span class="text-danger">'.$errorMessage.'</span>';
            exit();
        }
    }

    // Sanitasi input
    $fields = [
        'base_url', 'user_key', 'access_key'
    ];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = validateAndSanitizeInput($_POST[$field]);
    }

    try {
        $Conn->beginTransaction();

        // Update data utama
        $stmt = $Conn->prepare("UPDATE setting_koneksi SET 
            base_url = :base_url,
            user_key = :user_key,
            access_key = :access_key
            WHERE id_setting_koneksi = 1");

        foreach ($data as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }

        if($stmt->execute()) {
            $Conn->commit();
            echo '<small class="text-success" id="NotifikasiSettingKoneksiWebBerhasil">Success</small>';
        } else {
            $Conn->rollBack();
            echo '<small class="text-danger">Terjadi kesalahan pada saat update data pengaturan</small>';
        }

    } catch (PDOException $e) {
        $Conn->rollBack();
        echo '<small class="text-danger">Terjadi kesalahan database: '.htmlspecialchars($e->getMessage()).'</small>';
    } catch (Exception $e) {
        $Conn->rollBack();
        echo '<span class="text-danger">'.htmlspecialchars($e->getMessage()).'</span>';
    }
?>