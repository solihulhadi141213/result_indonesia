<?php
    // Koneksi dan konfigurasi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    //Keterangan waktu dan zona waktu
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    if (empty($SessionIdAkses)) {
        echo '<small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>';
    } else {
        if (empty($_POST['nama_akses'])) {
            echo '<small class="text-danger">Nama tidak boleh kosong</small>';
        } elseif (empty($_POST['kontak_akses'])) {
            echo '<small class="text-danger">Kontak tidak boleh kosong</small>';
        } else {
            $kontak_akses = $_POST['kontak_akses'];
            $JumlahKarakterKontak = strlen($kontak_akses);

            if ($JumlahKarakterKontak > 20 || $JumlahKarakterKontak < 6 || !preg_match("/^[0-9]*$/", $kontak_akses)) {
                echo '<small class="text-danger">Kontak hanya boleh terdiri dari 6-20 karakter numerik</small>';
            } else {
                $id_akses = validateAndSanitizeInput($SessionIdAkses);
                $kontak_akses = validateAndSanitizeInput($kontak_akses);

                // Ambil kontak lama
                $kontak_akses_lama = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'kontak_akses');

                // Cek duplikasi kontak
                $ValidasiKontakDuplikat = 0;
                if ($kontak_akses !== $kontak_akses_lama) {
                    $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE kontak_akses = :kontak");
                    $stmt->bindValue(':kontak', $kontak_akses, PDO::PARAM_STR);
                    $stmt->execute();
                    $ValidasiKontakDuplikat = $stmt->fetchColumn();
                }

                if (!empty($ValidasiKontakDuplikat)) {
                    echo '<small class="text-danger">Nomor kontak sudah terdaftar</small>';
                } elseif (empty($_POST['email_akses'])) {
                    echo '<small class="text-danger">Email tidak boleh kosong</small>';
                } else {
                    $email_akses = validateAndSanitizeInput($_POST['email_akses']);
                    $email_akses_lama = GetDetailData($Conn, 'akses', 'id_akses', $id_akses, 'email_akses');

                    // Cek duplikasi email
                    $ValidasiEmailDuplikat = 0;
                    if ($email_akses !== $email_akses_lama) {
                        $stmt = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE email_akses = :email");
                        $stmt->bindValue(':email', $email_akses, PDO::PARAM_STR);
                        $stmt->execute();
                        $ValidasiEmailDuplikat = $stmt->fetchColumn();
                    }

                    if (!empty($ValidasiEmailDuplikat)) {
                        echo '<small class="text-danger">Email yang anda gunakan sudah terdaftar</small>';
                    } else {
                        // Siapkan dan sanitasi semua data
                        $nama_akses = validateAndSanitizeInput($_POST['nama_akses']);

                        try {
                            $stmt = $Conn->prepare("UPDATE akses SET 
                                nama_akses = :nama_akses,
                                kontak_akses = :kontak_akses,
                                email_akses = :email_akses,
                                datetime_update = :datetime_update
                                WHERE id_akses = :id_akses");

                            $stmt->bindValue(':nama_akses', $nama_akses, PDO::PARAM_STR);
                            $stmt->bindValue(':kontak_akses', $kontak_akses, PDO::PARAM_STR);
                            $stmt->bindValue(':email_akses', $email_akses, PDO::PARAM_STR);
                            $stmt->bindValue(':datetime_update', $now, PDO::PARAM_STR);
                            $stmt->bindValue(':id_akses', $id_akses, PDO::PARAM_INT);

                            if ($stmt->execute()) {
                                $_SESSION["NotifikasiSwal"] = "Edit Akses Berhasil";
                                echo '<small class="text-success" id="NotifikasiUbahIdentitasProfilBerhasil">Success</small>';
                            } else {
                                echo '<small class="text-danger">Terjadi kesalahan pada saat menyimpan data</small>';
                            }
                        } catch (PDOException $e) {
                            echo '<small class="text-danger">Error: ' . $e->getMessage() . '</small>';
                        }
                    }
                }
            }
        }
    }
?>
