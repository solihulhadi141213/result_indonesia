<?php
// Zona Waktu & Koneksi
date_default_timezone_set("Asia/Jakarta");
include "../../_Config/Connection.php";
include "../../_Config/GlobalFunction.php";
include "../../_Config/Session.php";

// Ambil parameter input
$keyword_by = $_POST['keyword_by'] ?? "";
$keyword    = $_POST['keyword'] ?? "";
$batas      = isset($_POST['batas']) && is_numeric($_POST['batas']) ? (int) $_POST['batas'] : 10;
$ShortBy    = $_POST['ShortBy'] ?? "DESC";
$NextShort  = ($ShortBy === "ASC") ? "DESC" : "ASC";
$OrderBy    = $_POST['OrderBy'] ?? "id_akses";
$page       = isset($_POST['page']) && is_numeric($_POST['page']) ? (int) $_POST['page'] : 1;
$posisi     = ($page - 1) * $batas;

// Validasi input yang diizinkan
$allowedKeyword = ['nama_akses','kontak_akses','email_akses','akses','datetime_daftar','datetime_update'];
$allowedOrder   = ['id_akses','nama_akses','akses','datetime_daftar'];
$allowedSort    = ['ASC','DESC'];

if (!in_array($OrderBy, $allowedOrder)) $OrderBy = "id_akses";
if (!in_array($ShortBy, $allowedSort)) $ShortBy = "DESC";

// WHERE clause dan binding parameter
$where = "";
$params = [];

if (!empty($keyword)) {
    if (!empty($keyword_by) && in_array($keyword_by, $allowedKeyword)) {
        $where = "WHERE $keyword_by LIKE :keyword";
        $params[':keyword'] = "%$keyword%";
    } else {
        $where = "WHERE 
            nama_akses LIKE :k1 OR 
            kontak_akses LIKE :k2 OR 
            email_akses LIKE :k3 OR 
            akses LIKE :k4 OR 
            datetime_daftar LIKE :k5 OR 
            datetime_update LIKE :k6";
        $params = [
            ':k1' => "%$keyword%",
            ':k2' => "%$keyword%",
            ':k3' => "%$keyword%",
            ':k4' => "%$keyword%",
            ':k5' => "%$keyword%",
            ':k6' => "%$keyword%",
        ];
    }
}

// Hitung total data
$stmtCount = $Conn->prepare("SELECT COUNT(*) FROM akses $where");
$stmtCount->execute($params);
$jml_data = $stmtCount->fetchColumn();
$JmlHalaman = ceil($jml_data / $batas);

if ($jml_data == 0) {
    echo '<tr><td colspan="6" class="text-center"><code class="text-danger">Tidak Ada Data Akses Yang Dapat Ditampilkan</code></td></tr>';
} else {
    $sql = "SELECT * FROM akses $where ORDER BY $OrderBy $ShortBy LIMIT :posisi, :batas";
    $stmt = $Conn->prepare($sql);

    // Bind parameter pencarian
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    // Bind paginasi
    $stmt->bindValue(':posisi', $posisi, PDO::PARAM_INT);
    $stmt->bindValue(':batas', $batas, PDO::PARAM_INT);
    $stmt->execute();

    $no = 1 + $posisi;
    while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id_akses = $data['id_akses'];
        $nama_akses = htmlspecialchars($data['nama_akses']);
        $kontak_akses = htmlspecialchars($data['kontak_akses']);
        $email_akses = htmlspecialchars($data['email_akses']);
        $akses = htmlspecialchars($data['akses']);
        $datetime_daftar = date('d/m/Y H:i:s', strtotime($data['datetime_daftar']));
        $datetime_update = date('d/m/Y H:i:s', strtotime($data['datetime_update']));

        echo "<tr>
            <td><small>$no</small></td>
            <td><small><a href='javascript:void(0);' data-bs-toggle='modal' data-bs-target='#ModalDetailAkses' data-id='$id_akses' class='text-decoration-underline'>$nama_akses</a></small></td>
            <td><small>$kontak_akses</small></td>
            <td><small>$email_akses</small></td>
            <td><small>$akses</small></td>
            <td>
                <a class='btn btn-sm btn-outline-dark btn-floating' href='#' data-bs-toggle='dropdown'><i class='bi bi-three-dots-vertical'></i></a>
                <ul class='dropdown-menu dropdown-menu-end dropdown-menu-arrow'>
                    <li class='dropdown-header text-start'><h6>Option</h6></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalDetailAkses' data-id='$id_akses'><i class='bi bi-info-circle'></i> Detail</a></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalEditAkses' data-id='$id_akses'><i class='bi bi-pencil'></i> Ubah Info</a></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalUbahFotoAkses' data-id='$id_akses'><i class='bi bi-image'></i> Ubah Foto</a></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalUbahPassword' data-id='$id_akses'><i class='bi bi-lock'></i> Ubah Password</a></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalLogAkses' data-id='$id_akses'><i class='bi bi-list-check'></i> Log Aktivitas</a></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalUbahIzinAkses' data-id='$id_akses'><i class='bi bi-list-stars'></i> Izin Akses</a></li>
                    <li><a class='dropdown-item' href='#' data-bs-toggle='modal' data-bs-target='#ModalHapusAkses' data-id='$id_akses'><i class='bi bi-x'></i> Hapus</a></li>
                </ul>
            </td>
        </tr>";
        $no++;
    }
}
?>
<script>
    var page_count = <?php echo $JmlHalaman; ?>;
    var curent_page = <?php echo $page; ?>;
    $('#page_info').html('Page ' + curent_page + ' of ' + page_count);
    $('#prev_button').prop('disabled', curent_page === 1);
    $('#next_button').prop('disabled', curent_page >= page_count);
</script>
