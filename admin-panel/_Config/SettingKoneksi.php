<?php
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