<?php
    //Zona Waktu
    date_default_timezone_set('Asia/Jakarta');

    //Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Buka Pengaturan Koneksi Web
        try {
        // Query untuk mengambil data setting email gateway
        $id = 1; // Create variable to hold the value
        $stmt = $Conn->prepare("SELECT * FROM setting_koneksi  WHERE id_setting_koneksi  = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch data
        $DataKoneksiWeb = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($DataKoneksiWeb) {
            // Assign variabel
            $base_url = $DataKoneksiWeb['base_url'];
            $user_key = $DataKoneksiWeb['user_key'];
            $access_key = $DataKoneksiWeb['access_key'];
        } else {
            // Handle case when no data found
            $base_url = "";
            $user_key = "";
            $access_key = "";
            
            error_log("No connection settings found with ID 1");
        }
    } catch (PDOException $e) {
        // Handle database errors
        error_log("PDO Error: " . $e->getMessage());
        
        // Set default empty values
        $base_url = "";
        $user_key = "";
        $access_key = "";
    }
?>
<div class="row mb-2">
    <div class="col-5">
        <label for="base_url">
            <small>Base URL</small>
        </label>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-6">
        <input type="text" class="form-control" name="base_url" id="base_url" value="<?php echo "$base_url"; ?>">
    </div>
</div>
<div class="row mb-2">
    <div class="col-5">
        <label for="user_key">
            <small>User Key</small>
        </label>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-6">
        <input type="text" class="form-control" name="user_key" id="user_key" value="<?php echo "$user_key"; ?>">
    </div>
</div>
<div class="row mb-2">
    <div class="col-5">
        <label for="access_key">
            <small>Access Key</small>
        </label>
    </div>
    <div class="col-1"><small>:</small></div>
    <div class="col-6">
        <input type="text" class="form-control" name="access_key" id="access_key" value="<?php echo "$access_key"; ?>">
    </div>
</div>