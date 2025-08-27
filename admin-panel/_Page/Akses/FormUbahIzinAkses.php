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
            
            //Bersihkan Variabel
            $id_akses=validateAndSanitizeInput($id_akses);
            
            //Buka data askes
            $nama_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'nama_akses');
            $kontak_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'kontak_akses');
            $email_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'email_akses');
            $image_akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'image_akses');
            $akses=GetDetailData($Conn,'akses','id_akses',$id_akses,'akses');
            $datetime_daftar=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_daftar');
            $datetime_update=GetDetailData($Conn,'akses','id_akses',$id_akses,'datetime_update');
            
            //Jumlah
            try {
                // Menghitung Jumlah Aktivitas
                $queryAktivitas = $Conn->prepare("SELECT COUNT(*) as total FROM log WHERE id_akses = :id_akses");
                $queryAktivitas->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
                $queryAktivitas->execute();
                $resultAktivitas = $queryAktivitas->fetch(PDO::FETCH_ASSOC);
                $JumlahAktivitas = (int)$resultAktivitas['total'];

                // Menghitung Jumlah Role
                $queryRole = $Conn->prepare("SELECT COUNT(*) as total FROM akses_ijin WHERE id_akses = :id_akses");
                $queryRole->bindParam(':id_akses', $id_akses, PDO::PARAM_STR);
                $queryRole->execute();
                $resultRole = $queryRole->fetch(PDO::FETCH_ASSOC);
                $JumlahRole = (int)$resultRole['total'];

            } catch (PDOException $e) {
                // Set default value jika terjadi error
                $JumlahAktivitas = 0;
                $JumlahRole = 0;
                
                // Optional: Tampilkan atau log error
                error_log("Error menghitung aktivitas/role: " . $e->getMessage());
                // echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            
            //Format Tanggal
            $strtotime1=strtotime($datetime_daftar);
            $strtotime2=strtotime($datetime_update);
            
            //Menampilkan Tanggal
            $DateDaftar=date('d/m/Y H:i:s T', $strtotime1);
            $DateUpdate=date('d/m/Y H:i:s T', $strtotime2);
            if(!empty($image_akses)){
                $image_akses=$image_akses;
            }else{
                $image_akses="No-Image.png";
            }
?>
    <input type="hidden" name="id_akses" id="id_akses_edit" value="<?php echo "$id_akses"; ?>">
    <div class="row mb-3">
        <div class="col col-md-4">Nama Lengkap</div>
        <div class="col col-md-8">
            <code class="text text-grayish"><?php echo $nama_akses; ?></code>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col col-md-4">Kontak</div>
        <div class="col col-md-8">
            <code class="text text-grayish"><?php echo $kontak_akses; ?></code>
        </div>
    </div>
    <div class="row mb-3 border-1 border-bottom">
        <div class="col col-md-4 mb-3">Email</div>
        <div class="col col-md-8 mb-3">
            <code class="text text-grayish"><?php echo $email_akses; ?></code>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
                try {
                    // Hitung jumlah data fitur
                    $stmtCount = $Conn->prepare("SELECT COUNT(*) FROM akses_fitur");
                    $stmtCount->execute();
                    $jml_data = $stmtCount->fetchColumn();

                    if(empty($jml_data)) {
                        echo '<code class="text-center text-danger">Belum ada data fitur aplikasi, silahkan tambahkan fitur aplikasi terlebih dulu</code>';
                    } else {
                        echo '<div class="row">';
                        $no_kategori = 1;
                        
                        // Query kategori fitur
                        $stmtKategori = $Conn->prepare("SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                        $stmtKategori->execute();
                        
                        while ($DataKategori = $stmtKategori->fetch(PDO::FETCH_ASSOC)) {
                            $kategori = htmlspecialchars($DataKategori['kategori']);
                            echo '<div class="col-md-12 mb-2">';
                            echo '  <small class="credit">'.$no_kategori.'. '.htmlspecialchars($kategori).'</small>';
                            echo '  <ul>';
                            
                            // Query fitur per kategori
                            $stmtFitur = $Conn->prepare("SELECT * FROM akses_fitur WHERE kategori = :kategori ORDER BY nama ASC");
                            $stmtFitur->bindParam(':kategori', $kategori, PDO::PARAM_STR);
                            $stmtFitur->execute();
                            
                            while ($DataFitur = $stmtFitur->fetch(PDO::FETCH_ASSOC)) {
                                $id_akses_fitur = htmlspecialchars($DataFitur['id_akses_fitur']);
                                $NamaFitur = htmlspecialchars($DataFitur['nama']);
                                $kode = htmlspecialchars($DataFitur['kode']);
                                
                                echo '<li>';
                                // Validasi akses
                                $Validasi = IjinAksesSaya($Conn, $id_akses, $kode);
                                
                                if($Validasi == "Ada") {
                                    echo '<input type="checkbox" checked name="rules[]" id="IdFiturEdit'.$id_akses_fitur.'" value="'.$id_akses_fitur.'"> ';
                                    echo '<label for="IdFiturEdit'.$id_akses_fitur.'"><small class="credit"><code class="text text-grayish">'.$NamaFitur.'</code></small></label>';
                                } else {
                                    echo '<input type="checkbox" name="rules[]" id="IdFiturEdit'.$id_akses_fitur.'" value="'.$id_akses_fitur.'"> ';
                                    echo '<label for="IdFiturEdit'.$id_akses_fitur.'"><small class="credit"><code class="text text-grayish">'.$NamaFitur.'</code></small></label>';
                                }
                                echo '</li>';
                            }
                            echo '  </ul>';
                            echo '</div>';
                            $no_kategori++;
                        }
                        echo '</div>';
                    }
                } catch (PDOException $e) {
                    echo '<code class="text-center text-danger">Terjadi kesalahan: '.htmlspecialchars($e->getMessage()).'</code>';
                    error_log("PDO Error: " . $e->getMessage());
                }
            ?>
        </div>
    </div>
<?php 
        } 
    } 
?>