<div class="modal fade" id="ModalFilterGrafik" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesFilterGrafik">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-filter"></i> Filter Grafik
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-3">
                           <label for="periode">Periode Data</label>
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <select name="periode" id="periode" class="form-control">
                                <option value="Tahun">Tahun</option>
                                <option value="Bulan">Bulan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-3">
                           <label for="tahun" id="FormTahun">Tahun</label>
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <input type="number" class="form-control" name="tahun" id="tahun" value="<?php echo date('Y'); ?>">
                        </div>
                    </div>
                    <div class="row mb-2" id="FormBulan">
                        <div class="col-3">
                           <label for="bulan">Bulan</label>
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <select name="bulan" id="bulan" class="form-control">
                                <?php
                                    // Daftar bulan
                                    $bulan = [
                                        "01" => "Januari",
                                        "02" => "Februari",
                                        "03" => "Maret",
                                        "04" => "April",
                                        "05" => "Mei",
                                        "06" => "Juni",
                                        "07" => "Juli",
                                        "08" => "Agustus",
                                        "09" => "September",
                                        "10" => "Oktober",
                                        "11" => "November",
                                        "12" => "Desember"
                                    ];

                                    // Ambil bulan sekarang
                                    $bulanSekarang = date("m");
                                    foreach ($bulan as $val => $nama):
                                ?>
                                    <option value="<?= $val ?>" <?= ($val == $bulanSekarang) ? 'selected' : '' ?>>
                                        <?= $nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-arrow-right"></i> Tampilkan
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
