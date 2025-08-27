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

    // Ambil detail entitas
    $StmtEntitas = $Conn->prepare("SELECT * FROM akses_entitas WHERE uuid_akses_entitas = :uuid");
    $StmtEntitas->execute([':uuid' => $uuid_akses_entitas]);
    $DataEntitas = $StmtEntitas->fetch(PDO::FETCH_ASSOC);

    if (!$DataEntitas) {
        echo '<code>ID Entitias Tidak Valid, Atau Tidak Ditemukan Pada Database!</code>';
        exit;
    }

    $NamaAkses = $DataEntitas['akses'];
    $KeteranganEntitias = $DataEntitas['keterangan'];
?>

<input type="hidden" name="uuid_akses_entitas" value="<?php echo $uuid_akses_entitas; ?>">

<div class="row mb-3">
    <div class="col-md-3"><label for="akses_edit">Nama Entitias</label></div>
    <div class="col-md-9">
        <input type="text" class="form-control" name="akses" id="akses_edit" value="<?php echo htmlspecialchars($NamaAkses); ?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3"><label for="keterangan_edit">Keterangan</label></div>
    <div class="col-md-9">
        <input type="text" class="form-control" name="keterangan" id="keterangan_edit" value="<?php echo htmlspecialchars($KeteranganEntitias); ?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="table table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <td align="center" colspan="4"><b>PENGATURAN IJIN AKSES FITUR</b></td>
                    </tr>
                    <tr>
                        <td align="center"><b>No</b></td>
                        <td colspan="2" align="center"><b>Kategori/Fitur</b></td>
                        <td align="center"><b>Check</b></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Cek jumlah data fitur
                $stmtCek = $Conn->query("SELECT COUNT(*) FROM akses_fitur");
                $jumlah_fitur = $stmtCek->fetchColumn();

                if (empty($jumlah_fitur)) {
                    echo '<tr>';
                    echo '  <td colspan="4" class="text-center text-danger">Belum ada data fitur aplikasi, silahkan tambahkan fitur terlebih dulu</td>';
                    echo '</tr>';
                } else {
                    $no_kategori = 1;
                    $stmtKategori = $Conn->query("SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                    $KategoriList = $stmtKategori->fetchAll(PDO::FETCH_COLUMN);

                    foreach ($KategoriList as $kategori) {
                        echo '<tr>';
                        echo '  <td align="center"><b>' . $no_kategori . '</b></td>';
                        echo '  <td align="left" colspan="2"><label for="IdKategoriEdit' . $no_kategori . '"><b>' . htmlspecialchars($kategori) . '</b></label></td>';
                        echo '  <td align="center"></td>';
                        echo '</tr>';

                        $stmtFitur = $Conn->prepare("SELECT * FROM akses_fitur WHERE kategori = :kategori ORDER BY nama ASC");
                        $stmtFitur->execute([':kategori' => $kategori]);

                        $no_fitur = 1;
                        while ($fitur = $stmtFitur->fetch(PDO::FETCH_ASSOC)) {
                            $id_akses_fitur = $fitur['id_akses_fitur'];
                            $nama = $fitur['nama'];
                            $keterangan = $fitur['keterangan'];

                            // Cek apakah entitas ini punya akses ke fitur ini
                            $stmtCekAkses = $Conn->prepare("SELECT COUNT(*) FROM akses_referensi WHERE uuid_akses_entitas = :uuid AND id_akses_fitur = :fitur");
                            $stmtCekAkses->execute([
                                ':uuid' => $uuid_akses_entitas,
                                ':fitur' => $id_akses_fitur
                            ]);
                            $hasAccess = $stmtCekAkses->fetchColumn() > 0;

                            echo '<tr>';
                            echo '  <td align="center"></td>';
                            echo '  <td align="center"><label for="IdFiturEdit' . $id_akses_fitur . '">' . $no_kategori . '.' . $no_fitur . '</label></td>';
                            echo '  <td align="left"><label for="IdFiturEdit' . $id_akses_fitur . '">' . htmlspecialchars($nama) . '</label><br><code class="text text-grayish">' . htmlspecialchars($keterangan) . '</code></td>';
                            echo '  <td align="center">';
                            echo '      <input type="checkbox" name="rules[]" class="ListFitur" kategori="' . $no_kategori . '" id="IdFiturEdit' . $id_akses_fitur . '" value="' . $id_akses_fitur . '"' . ($hasAccess ? ' checked' : '') . '>';
                            echo '  </td>';
                            echo '</tr>';
                            $no_fitur++;
                        }

                        $no_kategori++;
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
