<?php
    require_once '../../_Config/Connection.php';
    date_default_timezone_set('Asia/Jakarta');

    $db = new Database();
    $Conn = $db->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama  = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $datetime = date("Y-m-d H:i:s");

        if (empty($nama) || empty($email)) {
            echo "Nama dan Email wajib diisi!";
            exit;
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Format email tidak valid!";
            exit;
        }

        try {
            // Cek apakah email sudah terdaftar
            $sql_check = "SELECT COUNT(*) FROM newslater WHERE email = :email";
            $stmt_check = $Conn->prepare($sql_check);
            $stmt_check->bindParam(':email', $email);
            $stmt_check->execute();
            $exists = $stmt_check->fetchColumn();

            if ($exists > 0) {
                echo "Email sudah terdaftar!";
                exit;
            }

            // Insert data baru
            $sql_insert = "INSERT INTO newslater (datetime, nama, email) 
                        VALUES (:datetime, :nama, :email)";
            $stmt = $Conn->prepare($sql_insert);
            $stmt->bindParam(':datetime', $datetime);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Gagal menyimpan data!";
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request!";
    }
?>
