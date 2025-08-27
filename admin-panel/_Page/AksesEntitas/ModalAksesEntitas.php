<!-- Filter Data -->
<div class="modal fade" id="ModalFilter" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilter">
                <input type="hidden" name="page" id="page" value="1">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-funnel"></i> Filter Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="batas">
                                <small>Limit/Batas</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                            <select name="batas" id="batas" class="form-control">
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="OrderBy">
                                <small>Mode Urutan</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                             <select name="OrderBy" id="OrderBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="akses">Nama Entitas</option>
                                <option value="keterangan">Keterangan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="ShortBy">
                                <small>Tipe Urutan</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                            <select name="ShortBy" id="ShortBy" class="form-control">
                                <option value="ASC">A To Z</option>
                                <option selected value="DESC">Z To A</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="KeywordBy">
                                <small>Dasar Pencarian</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                            <select name="KeywordBy" id="KeywordBy" class="form-control">
                                <option value="">Pilih</option>
                                <option value="akses">Nama Entitas</option>
                                <option value="keterangan">Keterangan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="keyword">
                                <small>Kata Kunci</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2" id="FormFilter">
                            <input type="text" name="keyword" id="keyword" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalTambahAksesEntitas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahAksesEntitas" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-plus"></i> Tambah Akses Entitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="akses">Nama Entitias</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="akses" id="akses">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="keterangan">Keterangan</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="keterangan" id="keterangan">
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
                                            // Hitung jumlah data
                                            $QryHitung = $Conn->query("SELECT COUNT(*) FROM akses_fitur");
                                            $jml_data = $QryHitung->fetchColumn();

                                            if (empty($jml_data)) {
                                                echo '<tr>';
                                                echo '  <td colspan="4" class="text-center text-danger">Belum ada data fitur aplikasi, silahkan tambahkan fitur aplikasi terlebih dulu</td>';
                                                echo '</tr>';
                                            } else {
                                                $no_kategori = 1;

                                                // Ambil kategori unik
                                                $QryKategoriFitur = $Conn->query("SELECT DISTINCT kategori FROM akses_fitur ORDER BY kategori ASC");
                                                while ($DataKategori = $QryKategoriFitur->fetch(PDO::FETCH_ASSOC)) {
                                                    $kategori = $DataKategori['kategori'];

                                                    echo '<tr>';
                                                    echo '  <td align="center"><b>'.$no_kategori.'</b></td>';
                                                    echo '  <td align="left" colspan="2"><label for="IdKategori'.$no_kategori.'"><b>'.htmlspecialchars($kategori).'</b></label></td>';
                                                    echo '  <td align="center">';
                                                    echo '      <input type="checkbox" class="KelasKategori" id="IdKategori'.$no_kategori.'" value="'.$no_kategori.'">';
                                                    echo '  </td>';
                                                    echo '</tr>';

                                                    $no_fitur = 1;

                                                    // Ambil fitur per kategori (gunakan prepared statement agar aman)
                                                    $QryFitur = $Conn->prepare("SELECT * FROM akses_fitur WHERE kategori = :kategori ORDER BY nama ASC");
                                                    $QryFitur->bindParam(':kategori', $kategori);
                                                    $QryFitur->execute();

                                                    while ($DataFitur = $QryFitur->fetch(PDO::FETCH_ASSOC)) {
                                                        $id_akses_fitur = $DataFitur['id_akses_fitur'];
                                                        $nama           = $DataFitur['nama'];
                                                        $keterangan     = $DataFitur['keterangan'];
                                                        $kode           = $DataFitur['kode'];

                                                        echo '<tr>';
                                                        echo '  <td align="center"></td>';
                                                        echo '  <td align="center"><label for="IdFitur'.$id_akses_fitur.'">'.$no_kategori.'.'.$no_fitur.'</label></td>';
                                                        echo '  <td align="left"><label for="IdFitur'.$id_akses_fitur.'">'.htmlspecialchars($nama).'</label><br><code class="text text-grayish">'.htmlspecialchars($keterangan).'</code></td>';
                                                        echo '  <td align="center">';
                                                        echo '      <input type="checkbox" name="rules[]" class="ListFitur" kategori="'.$no_kategori.'" id="IdFitur'.$id_akses_fitur.'" value="'.$id_akses_fitur.'">';
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
                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            Pastikan data yang anda input sudah benar
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12" id="NotifikasiTambahAksesEntitias"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalDetailEntitias" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark">
                    <i class="bi bi-info-circle"></i> Detail Entitas Akses
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="FormDetailEntitias">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalHapusAksesEntitas" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusAksesEntitas" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Entitas Akses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormHapusAksesEntitas">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiHapusAksesEntitas">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tidak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="ModalEditAksesEntitas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditAksesEntitas" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-pencil"></i> Edit AksesEntitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="FormEditAksesEntitas">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="NotifikasiEditAksesEntitas">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-rounded">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tidak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>