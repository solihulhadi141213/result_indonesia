<?php
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    if(empty($_POST['id_akses_fitur'])){
        echo '<code>Data tidak diketahui, mungkin proses akan gagal!</code>';
    }else{
        $id_akses_fitur=$_POST['id_akses_fitur'];
        $NamaFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'nama');
        $KategoriFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kategori');
        $KodeFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'kode');
        $KeteranganFitur=GetDetailData($Conn,'akses_fitur','id_akses_fitur',$id_akses_fitur,'keterangan');
?>
        <input type="hidden" class="form-control" name="id_akses_fitur" value="<?php echo $id_akses_fitur; ?>">
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="nama">Nama Fitur</label>
                <input type="text" class="form-control" name="nama" value="<?php echo $NamaFitur; ?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="kategori">Kategori Fitur</label>
                <input type="text" class="form-control" name="kategori" list="ListKategori2" value="<?php echo $KategoriFitur; ?>">
                <datalist id="ListKategori2">
                    <?php
                        try {
                            $stmt = $Conn->prepare("SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                            $stmt->execute();
                            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $kategori = htmlspecialchars($data['kategori']);
                                echo '<option value="' . $kategori . '">';
                            }
                        } catch (PDOException $e) {
                            echo '<option disabled>Error: ' . $e->getMessage() . '</option>';
                        }
                    ?>
                </datalist>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="kode">Kode Fitur</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="kode" id="kodeEdit" value="<?php echo $KodeFitur; ?>">
                    <button type="button" class="btn btn-dark" title="Generate Kode" id="GenerateKodeEdit">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="form-control"><?php echo $KeteranganFitur; ?></textarea>
            </div>
        </div>
<?php
    }
?>