<?php
require_once '../../_Config/Connection.php';
date_default_timezone_set('Asia/Jakarta');

$db = new Database();
$Conn = $db->getConnection();

$keyword_by = $_POST['keyword_by'] ?? "";
$keyword    = $_POST['keyword'] ?? "";
$batas      = isset($_POST['batas']) ? (int) $_POST['batas'] : 10;
$ShortBy    = $_POST['ShortBy'] ?? "DESC";
$OrderBy    = $_POST['OrderBy'] ?? "datetime_creat";
$page       = isset($_POST['page']) ? (int) $_POST['page'] : 1;
$posisi     = ($page - 1) * $batas;

$where = "WHERE b.publish = 1"; // filter utama publish = 1
$join = "";
$params_filter = [];

// =======================
// Filter keyword
// =======================
if (!empty($keyword)) {
    if ($keyword_by === "blog_tag") {
        $join .= " LEFT JOIN blog_tag bt ON b.id_blog = bt.id_blog";
        $where .= " AND bt.blog_tag LIKE :keyword";
        $params_filter[':keyword'] = "%$keyword%";
    } elseif (in_array($keyword_by, ['title_blog', 'deskripsi', 'author_blog'])) {
        $where .= " AND b.$keyword_by LIKE :keyword";
        $params_filter[':keyword'] = "%$keyword%";
    } else {
        // Cari di title & deskripsi
        $where .= " AND (b.title_blog LIKE :keyword1 OR b.deskripsi LIKE :keyword2)";
        $params_filter[':keyword1'] = "%$keyword%";
        $params_filter[':keyword2'] = "%$keyword%";
    }
}

// =======================
// Hitung total data
// =======================
$sql_count = "SELECT COUNT(DISTINCT b.id_blog) FROM blog b $join $where";
$stmt_count = $Conn->prepare($sql_count);
$stmt_count->execute($params_filter);
$jml_data = $stmt_count->fetchColumn();
$JmlHalaman = ceil($jml_data / $batas);

// =======================
// Ambil data artikel
// =======================
$sql_berita = "SELECT DISTINCT b.* 
               FROM blog b 
               $join 
               $where 
               ORDER BY b.$OrderBy $ShortBy 
               LIMIT :batas OFFSET :offset";
$stmt_berita = $Conn->prepare($sql_berita);

// Gabungkan filter + limit
$params_berita = $params_filter;
$params_berita[':offset'] = $posisi;
$params_berita[':batas'] = $batas;

foreach ($params_berita as $key => $val) {
    if ($key === ':batas' || $key === ':offset') {
        $stmt_berita->bindValue($key, (int)$val, PDO::PARAM_INT);
    } else {
        $stmt_berita->bindValue($key, $val, PDO::PARAM_STR);
    }
}

$stmt_berita->execute();
$berita_list = $stmt_berita->fetchAll(PDO::FETCH_ASSOC);

// =======================
// Tampilkan data
// =======================
if (count($berita_list) > 0) {
    foreach ($berita_list as $berita_artikel) {
        $date_time_creat_blog = date('d/m/Y H:i T', strtotime($berita_artikel['datetime_creat']));
        $id_blog = $berita_artikel['id_blog'];
        $title_blog = $berita_artikel['title_blog'];
        $cover_image = 'image_proxy.php?segment=Artikel&image_name=' . $berita_artikel['cover'];
        $deskripsi_blog = $berita_artikel['deskripsi'];

        // Ambil tag/kategori
        $sql_tag = "SELECT blog_tag FROM blog_tag WHERE id_blog = :id_blog";
        $stmt_tag = $Conn->prepare($sql_tag);
        $stmt_tag->execute([':id_blog' => $id_blog]);
        $tags = $stmt_tag->fetchAll(PDO::FETCH_COLUMN);
?>
        <div class="d-flex mb-4 border-bottom pb-3">
            <div class="me-3" style="flex: 0 0 150px;">
                <img src="<?= $cover_image ?>" alt="Cover" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
            </div>
            <div>
                <h5 class="fw-bold mb-1">
                    <a href="Blog?id=<?= $id_blog ?>" class="text text-dark text-decoration-none">
                        <?= htmlspecialchars($title_blog) ?>
                    </a>
                </h5>
                <p class="mb-1"><?= nl2br(htmlspecialchars($deskripsi_blog)) ?></p>
                <small class="text-muted"><?= $date_time_creat_blog ?></small><br>
                <?php if (!empty($tags)) : ?>
                    <div class="mt-1">
                        <small class="text-muted">Tag: 
                            <?php foreach ($tags as $tag): ?>
                                <span class="label label-secondary"><?= htmlspecialchars($tag) ?></span>,
                            <?php endforeach; ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php
    }
} else {
    echo '<p class="text-muted">Belum ada artikel yang tersedia.</p>';
}
?>

<script>
    var page_count = <?= $JmlHalaman ?>;
    var curent_page = <?= $page ?>;

    $('#page_position').html('Page ' + curent_page + ' Of ' + page_count);
    $('#prev_button').prop('disabled', curent_page == 1);
    $('#next_button').prop('disabled', curent_page >= page_count);
</script>