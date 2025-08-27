<?php
    // Koneksi
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    include "../../_Config/SettingKoneksi.php";
    date_default_timezone_set('Asia/Jakarta');
    $now = date('Y-m-d H:i:s');

    // Validasi session
    if(empty($SessionIdAkses)) {
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Sesi Akses Sudah Berakhir. Silahkan login ulang!
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Validasi type_konten
    if(empty($_POST['type_konten'])){
        echo '
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <small>
                            Tipe Konten Belum Dipilih
                        </small>
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    //Buat Variabel
    $type_konten=validateAndSanitizeInput($_POST['type_konten']);

    //Tampilkan Form
    if($type_konten=="html"){
        echo '
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="isi_content">Isi Konten</label>
                    <textarea name="isi_content" id="isi_content" class="form-control"></textarea>
                </div>
            </div>
        ';
    }else{
        echo '
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="position">Posisi</label>
                    <select name="position" id="position" class="form-control">
                        <option value="">Pilih</option>
                        <option value="center">Center</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                    </select>
                </div>
            </div>
        ';
        echo '
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="width">Lebar</label>
                    <input type="number" name="width" id="width" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="unit">Unit</label>
                    <select name="unit" id="unit" class="form-control">
                        <option value="">Pilih</option>
                        <option value="%">Persen</option>
                        <option value="px">Pixel</option>
                    </select>
                </div>
            </div>
        ';
        echo '
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="caption">Caption</label>
                    <input type="text" name="caption" id="caption" class="form-control">
                </div>
            </div>
        ';
        echo '
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="content">File Image</label>
                    <input type="file" name="content" id="content" class="form-control">
                </div>
            </div>
        ';
    }
    

?>