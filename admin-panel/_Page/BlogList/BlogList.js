//Fungsi Menampilkan Data
function filterAndLoadTable() {
    var ProsesFilter = $('#ProsesFilter').serialize();
    $.ajax({
        type    : 'POST',
        url     : '_Page/BlogList/TabelBlog.php',
        data    : ProsesFilter,
        success: function(data) {
            $('#TabelBlog').html(data);
        }
    });
}

$(document).ready(function() {

    //Menampilkan Data Pertama kali
    filterAndLoadTable();

    //Ketika keyword_by diubah
    $('#KeywordBy').change(function(){
        var keyword_by =$('#KeywordBy').val();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/BlogList/FormFilter.php',
            data        : {keyword_by: keyword_by},
            success     : function(data){
                $('#FormFilter').html(data);
            }
        });
    });

    //Jika Filter Di Submit
    $('#ProsesFilter').submit(function(){
        $('#page').val("1");
        filterAndLoadTable();
        $('#ModalFilter').modal('hide');
    });

    //Proses Update Pengaturan Blog
    $('#ProsesUpdatePengaturanBlog').submit(function(e){
        e.preventDefault(); // <- penting biar form tidak reload halaman
        $('#NotifikasiUpdatePengaturanBlog').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesUpdatePengaturanBlog')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/BlogList/ProsesUpdatePengaturanBlog.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdatePengaturanBlog').html(data);
                var NotifikasiUpdatePengaturanBlogBerhasil=$('#NotifikasiUpdatePengaturanBlogBerhasil').html();
                if(NotifikasiUpdatePengaturanBlogBerhasil=="Success"){

                    //Tampilkan swal
                    Swal.fire(
                        'Success!',
                        'Update Pengaturan Blog Berhasil!',
                        'success'
                    ).then(() => {
                        location.reload();
                    });

                }
            }
        });
    });

    //Ketika Proses Tambah Submit
    $('#ProsesTambah').submit(function(){
        $('#NotifikasiTambah').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesTambah')[0];
        var data = new FormData(form);
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/BlogList/ProsesTambah.php',
            data 	    :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambah').html(data);
                var NotifikasiTambahBerhasil=$('#NotifikasiTambahBerhasil').html();
                if(NotifikasiTambahBerhasil=="Success"){
                    $('#NotifikasiTambahBerhasil').html('');
                    $('#ModalTambah').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Tambah Konten Blog Berhasil!',
                        'success'
                    )
                    //Menampilkan Data
                    filterAndLoadTable();

                    //Kosongkan Notifikasi
                    $('#NotifikasiTambahBerhasil').html('');

                    // Reset form setelah sukses
                    $('#ProsesTambah')[0].reset();
                }
            }
        });
    });
    
    //Modal Edit
     $('#ModalEdit').on('show.bs.modal', function (e) {

        //Tangkap Data
        var id = $(e.relatedTarget).data('id');
        var mode = $(e.relatedTarget).data('mode');

        //Loading Form
        $('#FormEdit').html("Loading...");

        //Clear Old Notification
        $('#NotifikasiEdit').html("");

        //Open Data With AJAX
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/BlogList/FormEdit.php',
            data        : {id: id, mode: mode},
            success     : function(data){
                $('#FormEdit').html(data);
            }
        });
    });
    
    //Proses Edit
    $('#ProsesEdit').submit(function(e){
        e.preventDefault(); // <- penting biar form tidak reload halaman
        $('#NotifikasiEdit').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesEdit')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/BlogList/ProsesEdit.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEdit').html(data);
                var NotifikasiEditBerhasil=$('#NotifikasiEditBerhasil').html();
                var ModeReload=$('#ModeReloadEdit').html();
                if(NotifikasiEditBerhasil=="Success"){

                    //tutup modal
                    $('#ModalEdit').modal('hide');

                    //Tampilkan swal
                    Swal.fire(
                        'Success!',
                        'Edit Konten Blog Berhasil!',
                        'success'
                    ).then(() => {
                        if(ModeReload=="Reload"){
                            // Reload setelah swal ditutup
                            location.reload();
                        }else{
                            //Jika Refresh
                            $('#NotifikasiEdit').html('');
                            // Reset form setelah sukses
                            $('#ProsesEdit')[0].reset();
                            //Menampilkan Data
                            filterAndLoadTable();
                        }
                    });

                }
            }
        });
    });

    //Modal Hapus
    $('#ModalHapus').on('show.bs.modal', function (e) {
        var id = $(e.relatedTarget).data('id');
        var mode = $(e.relatedTarget).data('mode');
        $('#id_blog_hapus').val(id);
        $('#mode_hapus').val(mode);
    });

    //Proses Hapus
    $('#ProsesHapus').submit(function(e){
        e.preventDefault(); // <- penting biar form tidak reload halaman
        $('#NotifikasiHapus').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapus')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/BlogList/ProsesHapus.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapus').html(data);
                var NotifikasiHapusBerhasil=$('#NotifikasiHapusBerhasil').html();
                var ModeReload=$('#ModeReload').html();
                if(NotifikasiHapusBerhasil=="Success"){
                    $('#ModalHapus').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Hapus Konten Blog Berhasil!',
                        'success'
                    ).then(() => {
                        if(ModeReload=="Reload"){
                            // Reload setelah swal ditutup
                            window.location.href = "index.php?Page=BlogList";
                        }else{
                            //Jika Refresh
                            $('#NotifikasiHapus').html('');
                            // Reset form setelah sukses
                            $('#ProsesHapus')[0].reset();
                            //Menampilkan Data
                            filterAndLoadTable();
                        }
                    });

                }
            }
        });
    });

    //Modal Tambah Konten
    $('#ModalTambahKonten').on('show.bs.modal', function (e) {

        //Tangkap Data
        var id = $(e.relatedTarget).data('id');
        var order = $(e.relatedTarget).data('order');

        // Reset Form
        $('#ProsesTambahKonten')[0].reset();

        //Tempelkan Nilai
        $('#id_blog_tambah_konten').val(id);
        $('#order_tambah_konten').val(order);

        //Reload form
        $('#FormTambahKonten').html("");

        //Clear Old Notification
        $('#NotifikasiTambahKonten').html("");

    });

    //Ketika Tipe Konten Di Ubah
    $('#type_konten').change(function(e){
        var type_konten = $('#type_konten').val();

        //Routing Form Dengan AJAX
        $.ajax({
            type    : 'POST',
            url     : '_Page/BlogList/FormTambahKonten.php',
            data    : {type_konten: type_konten},
            success: function(data) {
                $('#FormTambahKonten').html(data);
            }
        });
    });

    //Proses Tambah Konten
    $('#ProsesTambahKonten').submit(function(e){
        e.preventDefault(); 
        // penting biar form tidak reload halaman
        $('#NotifikasiTambahKonten').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesTambahKonten')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/BlogList/ProsesTambahKonten.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahKonten').html(data);
                var NotifikasiTambahKontenBerhasil=$('#NotifikasiTambahKontenBerhasil').html();
               
                if(NotifikasiTambahKontenBerhasil=="Success"){
                    $('#ModalTambahKonten').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Tambah Konten Blog Berhasil!',
                        'success'
                    ).then(() => {
                        location.reload();
                    });

                }
            }
        });
    });

    //Modal Edit Konten
    $('#ModalEditContent').on('show.bs.modal', function (e) {

        //Tangkap Data
        var id_blog = $(e.relatedTarget).data('id');
        var order_id = $(e.relatedTarget).data('order');

        //Clear Old Notification
        $('#NotifikasiEditContent').html("");

        //Tampilkan Form Dengan AJAX
        $.ajax({
            type    : 'POST',
            url     : '_Page/BlogList/FormEditContent.php',
            data    : {id_blog: id_blog, order_id: order_id},
            success: function(data) {
                $('#FormEditContent').html(data);
            }
        });
    });

    //Proses Edit  Konten
    $('#ProsesEditContent').submit(function(e){
        e.preventDefault(); 
        // penting biar form tidak reload halaman
        $('#NotifikasiEditContent').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesEditContent')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/BlogList/ProsesEditContent.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditContent').html(data);
                var NotifikasiEditContentBerhasil=$('#NotifikasiEditContentBerhasil').html();
               
                if(NotifikasiEditContentBerhasil=="Success"){
                    location.reload();
                }
            }
        });
    });

    //Modal Delete Konten
    $('#ModalHapusContent').on('show.bs.modal', function (e) {

        //Tangkap Data
        var id_blog = $(e.relatedTarget).data('id');
        var order_id = $(e.relatedTarget).data('order');

        //Tempelkan Nilai
        $('#id_blog_delete').val(id_blog);
        $('#order_id_delete').val(order_id);

        //Clear Old Notification
        $('#NotifikasiHapusContent').html("");

    });

    //Proses Hapus  Konten
    $('#ProsesHapusContent').submit(function(e){
        e.preventDefault(); 
        // penting biar form tidak reload halaman
        $('#NotifikasiHapusContent').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapusContent')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/BlogList/ProsesHapusContent.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusContent').html(data);
                var NotifikasiHapusContentBerhasil=$('#NotifikasiHapusContentBerhasil').html();
               
                if(NotifikasiHapusContentBerhasil=="Success"){
                    location.reload();
                }
            }
        });
    });
});