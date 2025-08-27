<div class="modal fade" id="ModalTambahHero" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahHero">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-plus"></i> Tambah Hero/Slider
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                    <div class="row mb-3">
                        <div class="col-3"><label for="title">Judul</label></div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><label for="sub_title">Sub Judul</label></div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <input type="text" name="sub_title" id="sub_title" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><label for="image">Latar Belakang</label></div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><label for="show_button">Buat Tombol</label></div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <select name="show_button" id="show_button" class="form-control">
                                <option value="Tampilkan">Tampilkan</option>
                                <option value="Sembunyikan">Sembunyikan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><label for="button_url">URL Tombol</label></div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <input type="url" name="button_url" id="button_url" class="form-control" placeholder="https://">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3"><label for="button_label">Label Tombol</label></div>
                        <div class="col-1">:</div>
                        <div class="col-8">
                            <input type="text" name="button_label" id="button_label" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12" id="NotifikasiTambahHero">
                            <!-- Notifikasi Tambah Hero -->
                        </div>
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

<div class="modal fade" id="ModalHapusHero" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesHapusHero">
                <input type="hidden" name="order" id="order_hapus">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Hero/Slider
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-12 mb-2 text-center">
                            <img src="assets/img/question.gif" width="70%">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2 text-center" id="NotifikasiHapusHero">
                           <!-- Notifikasi Hapus Akan Muncul Disini -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2 text-center">
                           <small class="text-muted">Apakah Anda yakin akan menghapus Hero/Slider tersebut?</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="bi bi-check"></i> Ya, Hapus
                    </button>
                    <button type="button" class="btn btn-dark btn-rounded" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>