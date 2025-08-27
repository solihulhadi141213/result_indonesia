<?php
    session_start();
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";

    // Redirect fungsi biar ga ketik ulang
    function redirectLogin() {
        session_unset();
        session_destroy();
        header("Location: ../../Login.php");
        exit();
    }

    // Jika tidak ada login token
    if (empty($_SESSION["login_token"]) || empty($_SESSION["id_akses"])) {
        redirectLogin();
    } else {
        $SessionIdAkses   = $_SESSION["id_akses"];
        $SessionLoginToken = $_SESSION["login_token"];

        try {
            // Hapus token login dari DB (pakai PDO prepared statement)
            $stmt = $Conn->prepare("DELETE FROM akses_login WHERE id_akses = :id_akses AND token = :token");
            $stmt->bindParam(":id_akses", $SessionIdAkses, PDO::PARAM_INT);
            $stmt->bindParam(":token", $SessionLoginToken, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            // Bisa log error kalau perlu
            error_log("Logout error: " . $e->getMessage());
        }

        // Setelah hapus token, langsung logout
        redirectLogin();
    }
?>
