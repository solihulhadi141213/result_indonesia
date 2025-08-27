$(document).ready(function() {
    //Proses Update Title
    $('#ProsesUpdateTitle').submit(function(){
        $('#NotifikasiUpdateTitle').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesUpdateTitle = $('#ProsesUpdateTitle').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TautanLain/ProsesUpdateTitle.php',
            data 	    :  ProsesUpdateTitle,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdateTitle').html(data);
                var NotifikasiUpdateTitleBerhasil=$('#NotifikasiUpdateTitleBerhasil').html();
                if(NotifikasiUpdateTitleBerhasil=="Success"){
                    window.location.href = "index.php?Page=TautanLain";
                }
            }
        });
    });

    //Proses Tambah
     $('#ProsesTambah').submit(function(){
        $('#NotifikasiTambah').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambah = $('#ProsesTambah').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TautanLain/ProsesTambah.php',
            data 	    :  ProsesTambah,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambah').html(data);
                var NotifikasiTambahBerhasil=$('#NotifikasiTambahBerhasil').html();
                if(NotifikasiTambahBerhasil=="Success"){
                    window.location.href = "index.php?Page=TautanLain";
                }
            }
        });
    });
    
    //Modal Hapus Medsos
     $('#ModalDelete').on('show.bs.modal', function (e) {
        var tautan_lainnya_order = $(e.relatedTarget).data('id');
        $('#tautan_lainnya_order_delete').val(tautan_lainnya_order);
    });

    //Proses Hapus Medsos
     $('#ProsesDelete').submit(function(){
        $('#NotifikasiDelete').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesDelete = $('#ProsesDelete').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TautanLain/ProsesDelete.php',
            data 	    :  ProsesDelete,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiDelete').html(data);
                var NotifikasiDeleteBerhasil=$('#NotifikasiDeleteBerhasil').html();
                if(NotifikasiDeleteBerhasil=="Success"){
                    window.location.href = "index.php?Page=TautanLain";
                }
            }
        });
    });

    //Modal Edit
     $('#ModalEdit').on('show.bs.modal', function (e) {
        var order_id = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TautanLain/FormEdit.php',
            data 	    :  {order_id: order_id},
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#FormEdit').html(data);
            }
        });
    });

    //Proses Edit
     $('#ProsesEdit').submit(function(){
        $('#NotifikasiEdit').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesEdit = $('#ProsesEdit').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/TautanLain/ProsesEdit.php',
            data 	    :  ProsesEdit,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEdit').html(data);
                var NotifikasiEditBerhasil=$('#NotifikasiEditBerhasil').html();
                if(NotifikasiEditBerhasil=="Success"){
                    window.location.href = "index.php?Page=TautanLain";
                }
            }
        });
    });
});