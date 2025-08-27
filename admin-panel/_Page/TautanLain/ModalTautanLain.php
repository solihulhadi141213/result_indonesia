<div class="modal fade" id="ModalTambah" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambah">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Tambah Tautan Lainnya
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="label_tautan">
                                <small class="text-muted">Label</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="text" name="label_tautan" id="label_tautan" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="url_tautan">
                                <small class="text-muted">URL</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="url" name="url_tautan" id="url_tautan" class="form-control" placeholder="https://">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="target_tautan">
                                <small class="text-muted">Target</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                            <select name="target_tautan" id="target_tautan" class="form-control">
                                <option value="">Pilih</option>
                                <option value="_self">_self</option>
                                <option value="_blank">_blank</option>
                                <option value="_parent">_parent</option>
                                <option value="_top">_top</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="NotifikasiTambah">
                           <small class="text-muted">Pastikan informasi yang ingin anda tambahkan sudah benar</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
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
<div class="modal fade" id="ModalEdit" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEdit">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-pencil"></i> Edit Tautan Lainnya
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="FormEdit">
                           <!-- Form Edit Kontak -->
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="NotifikasiEdit">
                           <small class="text-muted">Pastikan informasi yang ingin anda tambahkan sudah benar</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-rounded">
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
<div class="modal fade" id="ModalDelete" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesDelete">
                <input type="hidden" name="tautan_lainnya_order" id="tautan_lainnya_order_delete">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-trash"></i> Hapus Tautan Lainnya
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
                        <div class="col-12 mb-2 text-center" id="NotifikasiDelete">
                           <small class="text-muted">Apakah Anda yakin akan menghapus informasi tersebut?</small>
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
