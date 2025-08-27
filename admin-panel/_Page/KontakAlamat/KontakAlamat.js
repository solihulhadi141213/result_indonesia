$(document).ready(function() {
    //Proses Update Title Kontak
    $('#ProsesUpdateTitleKontak').submit(function(){
        $('#NotifikasiUpdateTitleKontak').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesUpdateTitleKontak = $('#ProsesUpdateTitleKontak').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KontakAlamat/ProsesUpdateTitleKontak.php',
            data 	    :  ProsesUpdateTitleKontak,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdateTitleKontak').html(data);
                var NotifikasiUpdateTitleKontakBerhasil=$('#NotifikasiUpdateTitleKontakBerhasil').html();
                if(NotifikasiUpdateTitleKontakBerhasil=="Success"){
                    window.location.href = "index.php?Page=KontakAlamat";
                }
            }
        });
    });

    //Proses Tambah Kontak
     $('#ProsesTambahKontak').submit(function(){
        $('#NotifikasiTambahKontak').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesTambahKontak = $('#ProsesTambahKontak').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KontakAlamat/ProsesTambahKontak.php',
            data 	    :  ProsesTambahKontak,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahKontak').html(data);
                var NotifikasiTambahKontakBerhasil=$('#NotifikasiTambahKontakBerhasil').html();
                if(NotifikasiTambahKontakBerhasil=="Success"){
                    window.location.href = "index.php?Page=KontakAlamat";
                }
            }
        });
    });
    
    //Modal Hapus Kontak
     $('#ModalDeleteKontak').on('show.bs.modal', function (e) {
        var order_id = $(e.relatedTarget).data('id');
        $('#kontak_order_delete').val(order_id);
    });

    //Proses Hapus Kontak
     $('#ProsesDeleteKontak').submit(function(){
        $('#NotifikasiDeleteKontak').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesDeleteKontak = $('#ProsesDeleteKontak').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KontakAlamat/ProsesDeleteKontak.php',
            data 	    :  ProsesDeleteKontak,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiDeleteKontak').html(data);
                var NotifikasiDeleteKontakBerhasil=$('#NotifikasiDeleteKontakBerhasil').html();
                if(NotifikasiDeleteKontakBerhasil=="Success"){
                    window.location.href = "index.php?Page=KontakAlamat";
                }
            }
        });
    });

    //Modal Edit Kontak
     $('#ModalEditKontak').on('show.bs.modal', function (e) {
        var order_id = $(e.relatedTarget).data('id');
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KontakAlamat/FormEditKontak.php',
            data 	    :  {order_id: order_id},
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#FormEditKontak').html(data);
            }
        });
    });

    //Proses Edit Kontak
     $('#ProsesEditKontak').submit(function(){
        $('#NotifikasiEditKontak').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesEditKontak = $('#ProsesEditKontak').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KontakAlamat/ProsesEditKontak.php',
            data 	    :  ProsesEditKontak,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiEditKontak').html(data);
                var NotifikasiEditKontakBerhasil=$('#NotifikasiEditKontakBerhasil').html();
                if(NotifikasiEditKontakBerhasil=="Success"){
                    window.location.href = "index.php?Page=KontakAlamat";
                }
            }
        });
    });

    //Proses Update Alamat
     $('#ProsesUpdateAlamat').submit(function(){
        $('#NotifikasiUpdateAlamat').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var ProsesUpdateAlamat = $('#ProsesUpdateAlamat').serialize();
        $.ajax({
            type 	    : 'POST',
            url 	    : '_Page/KontakAlamat/ProsesUpdateAlamat.php',
            data 	    :  ProsesUpdateAlamat,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiUpdateAlamat').html(data);
                var NotifikasiUpdateAlamatBerhasil=$('#NotifikasiUpdateAlamatBerhasil').html();
                if(NotifikasiUpdateAlamatBerhasil=="Success"){
                    window.location.href = "index.php?Page=KontakAlamat";
                }
            }
        });
    });
});