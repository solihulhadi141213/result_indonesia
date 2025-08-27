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
        <div class="row mb-3">
            <div class="col col-md-4">Email</div>
            <div class="col col-md-8">
                <code class="text text-grayish"><?php echo $email_akses; ?></code>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col col-md-4">
                <label for="image_akses_edit">Upload Foto</label>
            </div>
            <div class="col col-md-8">
                <input type="file" name="image_akses" id="image_akses_edit" class="form-control">
                <small class="credit">
                    <code class="text text-grayish">
                        Maximum File 2 Mb (PNG, JPG, JPEG, GIF)
                    </code>
                </small>
            </div>
        </div>
<?php 
        } 
    } 
?>