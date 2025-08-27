    <?php
    date_default_timezone_set('Asia/Jakarta');
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";

    if (empty($_POST['uuid_akses_entitas'])) {
        echo '<code>ID Entitias Tidak Boleh Kosong!</code>';
        exit;
    }

    $uuid_akses_entitas = validateAndSanitizeInput($_POST['uuid_akses_entitas']);

    // Validasi UUID entitas
    $checkStmt = $Conn->prepare("SELECT * FROM akses_entitas WHERE uuid_akses_entitas = :uuid");
    $checkStmt->execute([':uuid' => $uuid_akses_entitas]);
    $DataEntitas = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$DataEntitas) {
        echo '<code>ID Entitias Tidak Valid, Atau Tidak Ditemukan Pada Database!</code>';
        exit;
    }

    $NamaAkses = $DataEntitas['akses'];
    $KeteranganEntitias = $DataEntitas['keterangan'];
    ?>

    <div class="row mb-3">
        <div class="col col-md-4">Nama Entitias</div>
        <div class="col col-md-8">
            <small class="credit">
                <code class="text text-grayish"><?php echo htmlspecialchars($NamaAkses); ?></code>
            </small>
        </div>
    </div>
    <div class="row mb-3 border-1 border-bottom">
        <div class="col col-md-4 mb-3">Keterangan</div>
        <div class="col col-md-8 mb-3">
            <small class="credit">
                <code class="text text-grayish"><?php echo htmlspecialchars($KeteranganEntitias); ?></code>
            </small>
        </div>
    </div>

    <div class="row mb-3">
    <?php
    $no = 1;

    // Ambil semua kategori fitur
    $StmtKategori = $Conn->query("SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
    $KategoriList = $StmtKategori->fetchAll(PDO::FETCH_COLUMN);

    foreach ($KategoriList as $kategori) {
        echo '  <div class="col-md-12 mb-3">';
        echo '     <small class="credit">' . $no . '. ' . htmlspecialchars($kategori) . '</small><br>';
        echo '      <ul>';

        // Ambil fitur dalam kategori tersebut
        $StmtFitur = $Conn->prepare("SELECT * FROM akses_fitur WHERE kategori = :kategori ORDER BY nama ASC");
        $StmtFitur->execute([':kategori' => $kategori]);

        while ($DataFitur = $StmtFitur->fetch(PDO::FETCH_ASSOC)) {
            $id_akses_fitur = $DataFitur['id_akses_fitur'];
            $NamaFitur = $DataFitur['nama'];

            // Cek apakah entitas ini punya akses terhadap fitur ini
            $CekAkses = $Conn->prepare("SELECT COUNT(*) FROM akses_referensi WHERE uuid_akses_entitas = :uuid AND id_akses_fitur = :fitur");
            $CekAkses->execute([
                ':uuid' => $uuid_akses_entitas,
                ':fitur' => $id_akses_fitur
            ]);
            $hasAccess = $CekAkses->fetchColumn() > 0;

            if ($hasAccess) {
                echo '<li><code class="text text-grayish">' . htmlspecialchars($NamaFitur) . ' <i class="bi bi-check text-success"></i></code></li>';
            } else {
                echo '<li><code class="text text-grayish">' . htmlspecialchars($NamaFitur) . '</code></li>';
            }
        }

        echo '      </ul>';
        echo '  </div>';
        $no++;
    }
?>
</div>
