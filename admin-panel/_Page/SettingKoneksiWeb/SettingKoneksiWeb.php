<?php
    //Cek Aksesibilitas ke halaman ini
    $IjinAksesSaya=IjinAksesSaya($Conn,$SessionIdAkses,'uMaBty0vjhOEmUzZ0rk');
    if($IjinAksesSaya!=="Ada"){
        include "_Page/Error/NoAccess.php";
    }else{

        //Buka Pengaturan Koneksi Web
        try {
            // Query untuk mengambil data setting email gateway
            $id = 1; // Create variable to hold the value
            $stmt = $Conn->prepare("SELECT * FROM setting_koneksi  WHERE id_setting_koneksi  = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            // Fetch data
            $DataKoneksiWeb = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($DataKoneksiWeb) {
                // Assign variabel
                $base_url = $DataKoneksiWeb['base_url'];
                $user_key = $DataKoneksiWeb['user_key'];
                $access_key = $DataKoneksiWeb['access_key'];
            } else {
                // Handle case when no data found
                $base_url = "";
                $user_key = "";
                $access_key = "";
                
                error_log("No connection settings found with ID 1");
            }
        } catch (PDOException $e) {
            // Handle database errors
            error_log("PDO Error: " . $e->getMessage());
            
            // Set default empty values
            $base_url = "";
            $user_key = "";
            $access_key = "";
        }
?>
    <div class="pagetitle">
        <h1>
            <a href="">
                <i class="bi bi-gear"></i> Koneksi Website</a>
            </a>
        </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active"> Koneksi Website</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <small>
                        Berikut ini adalah halaman pengaturan koneksi website. 
                        Pada halaman ini anda bisa mengatur properti koneksi dengan website sehingga dapat di akses dengan baik.
                        Periksa kembali pengaturan yang anda gunakan agar aplikasi dapat berjalan dengan baik.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <b class="card-title">
                            <i class="bi bi-globe"></i> Koneksi Website
                        </b>
                    </div>
                    <div class="card-body" id="ShowSettingStatus">
                        <!-- Konten Setting Akan Ditampilkan Disini -->
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-md btn-outline-success btn-floating" id="ReloadKoneksiWeb" title="Muat Ulang Status Koneksi">
                            <i class="bi bi-repeat"></i>
                        </button>
                        <button type="button" class="btn btn-md btn-primary btn-floating" title="Ubah Koneksi Website" data-bs-toggle="modal" data-bs-target="#ModalSettingKoneksiWeb">
                            <i class="bi bi-gear"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>