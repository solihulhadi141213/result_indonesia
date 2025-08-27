<?php
    //Koneksi
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/SettingGeneral.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    //Harus Login Terlebih Dulu
    if(empty($SessionIdAkses)){
        echo '<div class="row">';
        echo '  <div class="col-md-12 mb-3 text-center">';
        echo '      <code>Sesi Login Sudah Berakhir, Silahkan Login Ulang!</code>';
        echo '  </div>';
        echo '</div>';
    }else{
        //Tangkap id_akses
        if(empty($_POST['id_akses'])){
            echo '<div class="row">';
            echo '  <div class="col-md-12 mb-3 text-center">';
            echo '      <code>ID Akses Tidak Boleh Kosong</code>';
            echo '  </div>';
            echo '</div>';
        }else{
            $id_akses=$_POST['id_akses'];
?>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <td align="center"><b>No</b></td>
                                    <td align="center"><b>Kategori Log</b></td>
                                    <td align="center"><b>Jumlah Aktivitas</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $JumlahAktivitas = 0;
                                     try {
                                        // Query untuk menghitung total aktivitas dengan PDO
                                        $query = $Conn->prepare("SELECT COUNT(*) as total FROM log WHERE id_akses = :id_akses");
                                        $query->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
                                        $query->execute();
                                        
                                        // Ambil hasil
                                        $data = $query->fetch(PDO::FETCH_ASSOC);
                                        $JumlahAktivitas = (int)$data['total'];
                                        
                                    } catch (PDOException $e) {
                                        $JumlahAktivitas = 0;
                                    }
                                    try {
                                        if(empty($JumlahAktivitas)) {
                                            echo '<tr>';
                                            echo '  <td class="text-center text-danger" colspan="3">Akun Akses Ini Belum Memiliki Record Aktivitas</td>';
                                            echo '</tr>';
                                        } else {
                                            $no = 1;
                                            
                                            // Query untuk mendapatkan kategori log unik
                                            $query = $Conn->prepare("SELECT DISTINCT kategori_log FROM log WHERE id_akses = :id_akses ORDER BY id_log DESC");
                                            $query->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
                                            $query->execute();
                                            
                                            while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
                                                $kategori_log = htmlspecialchars($data['kategori_log']);
                                                
                                                // Menghitung jumlah log per kategori
                                                $countQuery = $Conn->prepare("SELECT COUNT(*) as jumlah FROM log WHERE id_akses = :id_akses AND kategori_log = :kategori_log");
                                                $countQuery->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
                                                $countQuery->bindParam(':kategori_log', $kategori_log, PDO::PARAM_STR);
                                                $countQuery->execute();
                                                $countData = $countQuery->fetch(PDO::FETCH_ASSOC);
                                                $JumlahLog = $countData['jumlah'];
                                                
                                                echo '<tr>';
                                                echo '  <td class="text-center">'.htmlspecialchars($no).'</td>';
                                                echo '  <td class="text-left">'.htmlspecialchars($kategori_log).'</td>';
                                                echo '  <td class="text-center">'.htmlspecialchars($JumlahLog).' Record</td>';
                                                echo '</tr>';
                                                $no++;
                                            }
                                        }
                                    } catch (PDOException $e) {
                                        echo '<tr>';
                                        echo '  <td class="text-center text-danger" colspan="3">Terjadi kesalahan: '.htmlspecialchars($e->getMessage()).'</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<?php 
        } 
    } 
?>