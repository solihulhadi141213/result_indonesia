<div class="modal fade" id="ModalTambahMenu" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahMenu">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Tambah Menu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="menu_label">
                                <small class="text-muted">Label Menu</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="text" name="menu_label" id="menu_label" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="menu_url">
                                <small class="text-muted">URL</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="url" name="menu_url" id="menu_url" class="form-control" placeholder="https://">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="NotifikasiTambahMenu">
                           <small class="text-muted">Pastikan informasi menu yang ingin anda tambahkan sudah benar</small>
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

<div class="modal fade" id="ModalTambahSubmenu" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesTambahSubmenu">
                <input type="hidden" name="menu_order" id="menu_order">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Tambah Submenu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="submenu_label">
                                <small class="text-muted">Label Menu</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="text" name="submenu_label" id="submenu_label" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="submenu_url">
                                <small class="text-muted">URL</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="url" name="submenu_url" id="submenu_url" class="form-control" placeholder="https://">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="NotifikasiTambahSubmenu">
                           <small class="text-muted">Pastikan informasi menu yang ingin anda tambahkan sudah benar</small>
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

<div class="modal fade" id="ModalDeleteMenu" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesDeleteMenu" autocomplete="off">
                <input type="hidden" name="menu_order_id" id="menu_order_id">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img src="assets/img/question.gif" alt="" width="70%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiHapusAksesEntitas">
                            Apakah anda yakin akan menghapus menu tersebut?
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

<div class="modal fade" id="ModalDeleteSubmenu" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesDeleteSubmenu" autocomplete="off">
                <input type="hidden" name="submenu_order_id" id="submenu_order_id">
                <input type="hidden" name="menu_order_id2" id="menu_order_id2">
                <div class="modal-header">
                    <h5 class="modal-title text-dark"><i class="bi bi-trash"></i> Hapus Submenu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <img src="assets/img/question.gif" alt="" width="70%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center" id="NotifikasiDeleteSubmenu">
                            Apakah anda yakin akan menghapus menu tersebut?
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

<div class="modal fade" id="ModalEditMenu" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditMenu">
                <input type="hidden" name="menu_order_edit" id="menu_order_edit">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Edit Menu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="menu_label_edit">
                                <small class="text-muted">Label Menu</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="text" name="menu_label_edit" id="menu_label_edit" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="menu_url_edit">
                                <small class="text-muted">URL</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="url" name="menu_url_edit" id="menu_url_edit" class="form-control" placeholder="https://">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="NotifikasiEditMenu">
                           <small class="text-muted">Pastikan informasi menu yang ingin anda ubah sudah benar</small>
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

<div class="modal fade" id="ModalEditSubmenu" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form action="javascript:void(0);" id="ProsesEditSubmenu">
                <input type="hidden" name="order_parent" id="order_parent">
                <input type="hidden" name="order_child" id="order_child">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">
                        <i class="bi bi-info-circle"></i> Edit Submenu
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="submenu_label_edit">
                                <small class="text-muted">Label Menu</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="text" name="submenu_label_edit" id="submenu_label_edit" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4 mb-2">
                            <label for="submenu_url_edit">
                                <small class="text-muted">URL</small>
                            </label>
                        </div>
                        <div class="col-8 mb-2">
                           <input type="url" name="submenu_url_edit" id="submenu_url_edit" class="form-control" placeholder="https://">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 mb-2" id="NotifikasiEditSubmenu">
                           <small class="text-muted">Pastikan informasi menu yang ingin anda ubah sudah benar</small>
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