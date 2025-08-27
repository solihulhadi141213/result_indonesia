<?php
    // Koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    // Default variabel
    $JmlHalaman = 0;
    $page = 1;

    // Validasi akses sesi login
    if (empty($SessionIdAkses)) {
        echo '<tr><td colspan="6" class="text-center"><small class="text-danger">Sesi Akses Sudah Berakhir! Silahkan Login Ulang!</small></td></tr>';
        exit;
    }

    // Ambil data dari request dengan default value
    $keyword_by = $_POST['keyword_by'] ?? "";
    $keyword = $_POST['keyword'] ?? "";
    $batas = isset($_POST['batas']) ? (int)$_POST['batas'] : 10;
    $ShortBy = strtoupper($_POST['ShortBy'] ?? "DESC");
    $OrderBy = $_POST['OrderBy'] ?? "id_akses_fitur";
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $posisi = ($page - 1) * $batas;

    // Validasi kolom yang boleh digunakan
    $allowedOrderBy = ['id_akses_fitur', 'kategori', 'nama', 'kode', 'keterangan'];
    $allowedSort = ['ASC', 'DESC'];

    if (!in_array($OrderBy, $allowedOrderBy)) {
        $OrderBy = 'id_akses_fitur';
    }
    if (!in_array($ShortBy, $allowedSort)) {
        $ShortBy = 'DESC';
    }

    try {
        // ========== HITUNG JUMLAH DATA ==========
        if (empty($keyword)) {
            $sqlCount = "SELECT COUNT(*) FROM akses_fitur";
            $stmtCount = $Conn->prepare($sqlCount);
        } else {
            if (empty($keyword_by)) {
                $sqlCount = "SELECT COUNT(*) FROM akses_fitur WHERE 
                    kategori LIKE :kategori OR 
                    nama LIKE :nama OR 
                    kode LIKE :kode OR 
                    keterangan LIKE :keterangan";
                $stmtCount = $Conn->prepare($sqlCount);
                $stmtCount->bindValue(':kategori', "%$keyword%", PDO::PARAM_STR);
                $stmtCount->bindValue(':nama', "%$keyword%", PDO::PARAM_STR);
                $stmtCount->bindValue(':kode', "%$keyword%", PDO::PARAM_STR);
                $stmtCount->bindValue(':keterangan', "%$keyword%", PDO::PARAM_STR);
            } else {
                // validasi kolom pencarian
                if (!in_array($keyword_by, $allowedOrderBy)) {
                    $keyword_by = 'nama';
                }
                $sqlCount = "SELECT COUNT(*) FROM akses_fitur WHERE $keyword_by LIKE :keyword";
                $stmtCount = $Conn->prepare($sqlCount);
                $stmtCount->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
            }
        }

        $stmtCount->execute();
        $jml_data = $stmtCount->fetchColumn();
        $JmlHalaman = ceil($jml_data / $batas);

        // ========== JIKA TIDAK ADA DATA ==========
        if ($jml_data == 0) {
            echo '<tr><td colspan="6" class="text-center"><small class="text-danger">Tidak Ada Data Fitur Aplikasi Yang Ditampilkan!</small></td></tr>';
            exit;
        }

        // ========== AMBIL DATA ==========
        if (empty($keyword)) {
            $sql = "SELECT * FROM akses_fitur ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas";
            $stmt = $Conn->prepare($sql);
        } else {
            if (empty($keyword_by)) {
                $sql = "SELECT * FROM akses_fitur 
                    WHERE kategori LIKE :kategori OR 
                        nama LIKE :nama OR 
                        kode LIKE :kode OR 
                        keterangan LIKE :keterangan 
                    ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas";
                $stmt = $Conn->prepare($sql);
                $stmt->bindValue(':kategori', "%$keyword%", PDO::PARAM_STR);
                $stmt->bindValue(':nama', "%$keyword%", PDO::PARAM_STR);
                $stmt->bindValue(':kode', "%$keyword%", PDO::PARAM_STR);
                $stmt->bindValue(':keterangan', "%$keyword%", PDO::PARAM_STR);
            } else {
                if (!in_array($keyword_by, $allowedOrderBy)) {
                    $keyword_by = 'nama';
                }
                $sql = "SELECT * FROM akses_fitur 
                    WHERE $keyword_by LIKE :keyword 
                    ORDER BY $OrderBy $ShortBy LIMIT $posisi, $batas";
                $stmt = $Conn->prepare($sql);
                $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
            }
        }

        $stmt->execute();

        $no = 1 + $posisi;
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id_akses_fitur = $data['id_akses_fitur'];
            $kategori = $data['kategori'];
            $nama = $data['nama'];
            $kode = $data['kode'];
            $keterangan = $data['keterangan'];

            // Hitung jumlah pengguna fitur ini
            $stmtJumlah = $Conn->prepare("SELECT COUNT(*) FROM akses_ijin WHERE id_akses_fitur = :id_akses_fitur");
            $stmtJumlah->bindValue(':id_akses_fitur', $id_akses_fitur, PDO::PARAM_INT);
            $stmtJumlah->execute();
            $JumlahPengguna = $stmtJumlah->fetchColumn();

            $label_jumlah_pengguna = empty($JumlahPengguna)
                ? '<span class="badge badge-danger">NULL</span>'
                : '<span class="badge badge-success">' . $JumlahPengguna . ' Orang</span>';

            echo '
                <tr>
                    <td><small>' . $no . '</small></td>
                    <td><small>' . $kategori . '</small></td>
                    <td>
                        <a href="javascript:void(0);" class="text text-decoration-underline" data-bs-toggle="modal" data-bs-target="#ModalDetailFitur" data-id="' . $id_akses_fitur . '">
                            <small>' . $nama . '</small>
                        </a>
                    </td>
                    <td><small class="text-muted">' . $kode . '</small></td>
                    <td><small>' . $label_jumlah_pengguna . '</small></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-dark btn-floating"  data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start"><h6>Option</h6></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditFitur" data-id="' . $id_akses_fitur . '"><i class="bi bi-pencil"></i> Edit Fitur</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusFitur" data-id="' . $id_akses_fitur . '"><i class="bi bi-x"></i> Hapus Fitur</a></li>
                        </ul>
                    </td>
                </tr>';
            $no++;
        }

    } catch (PDOException $e) {
        echo '<tr><td colspan="6" class="text-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
    }
?>
<!-- Pagination Info & Button State -->
<script>
    var page_count = <?php echo $JmlHalaman; ?>;
    var curent_page = <?php echo $page; ?>;
    $('#page_info').html('Page ' + curent_page + ' Of ' + page_count);
    $('#prev_button').prop('disabled', curent_page === 1);
    $('#next_button').prop('disabled', curent_page >= page_count);
</script>