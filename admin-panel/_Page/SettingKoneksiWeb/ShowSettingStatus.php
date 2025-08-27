<?php
//Zona Waktu
date_default_timezone_set('Asia/Jakarta');

//Koneksi
include "../../_Config/Connection.php";
include "../../_Config/GlobalFunction.php";
include "../../_Config/Session.php";

// Ambil pengaturan koneksi web
try {
    $id = 1;
    $stmt = $Conn->prepare("SELECT * FROM setting_koneksi WHERE id_setting_koneksi = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $DataKoneksiWeb = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($DataKoneksiWeb) {
        $base_url   = $DataKoneksiWeb['base_url'];
        $user_key   = $DataKoneksiWeb['user_key'];
        $access_key = $DataKoneksiWeb['access_key'];
    } else {
        $base_url = $user_key = $access_key = "";
        error_log("No connection settings found with ID 1");
    }
} catch (PDOException $e) {
    error_log("PDO Error: " . $e->getMessage());
    $base_url = $user_key = $access_key = "";
}

$status  = "failed";
$message = "Tidak dapat terhubung ke API.";

// Jalankan cURL hanya jika base_url ada
if (!empty($base_url)) {
    $curl = curl_init();

    $data = [
        "user_key"   => $user_key,
        "access_key" => $access_key
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => rtrim($base_url, "/") . "/_Api/_GetToken.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json"
        ],
        // Tambahan agar bisa jalan di localhost (abaikan SSL verify)
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $message = "cURL Error: " . curl_error($curl);
    } else {
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            $message = "HTTP Error $http_code. Response: " . htmlspecialchars($response);
        } else {
            $result = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($result)) {
                $status  = $result['status'] ?? "failed";
                $message = $result['message'] ?? "Response tidak memiliki pesan.";
            } else {
                $message = "Response bukan JSON valid: " . htmlspecialchars($response);
            }
        }
    }

    curl_close($curl);
}
?>

<!-- Tampilan -->
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2"><small>Base URL</small></div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9"><small class="text text-grayish"><?= $base_url; ?></small></div>
</div>
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2"><small>User Key</small></div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9"><small class="text text-grayish"><?= $user_key; ?></small></div>
</div>
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2"><small>Access Key</small></div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9"><small class="text text-grayish"><?= $access_key; ?></small></div>
</div>
<div class="row mb-2">
    <div class="col-4 col-sm-5 col-md-4 col-lg-2"><small>Status</small></div>
    <div class="col-1"><small>:</small></div>
    <div class="col-7 col-sm-6 col-md-7 col-lg-9">
        <?php if ($status == "success"): ?>
            <small class="text text-success">Connected <i class="bi bi-check-circle"></i></small>
        <?php else: ?>
            <small class="text text-danger"><?= $message; ?> <i class="bi bi-x"></i></small>
        <?php endif; ?>
    </div>
</div>
