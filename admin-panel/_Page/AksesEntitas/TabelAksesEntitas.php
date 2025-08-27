<?php
    include "../../_Config/Connection.php";
    include "../../_Config/GlobalFunction.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");

    if (empty($SessionIdAkses)) {
        echo '
            <tr>
                <td colspan="6" align="center">
                    <small class="text-danger">Sesi Akses Sudah Berakhir, Silahkan Login Ulang!</small>
                </td>
            </tr>
        ';
        exit;
    }

    // Konfigurasi input
    $allowedOrderBy  = ['akses', 'keterangan'];
    $allowedShortBy  = ['ASC', 'DESC'];
    $allowedKeywordBy = ['akses', 'keterangan'];

    // Ambil parameter
    $keyword_by = $_POST['keyword_by'] ?? "";
    $keyword    = $_POST['keyword'] ?? "";
    $batas      = isset($_POST['batas']) && is_numeric($_POST['batas']) ? (int)$_POST['batas'] : 10;
    $ShortBy    = in_array($_POST['ShortBy'] ?? 'DESC', $allowedShortBy) ? $_POST['ShortBy'] : 'DESC';
    $OrderBy    = in_array($_POST['OrderBy'] ?? 'akses', $allowedOrderBy) ? $_POST['OrderBy'] : 'akses';
    $page       = isset($_POST['page']) && is_numeric($_POST['page']) ? (int)$_POST['page'] : 1;
    $posisi     = ($page - 1) * $batas;
    $NextShort  = $ShortBy === "ASC" ? "DESC" : "ASC";

    // Filter pencarian
    $whereClause = "";
    $params = [];

    if (!empty($keyword)) {
        if (!empty($keyword_by) && in_array($keyword_by, $allowedKeywordBy)) {
            $whereClause = "WHERE $keyword_by LIKE :keyword";
            $params[':keyword'] = "%$keyword%";
        } else {
           $whereClause = "WHERE akses LIKE :kw1 OR keterangan LIKE :kw2";
            $params[':kw1'] = "%$keyword%";
            $params[':kw2'] = "%$keyword%";
        }
    }

    // Hitung total data
    $StmtCount = $Conn->prepare("SELECT COUNT(*) FROM akses_entitas $whereClause");
    $StmtCount->execute($params);
    $jml_data = $StmtCount->fetchColumn();
    $JmlHalaman = ceil($jml_data / $batas);

    // Jika tidak ada data
    if (empty($jml_data)) {
        echo '
            <tr>
                <td colspan="6" class="text-center">
                    <code class="text-danger">Tidak Ada Data Entitias Yang Dapat Ditampilkan</code>
                </td>
            </tr>
        ';
        exit;
    }

    // Ambil data akses_entitas
    $sql = "SELECT * FROM akses_entitas $whereClause ORDER BY $OrderBy $ShortBy LIMIT :posisi, :batas";
    $StmtData = $Conn->prepare($sql);
    foreach ($params as $key => $val) {
        $StmtData->bindValue($key, $val);
    }
    $StmtData->bindValue(':posisi', $posisi, PDO::PARAM_INT);
    $StmtData->bindValue(':batas', $batas, PDO::PARAM_INT);
    $StmtData->execute();

    // Tampilkan data
    $no = 1 + $posisi;
    while ($data = $StmtData->fetch(PDO::FETCH_ASSOC)) {
        $uuid_akses_entitas = $data['uuid_akses_entitas'];
        $akses              = $data['akses'];
        $keterangan         = $data['keterangan'];

        // Jumlah pengguna
        $stmtUser = $Conn->prepare("SELECT COUNT(*) FROM akses WHERE akses = :akses");
        $stmtUser->execute([':akses' => $akses]);
        $JumlahPengguna = $stmtUser->fetchColumn();

        // Jumlah role
        $stmtRole = $Conn->prepare("SELECT COUNT(*) FROM akses_referensi WHERE uuid_akses_entitas = :uuid");
        $stmtRole->execute([':uuid' => $uuid_akses_entitas]);
        $JumlahRole = $stmtRole->fetchColumn();

        echo '
            <tr>
                <td align="left">' . $no . '</td>
                <td align="left">' . htmlspecialchars($akses) . '</td>
                <td align="left"><small>' . $JumlahPengguna . ' User</small></td>
                <td align="left"><small>' . $JumlahRole . ' Record</small></td>
                <td align="left">
                    <a class="btn btn-sm btn-outline-dark btn-floating" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Option</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalDetailEntitias" data-id="' . $uuid_akses_entitas . '">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalEditAksesEntitas" data-id="' . $uuid_akses_entitas . '">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#ModalHapusAksesEntitas" data-id="' . $uuid_akses_entitas . '">
                                <i class="bi bi-x"></i> Hapus
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>
        ';
        $no++;
    }
?>
<script>
    var page_count = <?php echo $JmlHalaman; ?>;
    var curent_page = <?php echo $page; ?>;
    $('#page_info').html('Page ' + curent_page + ' Of ' + page_count);
    $('#prev_button').prop('disabled', curent_page === 1);
    $('#next_button').prop('disabled', curent_page >= page_count);
</script>