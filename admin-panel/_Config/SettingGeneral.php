<?php

    // Gunakan prepared statement PDO
    $sql = "SELECT * FROM setting_general WHERE id_setting_general = :id";
    $stmt = $Conn->prepare($sql);

    // Bind parameter (gunakan bindValue atau bindParam)
    $id = 1;
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Eksekusi statement
    $stmt->execute();

    // Ambil hasil query
    $DataSettingGeneral = $stmt->fetch(PDO::FETCH_ASSOC);

    // Simpan hasil ke variabel
    $id_setting_general = $DataSettingGeneral['id_setting_general'] ?? null;
    $title_page = $DataSettingGeneral['title_page'] ?? null;
    $kata_kunci = $DataSettingGeneral['kata_kunci'] ?? null;
    $deskripsi = $DataSettingGeneral['deskripsi'] ?? null;
    $alamat_bisnis = $DataSettingGeneral['alamat_bisnis'] ?? null;
    $email_bisnis = $DataSettingGeneral['email_bisnis'] ?? null;
    $telepon_bisnis = $DataSettingGeneral['telepon_bisnis'] ?? null;
    $favicon = $DataSettingGeneral['favicon'] ?? null;
    $logo = $DataSettingGeneral['logo'] ?? null;
    $base_url = $DataSettingGeneral['base_url'] ?? null;
    $AuthorAplikasi = $DataSettingGeneral['author'] ?? null;

    // Tidak perlu close() pada PDO, tapi jika mau aman:
    $stmt = null;
?>
