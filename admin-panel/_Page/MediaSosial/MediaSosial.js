$(document).ready(function() {
    //Proses Update Title Medsos
    $('#ProsesUpdateMedsos').submit(function(){
        $('#NotifikasiUpdateTitleMedsos').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesUpdateMedsos = $('#ProsesUpdateMedsos').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/MediaSosial/ProsesUpdateMedsos.php',
            data 	    :  ProsesUpdateMedsos,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdateTitleMedsos').html(data);
                var NotifikasiUpdateTitleMedsosBerhasil=$('#NotifikasiUpdateTitleMedsosBerhasil').html();
                if(NotifikasiUpdateTitleMedsosBerhasil=="Success"){
                    window.location.href = "index.php?Page=MediaSosial";
                }
            }
        });
    });

    //Proses Tambah Kontak
     $('#ProsesTambahMedsos').submit(function(){
        $('#NotifikasiTambahMedsos').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambahMedsos = $('#ProsesTambahMedsos').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/MediaSosial/ProsesTambahMedsos.php',
            data 	    :  ProsesTambahMedsos,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahMedsos').html(data);
                var NotifikasiTambahMedsosBerhasil=$('#NotifikasiTambahMedsosBerhasil').html();
                if(NotifikasiTambahMedsosBerhasil=="Success"){
                    window.location.href = "index.php?Page=MediaSosial";
                }
            }
        });
    });
    
    //Modal Hapus Medsos
     $('#ModalDelete').on('show.bs.modal', function (e) {
        var order_id = $(e.relatedTarget).data('id');
        $('#medsos_order_delete').val(order_id);
    });

    //Proses Hapus Medsos
     $('#ProsesDelete').submit(function(){
        $('#NotifikasiDelete').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesDelete = $('#ProsesDelete').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/MediaSosial/ProsesDelete.php',
            data 	    :  ProsesDelete,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiDelete').html(data);
                var NotifikasiDeleteBerhasil=$('#NotifikasiDeleteBerhasil').html();
                if(NotifikasiDeleteBerhasil=="Success"){
                    window.location.href = "index.php?Page=MediaSosial";
                }
            }
        });
    });

    //Modal Edit
     $('#ModalEdit').on('show.bs.modal', function (e) {
        var order_id = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/MediaSosial/FormEdit.php',
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
            url 	    : '_Page/MediaSosial/ProsesEdit.php',
            data 	    :  ProsesEdit,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEdit').html(data);
                var NotifikasiEditBerhasil=$('#NotifikasiEditBerhasil').html();
                if(NotifikasiEditBerhasil=="Success"){
                    window.location.href = "index.php?Page=MediaSosial";
                }
            }
        });
    });
});