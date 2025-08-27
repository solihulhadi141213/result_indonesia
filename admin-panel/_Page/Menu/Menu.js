//Show List Menu
function ShowListMenu() {
    $.ajax({
        type    : 'POST',
        url     : '_Page/Menu/TabelMenu.php',
        success: function(data) {
            $('#ListMenu').html(data);
        }
    });
}

$(document).ready(function() {
    ShowListMenu();

    //Proses Tambah Komponen Menu
    $('#ProsesTambahMenu').submit(function(){
        $('#NotifikasiTambahAksesEntitias').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambahMenu = $('#ProsesTambahMenu').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Menu/ProsesTambahMenu.php',
            data 	    :  ProsesTambahMenu,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahMenu').html(data);
                var NotifikasiTambahMenuBerhasil=$('#NotifikasiTambahMenuBerhasil').html();
                if(NotifikasiTambahMenuBerhasil=="Success"){
                    $('#NotifikasiTambahMenu').html('<small class="text-muted">Pastikan informasi menu yang ingin anda tambahkan sudah benar</small>');
                    $("#ProsesTambahMenu")[0].reset();
                    $('#ModalTambahMenu').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Tambahh Menu Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    ShowListMenu();
                }
            }
        });
    });

    //Modal Tambah Sub Menu
    $('#ModalTambahSubmenu').on('show.bs.modal', function (e) {
        var menu_order = $(e.relatedTarget).data('id');
        $('#menu_order').val(menu_order);
    });

    //Proses Tambah Komponen Submenu
    $('#ProsesTambahSubmenu').submit(function(){
        $('#NotifikasiTambahSubmenu').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambahSubmenu = $('#ProsesTambahSubmenu').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Menu/ProsesTambahSubmenu.php',
            data 	    :  ProsesTambahSubmenu,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahSubmenu').html(data);
                var NotifikasiTambahSubmenuBerhasil=$('#NotifikasiTambahSubmenuBerhasil').html();
                if(NotifikasiTambahSubmenuBerhasil=="Success"){
                    $('#NotifikasiTambahSubmenu').html('<small class="text-muted">Pastikan informasi menu yang ingin anda tambahkan sudah benar</small>');
                    $("#ProsesTambahSubmenu")[0].reset();
                    $('#ModalTambahSubmenu').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Tambahh Submenu Berhasil!',
                        'success'
                    )

                    //Menampilkan Data
                    ShowListMenu();
                }
            }
        });
    });

    //Modal Hapus Menu
    $('#ModalDeleteMenu').on('show.bs.modal', function (e) {
        var menu_order = $(e.relatedTarget).data('id');
        $('#menu_order_id').val(menu_order);
    });

    //Proses Hapus Menu
    $('#ProsesDeleteMenu').submit(function(){
        $('#NotifikasiHapusAksesEntitas').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesDeleteMenu = $('#ProsesDeleteMenu').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Menu/ProsesDeleteMenu.php',
            data 	    :  ProsesDeleteMenu,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusAksesEntitas').html(data);
                var NotifikasiHapusAksesEntitasBerhasil=$('#NotifikasiHapusAksesEntitasBerhasil').html();
                if(NotifikasiHapusAksesEntitasBerhasil=="Success"){
                    $('#NotifikasiHapusAksesEntitas').html('Apakah anda yakin akan menghapus menu tersebut?');
                    $("#ProsesDeleteMenu")[0].reset();
                    $('#ModalDeleteMenu').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Hapus Menu Berhasil!',
                        'success'
                    )

                    //Menampilkan Data
                    ShowListMenu();
                }
            }
        });
    });

    //Modal Hapus Submenu
    $('#ModalDeleteSubmenu').on('show.bs.modal', function (e) {
        var submenu_order = $(e.relatedTarget).data('id');
        var menu_order = $(e.relatedTarget).data('menuid');
        $('#submenu_order_id').val(submenu_order);
        $('#menu_order_id2').val(menu_order);
    });

    //Proses Hapus Submenu
    $('#ProsesDeleteSubmenu').submit(function(){
        $('#NotifikasiDeleteSubmenu').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesDeleteSubmenu = $('#ProsesDeleteSubmenu').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Menu/ProsesDeleteSubmenu.php',
            data 	    :  ProsesDeleteSubmenu,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiDeleteSubmenu').html(data);
                var NotifikasiDeleteSubmenuBerhasil=$('#NotifikasiDeleteSubmenuBerhasil').html();
                if(NotifikasiDeleteSubmenuBerhasil=="Success"){
                    $('#NotifikasiDeleteSubmenu').html('Apakah anda yakin akan menghapus menu tersebut?');
                    $("#ProsesDeleteSubmenu")[0].reset();
                    $('#ModalDeleteSubmenu').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Hapus Submenu Berhasil!',
                        'success'
                    )

                    //Menampilkan Data
                    ShowListMenu();
                }
            }
        });
    });

    //Modal Edit Menu
    $('#ModalEditMenu').on('show.bs.modal', function (e) {
        var menu_order = $(e.relatedTarget).data('order');
        var menu_url = $(e.relatedTarget).data('url');
        var menu_label = $(e.relatedTarget).data('label');
        $('#menu_order_edit').val(menu_order);
        $('#menu_url_edit').val(menu_url);
        $('#menu_label_edit').val(menu_label);
    });

    //Proses Edit Menu
    $('#ProsesEditMenu').submit(function(){
        $('#NotifikasiEditMenu').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesEditMenu = $('#ProsesEditMenu').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Menu/ProsesEditMenu.php',
            data 	    :  ProsesEditMenu,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditMenu').html(data);
                var NotifikasiEditMenuBerhasil=$('#NotifikasiEditMenuBerhasil').html();
                if(NotifikasiEditMenuBerhasil=="Success"){
                    $('#NotifikasiEditMenu').html('<small class="text-muted">Pastikan informasi menu yang ingin anda ubah sudah benar</small>');
                    $("#ProsesEditMenu")[0].reset();
                    $('#ModalEditMenu').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Edit Menu Berhasil!',
                        'success'
                    )

                    //Menampilkan Data
                    ShowListMenu();
                }
            }
        });
    });

    //Modal Edit Submenu
    $('#ModalEditSubmenu').on('show.bs.modal', function (e) {
        var order_parent = $(e.relatedTarget).data('order_parent');
        var order_child = $(e.relatedTarget).data('order_child');
        var submenu_label = $(e.relatedTarget).data('submenu_label');
        var submenu_url = $(e.relatedTarget).data('submenu_url');
        $('#order_parent').val(order_parent);
        $('#order_child').val(order_child);
        $('#submenu_label_edit').val(submenu_label);
        $('#submenu_url_edit').val(submenu_url);
    });

    //Proses Edit Submenu
    $('#ProsesEditSubmenu').submit(function(){
        $('#NotifikasiEditSubmenu').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesEditMenu = $('#ProsesEditSubmenu').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/Menu/ProsesEditSubmenu.php',
            data 	    :  ProsesEditMenu,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditSubmenu').html(data);
                var NotifikasiEditSubmenuBerhasil=$('#NotifikasiEditSubmenuBerhasil').html();
                if(NotifikasiEditSubmenuBerhasil=="Success"){
                    $('#NotifikasiEditSubmenu').html('<small class="text-muted">Pastikan informasi menu yang ingin anda ubah sudah benar</small>');
                    $("#ProsesEditSubmenu")[0].reset();
                    $('#ModalEditSubmenu').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Edit Submenu Berhasil!',
                        'success'
                    )

                    //Menampilkan Data
                    ShowListMenu();
                }
            }
        });
    });
});