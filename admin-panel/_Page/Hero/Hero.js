$(document).ready(function() {

    //Jika show_button diubah
    $("#show_button").on("change", function() {
        let value = $(this).val();

        if (value === "Sembunyikan") {
            // Kosongkan field
            $("#button_url").val("").prop("readonly", true);
            $("#button_label").val("").prop("readonly", true);
        } else {
            // Aktifkan kembali jika "Tampilkan" atau kosong
            $("#button_url").prop("readonly", false);
            $("#button_label").prop("readonly", false);
        }
    });

    //Proses Tambah Hero/Slider
    $('#ProsesTambahHero').submit(function(e){
        e.preventDefault(); // <- penting biar form tidak reload halaman
        $('#NotifikasiTambahHero').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesTambahHero')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/Hero/ProsesTambahHero.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiTambahHero').html(data);
                var NotifikasiTambahHeroBerhasil=$('#NotifikasiTambahHeroBerhasil').html();
                if(NotifikasiTambahHeroBerhasil=="Success"){

                    //Tampilkan swal
                    Swal.fire(
                        'Success!',
                        'Tambah Hero/Slider Berhasil!',
                        'success'
                    ).then(() => {
                        location.reload();
                    });

                }
            }
        });
    });

    //Modal Hapus
    $('#ModalHapusHero').on('show.bs.modal', function (e) {
        var order = $(e.relatedTarget).data('id');
        $('#order_hapus').val(order);
        $('#NotifikasiHapusHero').html("");
    });

    //Proses Hapus
    $('#ProsesHapusHero').submit(function(e){
        e.preventDefault(); // <- penting biar form tidak reload halaman
        $('#NotifikasiHapusHero').html('<div class="spinner-border text-secondary" role="status"><span class="sr-only"></span></div>');
        var form = $('#ProsesHapusHero')[0];
        var data = new FormData(form);
        $.ajax({
            type        : 'POST',
            url         : '_Page/Hero/ProsesHapusHero.php',
            data        :  data,
            cache       : false,
            processData : false,
            contentType : false,
            enctype     : 'multipart/form-data',
            success     : function(data){
                $('#NotifikasiHapusHero').html(data);
                var NotifikasiHapusHeroBerhasil=$('#NotifikasiHapusHeroBerhasil').html();
                if(NotifikasiHapusHeroBerhasil=="Success"){
                    $('#ModalHapusHero').modal('hide');
                    Swal.fire(
                        'Success!',
                        'Hapus Slider/Hero Berhasil!',
                        'success'
                    ).then(() => {
                        location.reload();
                    });

                }
            }
        });
    });
});