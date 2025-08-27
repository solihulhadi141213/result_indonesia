<?php
    try {
        // Query untuk mengambil data setting email gateway
        $id = 1; // Create variable to hold the value
        $stmt = $Conn->prepare("SELECT * FROM setting_email_gateway WHERE id_setting_email_gateway = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // Fetch data
        $DataPaymentsetting = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($DataPaymentsetting) {
            // Assign variabel
            $email_gateway = $DataPaymentsetting['email_gateway'];
            $password_gateway = $DataPaymentsetting['password_gateway'];
            $url_provider = $DataPaymentsetting['url_provider'];
            $port_gateway = $DataPaymentsetting['port_gateway'];
            $nama_pengirim = $DataPaymentsetting['nama_pengirim'];
            $url_service = $DataPaymentsetting['url_service'];
        } else {
            // Handle case when no data found
            $email_gateway = '';
            $password_gateway = '';
            $url_provider = '';
            $port_gateway = '';
            $nama_pengirim = '';
            $url_service = '';
            
            error_log("No email gateway settings found with ID 1");
        }
    } catch (PDOException $e) {
        // Handle database errors
        error_log("PDO Error: " . $e->getMessage());
        
        // Set default empty values
        $email_gateway = '';
        $password_gateway = '';
        $url_provider = '';
        $port_gateway = '';
        $nama_pengirim = '';
        $url_service = '';
    }
?>