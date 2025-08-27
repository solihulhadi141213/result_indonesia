<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    // Time Zone
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi Input
    if(empty($_POST['id_akses'])) {
        echo '<code class="text-danger">ID Akses Tidak Boleh Kosong!</code>';
        exit();
    }

    if(empty($_POST['rules'])) {
        echo '<code class="text-danger">Ijin Akses Tidak Boleh Kosong! Setidaknya yang bersangkutan memiliki 1 fitur yang bisa diakses.</code>';
        exit();
    }

    try {
        // Sanitasi Input
        $id_akses = validateAndSanitizeInput($_POST['id_akses']);
        $rules = $_POST['rules'];
        $JumlahFitur = count($rules);

        if(empty($JumlahFitur)) {
            echo '<code class="text-danger">Ijin Akses Tidak Boleh Kosong! Setidaknya yang bersangkutan memiliki 1 fitur yang bisa diakses.</code>';
            exit();
        }

        // Mulai transaksi
        $Conn->beginTransaction();

        // Hapus Akses lama
        $stmtDelete = $Conn->prepare("DELETE FROM akses_ijin WHERE id_akses = :id_akses");
        $stmtDelete->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
        if(!$stmtDelete->execute()) {
            throw new Exception("Gagal menghapus ijin akses lama");
        }

        // Melakukan Looping Berdasarkan Rules Yang Dipilih
        $JumlahRoleValid = 0;
        $stmtInsert = $Conn->prepare("INSERT INTO akses_ijin (
            id_akses,
            id_akses_fitur,
            kode,
            nama,
            kategori
        ) VALUES (
            :id_akses,
            :id_akses_fitur,
            :kode,
            :nama,
            :kategori
        )");

        foreach($rules as $id_akses_fitur) {
            $id_akses_fitur = validateAndSanitizeInput($id_akses_fitur);
            $KodeFitur = GetDetailData($Conn, 'akses_fitur', 'id_akses_fitur', $id_akses_fitur, 'kode');
            $NamaFitur = GetDetailData($Conn, 'akses_fitur', 'id_akses_fitur', $id_akses_fitur, 'nama');
            $KategoriFitur = GetDetailData($Conn, 'akses_fitur', 'id_akses_fitur', $id_akses_fitur, 'kategori');

            if(!empty($KodeFitur)) {
                $stmtInsert->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
                $stmtInsert->bindParam(':id_akses_fitur', $id_akses_fitur, PDO::PARAM_STR);
                $stmtInsert->bindParam(':kode', $KodeFitur, PDO::PARAM_STR);
                $stmtInsert->bindParam(':nama', $NamaFitur, PDO::PARAM_STR);
                $stmtInsert->bindParam(':kategori', $KategoriFitur, PDO::PARAM_STR);

                if($stmtInsert->execute()) {
                    $JumlahRoleValid++;
                }
            }
        }

        // Verifikasi hasil
        if($JumlahRoleValid == $JumlahFitur) {
            $Conn->commit();
            echo '<small class="text-success" id="NotifikasiUbahIzinAksesBerhasil">Success</small>';
        } else {
            $Conn->rollBack();
            echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan ijin akses ('.($JumlahFitur-$JumlahRoleValid).' dari '.$JumlahFitur.' gagal)</small>';
        }

    } catch (PDOException $e) {
        if(isset($Conn) && $Conn->inTransaction()) {
            $Conn->rollBack();
        }
        echo '<small class="text-danger">Terjadi kesalahan database: '.htmlspecialchars($e->getMessage()).'</small>';
    } catch (Exception $e) {
        if(isset($Conn) && $Conn->inTransaction()) {
            $Conn->rollBack();
        }
        echo '<small class="text-danger">'.htmlspecialchars($e->getMessage()).'</small>';
    }
?>